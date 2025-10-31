<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * Data contract:
     * - profile: Profile (name, title, bio, avatar, hero_image, email, phone, github, linkedin, twitter)
     * - services: Collection<Service> (id, slug, title, description, icon, features, sort_order)
     * - projects: Collection<Project> (id, slug, title, summary, cover_image, technologies, featured)
     */
    public function index()
    {
        $profile = Profile::current();

        $services = Service::active()
            ->ordered()
            ->get();

        $projects = Project::published()
            ->featured()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('profile', 'services', 'projects'));
    }
}
