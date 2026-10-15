<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper {
    public static function send($userId, $title, $message, $type = 'info', $link = null) {
        Notification::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'link'    => $link,
            'is_read' => false,
        ]);
    }

    public static function sendToRole($role, $title, $message, $type = 'info', $link = null) {
        $users = \App\Models\User::where('role', $role)->get();
        foreach ($users as $user) {
            self::send($user->id, $title, $message, $type, $link);
        }
    }
}
