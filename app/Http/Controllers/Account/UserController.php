<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\UserSelectCategory;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $searchTerm = request()->input('q', '');
        $query = User::with('roles')->orderBy('id');

        if (!empty($searchTerm)) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('surname', 'like', '%' . $searchTerm . '%');
            })->orWhereHas('roles', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        $users = $query->paginate(20);
        $users->appends(['q' => $searchTerm]);

        return inertia('Account/Users/Users', [
            'users' => $users,
        ]);
    }


    public function create()
    {
        $roles = Role::all();
        $categories = Category::all();

        return inertia('Account/Users/Create', [
            'roles' => $roles,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request, UserSelectCategory $userPref)
    {
        $this->validate($request, [
            'first_name'      => 'required',
            'surname'         => 'required',
            'email'     => 'required|email|unique:users',
            'birth_date'     => 'required|date',
            'gender'     => 'required',
            'profession'     => 'required',
            'educational_background'     => 'required',
            'roles' => 'required',
            'user_prefs' => 'required',
            'password'  => 'required|confirmed',
        ]);

        $user = User::create([
            'first_name'      => $request->first_name,
            'surname'         => $request->surname,
            'email'     => $request->email,
            'birth_date'     => $request->birth_date,
            'gender'     => $request->gender,
            'profession'     => $request->profession,
            'educational_background'     => $request->educational_background,
            'password'  => bcrypt($request->password)
        ]);
        $user->assignRole($request->roles);

        if ($request->has('user_prefs')) {
            $userPrefsData = $request->user_prefs;

            $userPref->where('user_id', $user->id)->delete();

            foreach ($userPrefsData as $category_id) {
                $userPref->create(['category_id' => $category_id, 'user_id' => $user->id]);
            }
        }
        return redirect()->route('account.users.index');
    }

    public function edit($id)
    {
        if (auth()->user()->cannot('users.index.full')) {
            abort(403, "Editing the user is not allowed.");
        }

        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        $categories = Category::all();
        $userPrefs = UserSelectCategory::where('user_id', $id)->get();

        return inertia('Account/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
            'categories' => $categories,
            'userPrefs' => $userPrefs
        ]);
    }

    public function update(Request $request, User $user, UserSelectCategory $userPref)
    {
        $this->validate($request, [
            'first_name'      => 'required',
            'surname'         => 'required',
            'email'    => 'required|unique:users,email,' . $user->id,
            'birth_date'     => 'nullable|date',
            'gender'     => 'required',
            'profession'     => 'required',
            'educational_background'     => 'required',
            'password' => 'nullable|confirmed',
            'roles' => 'required',
            'user_prefs' => 'required',
        ]);

        if ($request->password != '') {
            $user->update([
                'first_name'      => $request->first_name,
                'surname'         => $request->surname,
                'email'     => $request->email,
                'birth_date'     => $request->birth_date,
                'gender'     => $request->gender,
                'profession'     => $request->profession,
                'educational_background'     => $request->educational_background,
                'password'  => bcrypt($request->password)
            ]);
        } else if ($request->password == '') {
            $user->update([
                'first_name'      => $request->first_name,
                'surname'         => $request->surname,
                'email'     => $request->email,
                'birth_date'     => $request->birth_date,
                'gender'     => $request->gender,
                'profession'     => $request->profession,
                'educational_background'     => $request->educational_background,
            ]);
        }

        $user->syncRoles($request->roles);

        if ($request->has('user_prefs')) {
            $userPrefsData = $request->user_prefs;
            $userPref->where('user_id', $user->id)->delete();

            foreach ($userPrefsData as $category_id) {
                $userPref->create(['category_id' => $category_id, 'user_id' => $user->id]);
            }
        }

        return redirect()->route('account.users.index');
    }

    public function destroy($id)
    {
        if ($id == 1) {
            abort(403, "The system will not allow you to delete this user. The user cannot be deleted.");
        }

        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('account.users.index');
    }
}
