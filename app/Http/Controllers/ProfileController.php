<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {

        $user = $request->user();
        return view('profile.edit',compact('user'));
        // return view('profile.edit', [
        //     'user' => $request->user(),
        // ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    public function reset_password(Request $request) {
        $currentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;

        $updata_user = User::where('id', auth()->user()->id)->first();
        if ($newPassword == $confirmPassword) {
            if ($currentPassword && $currentPassword != null) {
                if (Hash::check($currentPassword, $updata_user->password)) {
                    $updata_user->password = Hash::make($newPassword);
                    $updata_user->save();
                    return response()->json('Password updated successfully');
                } else {
                    return response()->json('Current password is incorrect.');
                }
            } else {
                return response()->json('Enter the Current Password.');
            }
        } else {
            return response()->json('Confirm Password is not a matched.');
        }
       
    }

}
