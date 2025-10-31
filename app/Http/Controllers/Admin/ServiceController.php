<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     *
     * Data contract:
     * - services: Collection<Service> (ordered by sort_order)
     */
    public function index()
    {
        $services = Service::ordered()->get();

        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service.
     */
    public function store(ServiceRequest $request)
    {
        $data = $request->validated();

        // Generate slug from title if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        Service::create($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Tjänst skapad!');
    }

    /**
     * Show the form for editing a service.
     *
     * Data contract:
     * - service: Service
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update a service.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $data = $request->validated();

        // Generate slug from title if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $service->update($data);

        return redirect()->route('admin.services.index')
            ->with('success', 'Tjänst uppdaterad!');
    }

    /**
     * Delete a service.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Tjänst raderad!');
    }
}
