<?php
namespace App\Features\User\Controllers;

use App\Http\Controllers\Controller;
use App\Features\User\Models\RegistrationInvitation;    
use App\Features\User\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function adminIndex()
    {
        $users = User::paginate(10);
        $pendingInvitations = RegistrationInvitation::all();

        return view('user::index', compact('users', 'pendingInvitations'));
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