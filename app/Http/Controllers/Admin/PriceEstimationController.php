<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceEstimation;
use Illuminate\Http\Request;

class PriceEstimationController extends Controller
{
    /**
     * Display all price estimations.
     *
     * Data contract:
     * - estimations: Collection<PriceEstimation> (paginated, with contactMessage loaded)
     */
    public function index()
    {
        $estimations = PriceEstimation::with('contactMessage')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.estimations.index', compact('estimations'));
    }

    /**
     * Show a specific price estimation.
     *
     * Data contract:
     * - estimation: PriceEstimation (with contactMessage loaded)
     */
    public function show(PriceEstimation $estimation)
    {
        $estimation->load('contactMessage');

        return view('admin.estimations.show', compact('estimation'));
    }

    /**
     * Delete a price estimation.
     */
    public function destroy(PriceEstimation $estimation)
    {
        $estimation->delete();

        return redirect()->route('admin.estimations.index')
            ->with('success', 'Prisestimering raderad!');
    }

    /**
     * Delete multiple price estimations.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:price_estimations,id',
        ]);

        $count = PriceEstimation::whereIn('id', $request->ids)->delete();

        $message = $count === 1
            ? '1 prisestimering raderad!'
            : "{$count} prisertimeringar raderade!";

        return redirect()->route('admin.estimations.index')
            ->with('success', $message);
    }
}
