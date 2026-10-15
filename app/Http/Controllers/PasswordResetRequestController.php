<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetRequest;

class PasswordResetRequestController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No account found with this email address.');
        }

        $existing = PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('success', 'A reset request is already pending for this account. Please contact your Focal Person.');
        }

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'status'  => 'pending',
        ]);

        return back()->with('success', 'Your password reset request has been sent. Please contact your Focal Person to get your new password.');
    }
}
