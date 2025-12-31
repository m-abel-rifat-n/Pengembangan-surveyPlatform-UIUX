<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\UserSelectCategory;
use App\Models\SurveyResponses;
use App\Models\User;
use App\Models\Certificate;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(auth()->user()->id);
        $categories = Category::all();
        $userPrefs = UserSelectCategory::where('user_id', auth()->user()->id)->get();
        $filledOutSurvey = SurveyResponses::with(['survey', 'user'])->where('user_id', $user->id)->latest()->paginate(8);

        return inertia('Account/Profile/Profile', [
            'user' => auth()->user(),
            'categories' => $categories,
            'userPrefs' => $userPrefs,
            'filledOutSurvey' => $filledOutSurvey,
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $categories = Category::all();
        $userPrefs = UserSelectCategory::where('user_id', $user->id)->get();

        return inertia('Account/Profile/Edit', [
            'user' => $user,
            'categories' => $categories,
            'userPrefs' => $userPrefs
        ]);
    }

    public function update(Request $request, User $user, UserSelectCategory $userPref)
    {
        $user = User::findOrFail(auth()->user()->id);

        $this->validate($request, [
            'first_name'      => 'required',
            'surname'         => 'required',
            'email'    => 'required|unique:users,email,' . $user->id,
            'birth_date'     => 'nullable|date',
            'gender'     => 'required',
            'profession'     => 'required',
            'educational_background'     => 'required',
            'userPrefsData'     => 'required',
            'password' =>
            [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!\Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
        ]);

        $user->update([
            'first_name'      => $request->first_name,
            'surname'         => $request->surname,
            'email'     => $request->email,
            'birth_date'     => $request->birth_date,
            'gender'     => $request->gender,
            'profession'     => $request->profession,
            'educational_background'     => $request->educational_background,
        ]);

        if ($request->has('userPrefsData')) {
            $userPrefsData = $request->userPrefsData;
            $userPref->where('user_id', $user->id)->delete();

            foreach ($userPrefsData as $category_id) {
                $userPref->create(['category_id' => $category_id, 'user_id' => $user->id]);
            }
        }

        return redirect()->route('account.profile.index');
    }

    public function certificate()
    {
        $certificates = Certificate::where('user_id', auth()->user()->id)->latest()->paginate(8);

        return inertia('Account/Profile/Certificate', [
            'user' => auth()->user(),
            'certificates' => $certificates
        ]);
    }

    public function uploadCertificate(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'files.*' => 'required|file|mimes:pdf|max:2048',
            'files' => 'required|array|min:1'
        ], [
            'files' => 'Please select at least one valid PDF certificate to upload.'
        ]);

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $certificate = $file->hashName();
            $file->storeAs('public/file/certificates', $certificate);
            Certificate::create([
                'user_id' => $request->user_id,
                'original_certificate' => $originalName,
                'certificate' => $certificate,
                'status' => 'pending',
            ]);
        }

        redirect()->route('account.profile.certificate');
    }

    public function password(User $user)
    {
        $user = User::findOrFail(auth()->user()->id);
        return inertia('Account/Profile/Password', [
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request, User $user)
    {
        $user = User::findOrFail(auth()->user()->id);

        $this->validate($request, [
            'email' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== auth()->user()->email) {
                        $fail('The current email is incorrect.');
                    }
                },
            ],
            'password' => [
                'required',
                function ($attribute, $value, $fail) use ($user) {
                    if (!\Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password' => 'required',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'new_password_confirmation.same' => 'The new password confirmation does not match.',
        ]);

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return redirect()->route('account.profile.index');
    }
}
