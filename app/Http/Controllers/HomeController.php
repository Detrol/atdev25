<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * Data contract:
     * - profile: Profile (name, title, bio, avatar, hero_image, email, phone, github, linkedin, twitter)
     * - projects: Collection<Project> (id, slug, title, summary, cover_image, technologies, featured)
     */
    public function index()
    {
        $profile = Profile::current();
        $projects = Project::published()
            ->featured()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('profile', 'projects'));
    }
}
