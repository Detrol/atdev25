<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * Data contract:
     * - profile: Profile
     */
    public function edit()
    {
        $profile = Profile::current() ?? new Profile;

        return view('admin.profile.edit', compact('profile'));
    }

    /**
     * Update the profile.
     */
    public function update(ProfileRequest $request)
    {
        $profile = Profile::current();

        if ($profile) {
            $profile->update($request->validated());
        } else {
            Profile::create($request->validated());
        }

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil uppdaterad!');
    }
}
