<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * Data contract:
     * - projectsCount: int
     * - servicesCount: int
     * - unreadMessages: int
     * - recentProjects: Collection<Project>
     * - recentMessages: Collection<ContactMessage>
     */
    public function index()
    {
        $projectsCount = Project::count();
        $servicesCount = Service::count();
        $unreadMessages = ContactMessage::unread()->count();
        $recentProjects = Project::orderBy('created_at', 'desc')->take(5)->get();
        $recentMessages = ContactMessage::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'projectsCount',
            'servicesCount',
            'unreadMessages',
            'recentProjects',
            'recentMessages'
        ));
    }
}
