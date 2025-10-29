<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * Data contract:
     * - projectsCount: int
     * - unreadMessages: int
     * - recentProjects: Collection<Project>
     * - recentMessages: Collection<ContactMessage>
     */
    public function index()
    {
        $projectsCount = Project::count();
        $unreadMessages = ContactMessage::unread()->count();
        $recentProjects = Project::orderBy('created_at', 'desc')->take(5)->get();
        $recentMessages = ContactMessage::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'projectsCount',
            'unreadMessages',
            'recentProjects',
            'recentMessages'
        ));
    }
}
