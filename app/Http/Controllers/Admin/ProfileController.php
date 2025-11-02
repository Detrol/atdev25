<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

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
        $profile = Profile::current() ?? new Profile;

        $data = $request->validated();

        // Hantera borttagning av avatar
        if ($request->input('remove_avatar')) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $data['avatar'] = null;
        }
        // Hantera avatar upload
        elseif ($request->hasFile('avatar')) {
            // Ta bort gammal bild om den finns
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('profiles', 'public');
        }

        // Hantera borttagning av hero_image
        if ($request->input('remove_hero_image')) {
            if ($profile->hero_image) {
                Storage::disk('public')->delete($profile->hero_image);
            }
            $data['hero_image'] = null;
        }
        // Hantera hero_image upload
        elseif ($request->hasFile('hero_image')) {
            // Ta bort gammal bild om den finns
            if ($profile->hero_image) {
                Storage::disk('public')->delete($profile->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')->store('profiles', 'public');
        }

        if ($profile->exists) {
            $profile->update($data);
        } else {
            Profile::create($data);
        }

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil uppdaterad!');
    }
}
