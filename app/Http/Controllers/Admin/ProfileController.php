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

        // Create profile if it doesn't exist
        if (!$profile) {
            $profile = Profile::create([
                'github' => $request->input('github'),
                'linkedin' => $request->input('linkedin'),
                'twitter' => $request->input('twitter'),
            ]);
        } else {
            // Update social links
            $profile->update([
                'github' => $request->input('github'),
                'linkedin' => $request->input('linkedin'),
                'twitter' => $request->input('twitter'),
            ]);
        }

        // Hantera borttagning av avatar
        if ($request->input('remove_avatar')) {
            $profile->clearMediaCollection('avatar');
        }
        // Hantera avatar upload med optimering
        elseif ($request->hasFile('avatar')) {
            $profile->clearMediaCollection('avatar');
            $profile->addMediaFromRequest('avatar')
                ->toMediaCollection('avatar');
        }

        // Hantera borttagning av work_image
        if ($request->input('remove_hero_image')) {
            $profile->clearMediaCollection('work_image');
        }
        // Hantera work_image upload med optimering
        elseif ($request->hasFile('hero_image')) {
            $profile->clearMediaCollection('work_image');
            $profile->addMediaFromRequest('hero_image')
                ->toMediaCollection('work_image');
        }

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil uppdaterad!');
    }
}
