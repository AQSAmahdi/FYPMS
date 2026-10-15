<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FypGroup;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\ChatDeletion;
use App\Models\ChatRead;
use App\Models\MessageDeletion;

class ChatController extends Controller
{
    // Check: user ye group ki chat access kar sakta hai? (member ya supervisor)
    private function canAccess($group, $user)
    {
        if ((int) $group->supervisor_id === (int) $user->id) return true;
        return GroupMember::where('fyp_group_id', $group->id)
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
    }

    public function index($groupId)
    {
        $group = FypGroup::findOrFail($groupId);
        $user = auth()->user();

        if (!$this->canAccess($group, $user)) {
            abort(403, 'You do not have access to this group chat.');
        }

        return view('chat.index', compact('group'));
    }

    public function fetchMessages(Request $request, $groupId)
    {
        $group = FypGroup::findOrFail($groupId);
        $user = auth()->user();

        if (!$this->canAccess($group, $user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $deletion = ChatDeletion::where('user_id', $user->id)
            ->where('group_id', $groupId)
            ->first();

        $query = GroupMessage::where('group_id', $groupId)
            ->where('deleted_for_everyone', false)
            ->with('sender');

        if ($deletion) {
            $query->where('created_at', '>', $deletion->cleared_before);
        }

        $hiddenMessageIds = MessageDeletion::where('user_id', $user->id)->pluck('message_id')->toArray();

        $messages = $query->whereNotIn('id', $hiddenMessageIds)->orderBy('created_at')->get()->map(fn($m) => [
            'id'         => $m->id,
            'sender_id'  => $m->sender_id,
            'sender_name'=> $m->sender?->name,
            'message'    => $m->message,
            'is_mine'    => (int) $m->sender_id === (int) $user->id,
            'created_at' => $m->created_at->timezone('Asia/Karachi')->format('d M, h:i A'),
        ]);

        // Mark as read
        ChatRead::updateOrCreate(
            ['user_id' => $user->id, 'group_id' => $groupId],
            ['last_read_at' => now()]
        );

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function send(Request $request, $groupId)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $group = FypGroup::findOrFail($groupId);
        $user = auth()->user();

        if (!$this->canAccess($group, $user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $msg = GroupMessage::create([
            'group_id'  => $groupId,
            'sender_id' => $user->id,
            'message'   => $request->message,
        ]);

        // Notify all other group members + supervisor (badge only, not full notification list)
        $recipientIds = GroupMember::where('fyp_group_id', $groupId)
            ->where('status', 'accepted')
            ->pluck('user_id')
            ->toArray();
        if ($group->supervisor_id) $recipientIds[] = $group->supervisor_id;
        $recipientIds = array_diff(array_unique($recipientIds), [$user->id]);
        // (Badge counts are computed live from chat_reads vs messages, so no extra notification row needed here.)

        return response()->json(['success' => true, 'message_id' => $msg->id]);
    }

    // Clear chat - "for me" only
    public function clearForMe($groupId)
    {
        $user = auth()->user();
        ChatDeletion::updateOrCreate(
            ['user_id' => $user->id, 'group_id' => $groupId],
            ['cleared_before' => now()]
        );
        return response()->json(['success' => true, 'message' => 'Chat cleared for you.']);
    }

    // Clear chat - for everyone
    public function clearForEveryone($groupId)
    {
        $group = FypGroup::findOrFail($groupId);
        $user = auth()->user();

        if (!$this->canAccess($group, $user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        GroupMessage::where('group_id', $groupId)->update(['deleted_for_everyone' => true]);

        return response()->json(['success' => true, 'message' => 'Chat cleared for everyone.']);
    }

    // Delete a single message - for me only
    public function deleteMessageForMe(Request $request, $messageId)
    {
        $message = GroupMessage::findOrFail($messageId);
        $user = auth()->user();

        if (!$this->canAccess($message->group, $user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        MessageDeletion::firstOrCreate([
            'message_id' => $message->id,
            'user_id'    => $user->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Message deleted for you.']);
    }

    // Delete a single message - for everyone (sender only)
    public function deleteMessageForEveryone(Request $request, $messageId)
    {
        $message = GroupMessage::findOrFail($messageId);
        $user = auth()->user();

        if ((int) $message->sender_id !== (int) $user->id) {
            return response()->json(['success' => false, 'message' => 'You can only delete your own messages for everyone.'], 403);
        }

        $message->update(['deleted_for_everyone' => true]);

        return response()->json(['success' => true, 'message' => 'Message deleted for everyone.']);
    }

    // Unread badge count across all of the user's groups (for sidebar icon)
    public function unreadBadge(Request $request)
    {
        $user = auth()->user();

        $groupIds = GroupMember::where('user_id', $user->id)->where('status', 'accepted')->pluck('fyp_group_id');
        if ($groupIds->isEmpty()) {
            $groupIds = FypGroup::where('supervisor_id', $user->id)->pluck('id');
        }

        $totalUnread = 0;
        foreach ($groupIds as $gid) {
            $read = ChatRead::where('user_id', $user->id)->where('group_id', $gid)->first();
            $deletion = ChatDeletion::where('user_id', $user->id)->where('group_id', $gid)->first();

            $query = GroupMessage::where('group_id', $gid)
                ->where('deleted_for_everyone', false)
                ->where('sender_id', '!=', $user->id);

            if ($read) $query->where('created_at', '>', $read->last_read_at);
            if ($deletion) $query->where('created_at', '>', $deletion->cleared_before);

            $totalUnread += $query->count();
        }

        return response()->json(['success' => true, 'unread' => $totalUnread]);
    }

    // Per-group unread breakdown (used by faculty dashboard - each group has its own Chat button)
    public function unreadByGroup(Request $request)
    {
        $user = auth()->user();

        $groupIds = GroupMember::where('user_id', $user->id)->where('status', 'accepted')->pluck('fyp_group_id');
        if ($groupIds->isEmpty()) {
            $groupIds = FypGroup::where('supervisor_id', $user->id)->pluck('id');
        }

        $result = [];
        foreach ($groupIds as $gid) {
            $read = ChatRead::where('user_id', $user->id)->where('group_id', $gid)->first();
            $deletion = ChatDeletion::where('user_id', $user->id)->where('group_id', $gid)->first();

            $query = GroupMessage::where('group_id', $gid)
                ->where('deleted_for_everyone', false)
                ->where('sender_id', '!=', $user->id);

            if ($read) $query->where('created_at', '>', $read->last_read_at);
            if ($deletion) $query->where('created_at', '>', $deletion->cleared_before);

            $result[$gid] = $query->count();
        }

        return response()->json(['success' => true, 'unread_by_group' => $result]);
    }
}
