<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\RegistrationInvitation;
use App\Models\User;

class RegistrationInvitationController extends Controller
{
    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $token = Str::random(32);

        RegistrationInvitation::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        return back()->with('link', url('/register?token=' . $token));
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

        // 创建用户
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // 删除邀请记录
        RegistrationInvitation::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Account created successfully.');
    }
}
