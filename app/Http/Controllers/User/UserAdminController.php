<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RegistrationInvitation;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function adminIndex()
    {
        $users = User::paginate(10);
        $pendingInvitations = RegistrationInvitation::all();

        return view('admin.users.index', compact('users', 'pendingInvitations'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}