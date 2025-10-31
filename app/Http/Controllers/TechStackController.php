<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TechStackController extends Controller
{
    /**
     * Display the tech stack visualization page.
     *
     * Data contract:
     * - techData: array [
     *     'nodes' => [['id' => string, 'name' => string, 'count' => int, 'group' => string], ...],
     *     'links' => [['source' => string, 'target' => string, 'value' => int], ...],
     *     'technologies' => [['name' => string, 'count' => int, 'projects' => array], ...]
     *   ]
     */
    public function index(): View
    {
        $projects = Project::published()->get();

        // Extract and count all technologies
        $techCounts = $this->extractTechnologies($projects);

        // Create nodes for D3.js (each unique technology)
        $nodes = $this->createNodes($techCounts);

        // Create links between technologies that appear in same projects
        $links = $this->createLinks($projects, $techCounts);

        // Prepare technology statistics
        $technologies = $this->prepareTechnologyStats($techCounts, $projects);

        $techData = [
            'nodes' => $nodes,
            'links' => $links,
            'technologies' => $technologies,
        ];

        return view('tech-stack', compact('techData'));
    }

    /**
     * Extract all technologies from projects and count occurrences
     */
    private function extractTechnologies(Collection $projects): array
    {
        $techCounts = [];

        foreach ($projects as $project) {
            $technologies = $project->technologies ?? [];

            foreach ($technologies as $tech) {
                $tech = trim($tech);
                if (! empty($tech)) {
                    $techCounts[$tech] = ($techCounts[$tech] ?? 0) + 1;
                }
            }
        }

        // Sort by count descending
        arsort($techCounts);

        return $techCounts;
    }

    /**
     * Create nodes for D3.js visualization
     */
    private function createNodes(array $techCounts): array
    {
        $nodes = [];

        foreach ($techCounts as $tech => $count) {
            $nodes[] = [
                'id' => $tech,
                'name' => $tech,
                'count' => $count,
                'group' => $this->categorizeTechnology($tech),
            ];
        }

        return $nodes;
    }

    /**
     * Create links between technologies that appear together
     */
    private function createLinks(Collection $projects, array $techCounts): array
    {
        $links = [];
        $linkStrengths = [];

        foreach ($projects as $project) {
            $technologies = $project->technologies ?? [];

            // Create links between all pairs of technologies in this project
            for ($i = 0; $i < count($technologies); $i++) {
                for ($j = $i + 1; $j < count($technologies); $j++) {
                    $tech1 = trim($technologies[$i]);
                    $tech2 = trim($technologies[$j]);

                    if (empty($tech1) || empty($tech2)) {
                        continue;
                    }

                    // Create a unique key for this link (sorted to avoid duplicates)
                    $linkKey = $tech1 < $tech2 ? "{$tech1}|{$tech2}" : "{$tech2}|{$tech1}";

                    // Increment link strength
                    $linkStrengths[$linkKey] = ($linkStrengths[$linkKey] ?? 0) + 1;
                }
            }
        }

        // Convert to D3.js link format
        foreach ($linkStrengths as $linkKey => $strength) {
            [$source, $target] = explode('|', $linkKey);
            $links[] = [
                'source' => $source,
                'target' => $target,
                'value' => $strength,
            ];
        }

        return $links;
    }

    /**
     * Prepare technology statistics with project names
     */
    private function prepareTechnologyStats(array $techCounts, Collection $projects): array
    {
        $technologies = [];

        foreach ($techCounts as $tech => $count) {
            $projectsUsingTech = $projects->filter(function ($project) use ($tech) {
                return in_array($tech, $project->technologies ?? []);
            })->pluck('title')->toArray();

            $technologies[] = [
                'name' => $tech,
                'count' => $count,
                'projects' => $projectsUsingTech,
            ];
        }

        return $technologies;
    }

    /**
     * Categorize technology into groups for visualization
     */
    private function categorizeTechnology(string $tech): string
    {
        $tech = strtolower($tech);

        // Frontend
        if (preg_match('/vue|react|alpine|tailwind|bootstrap|css|sass|html|javascript|typescript/', $tech)) {
            return 'frontend';
        }

        // Backend
        if (preg_match('/laravel|php|python|node|express|django|flask|ruby|rails/', $tech)) {
            return 'backend';
        }

        // Database
        if (preg_match('/mysql|postgres|sqlite|mongodb|redis/', $tech)) {
            return 'database';
        }

        // DevOps/Tools
        if (preg_match('/docker|kubernetes|git|ci\/cd|nginx|apache|vite|webpack/', $tech)) {
            return 'devops';
        }

        return 'other';
    }
}
