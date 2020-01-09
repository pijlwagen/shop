<?php


namespace App\Http\Controllers\Admin\User;


use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\UserRole;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with(['roles'])->paginate($request->get('max', 25));
        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    public function edit($id, Request $request)
    {
        $user = User::with(['roles'])->findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|required',
        ]);
        $user = User::findOrFail($id);
        UserRole::where('user_id', $id)->delete();

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')) ?? $user->password,
        ]);

        foreach ($request->input('roles') ?? [] as $role) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role
            ]);
        }

        return redirect()->route('admin.users.edit', $user->id)->with('success', "Successfully updated user {$user->name}");
    }

    public function block($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update([
            'blocked' => $user->blocked ? false : true
        ]);
        return response()->json(['blocked' => $user->blocked ? true : false]);
    }

    public function delete($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['deleted' => true]);
    }
}
