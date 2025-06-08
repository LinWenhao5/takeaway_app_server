<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\RegistrationInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationInvitationMail;

class RegistrationInvitationController extends Controller
{
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
        ]);

        $token = Str::random(32);

        RegistrationInvitation::updateOrCreate(
            ['email' => $request->email],
            ['token' => $token, 'role' => $request->role, 'created_at' => now()] 
        );

        $link = url('/register?token=' . $token);

        try {
            Mail::to($request->email)->queue(new RegistrationInvitationMail($link, $request->role));

            return back()->with('success', 'Invitation email sent successfully to ' . $request->email . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send invitation email. Please try again.');
        }
    }

    public function cancel(RegistrationInvitation $invitation)
    {
        try {
            $invitation->delete();

            return back()->with('success', 'Invitation for ' . $invitation->email . ' has been successfully canceled.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel the invitation. Please try again.');
        }
    }

    public function register(Request $request)
    {
        $invitation = RegistrationInvitation::where('token', $request->token)->first();

        if (!$invitation) {
            abort(403, 'Invalid or expired invitation link.');
        }

        return view('auth.register', ['email' => $invitation->email]);
    }


    public function completeRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        $invitation = RegistrationInvitation::where('email', $request->email)->first();

        if (!$invitation) {
            return redirect()->route('login')->with('error', 'Invalid or expired invitation link.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($invitation->role);

        RegistrationInvitation::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Account created successfully.');
    }
}
