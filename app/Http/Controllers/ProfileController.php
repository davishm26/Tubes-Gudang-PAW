<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     * Support untuk demo mode - UI sama seperti real, tapi tetap read-only
     */
    public function edit(Request $request): View
    {
        $isDemo = Session::has('demo_mode') && Session::get('demo_mode');

        if ($isDemo) {
            // Demo mode: get profile from session but pass to same form
            $profileData = Session::get('demo_profile_data', []);
            // Convert array to object for form compatibility
            $user = (object) $profileData;
            return view('profile.edit', [
                'user' => $user,
                'isDemo' => true,
            ]);
        }

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     * Demo mode: read-only (tidak bisa update)
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $isDemo = Session::has('demo_mode') && Session::get('demo_mode');

        if ($isDemo) {
            return Redirect::route('profile.edit')->with('warning', 'Demo mode: Profil tidak dapat diubah dalam mode demo.');
        }

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     * Demo mode: tidak bisa delete
     */
    public function destroy(Request $request): RedirectResponse
    {
        $isDemo = Session::has('demo_mode') && Session::get('demo_mode');

        if ($isDemo) {
            return Redirect::route('profile.edit')->with('warning', 'Demo mode: Tidak dapat menghapus akun dalam mode demo.');
        }

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
}
