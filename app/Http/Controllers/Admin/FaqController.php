<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FaqRequest;
use App\Models\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs.
     *
     * Data contract:
     * - faqs: Collection<Faq> (paginated)
     */
    public function index()
    {
        $faqs = Faq::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ.
     */
    public function create()
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created FAQ.
     */
    public function store(FaqRequest $request)
    {
        Faq::create($request->validated());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ skapad!');
    }

    /**
     * Show the form for editing a FAQ.
     *
     * Data contract:
     * - faq: Faq
     * - tagsString: string (comma-separated)
     */
    public function edit(Faq $faq)
    {
        // Convert tags array to comma-separated string for the form
        $oldTags = old('tags', $faq->tags ?? []);
        $tagsString = is_array($oldTags)
            ? implode(', ', $oldTags)
            : $oldTags;

        return view('admin.faqs.edit', compact('faq', 'tagsString'));
    }

    /**
     * Update a FAQ.
     */
    public function update(FaqRequest $request, Faq $faq)
    {
        $faq->update($request->validated());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ uppdaterad!');
    }

    /**
     * Delete a FAQ.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ raderad!');
    }
}
