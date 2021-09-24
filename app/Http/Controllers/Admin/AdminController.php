<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DefaultMenu;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $menuItems = DefaultMenu::all();
        return view('Admin/admin', compact('users', 'menuItems'));
    }

    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->is_admin) {
            return response()->json('permission-denied', 403);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'max:90'],
            'id_role' => ['required', 'int', 'exists:roles,id_role'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $userData = User::query()->findOrFail($id);

        $userData->name = $request->input('name');
        $userData->id_role = $request->input('id_role');
        $userData->first_name = $request->input('first_name');
        $userData->last_name = $request->input('last_name');
        $userData->is_active = $request->input('is_active');

        $userData->save();

        return response()->json('OK', 200);
    }

    public function updateMenu(Request $request, $id) {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->is_admin) {
            return response()->json('permission-denied', 403);
        }

        $this->validate($request, [
            'name' => ['required', 'string', 'max:90'],
            'icon' => ['required', 'string'],
            'type' => ['required', 'string', 'in:default,admin'],
            'route' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $menuItem = DefaultMenu::query()->findOrFail($id);

        $menuItem->name = $request->input('name');
        $menuItem->icon = $request->input('icon');
        $menuItem->type = $request->input('type');
        $menuItem->route = $request->input('route');
        $menuItem->is_active = $request->input('is_active');

        $menuItem->save();

        return response()->json('OK');
    }

    public function destroy($id)
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->is_admin) {
            return response()->json('permission-denied', 403);
        }

        $deletableUser = User::query()->findOrFail($id);
        $deletableUser->delete();

        return response()->json('OK', 200);
    }

    public function destroyMenu($id)
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->is_admin) {
            return response()->json('permission-denied', 403);
        }

        $menuItem = DefaultMenu::query()->findOrFail($id);
        $menuItem->delete();

        return response()->json('OK');
    }

    public function loadUsers()
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->is_admin) {
            return response()->json('permission-denied', 403);
        }

        return response()->json(['data' => User::all()]);
    }

    public function loadMenuItems()
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user->is_admin) {
            return response()->json('permission-denied', 403);
        }

        return response()->json(['data' => DefaultMenu::all()]);
    }
}
