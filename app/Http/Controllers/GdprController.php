<?php

namespace App\Http\Controllers;

use App\Services\GdprDataExportService;
use App\Services\GdprDataDeletionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class GdprController extends Controller
{
    public function __construct(
        private GdprDataExportService $exportService,
        private GdprDataDeletionService $deletionService
    ) {}

    /**
     * Visa Privacy Policy
     *
     * @return View
     */
    public function privacy(): View
    {
        return view('gdpr.privacy');
    }

    /**
     * Visa Cookie Policy
     *
     * @return View
     */
    public function cookies(): View
    {
        return view('gdpr.cookies');
    }

    /**
     * GDPR Showcase page - demo av alla GDPR-funktioner
     *
     * @return View
     */
    public function showcase(): View
    {
        return view('gdpr.showcase');
    }

    /**
     * SHOWCASE: Export user data demo
     */
    public function exportDemo(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $data = $this->exportService->exportUserData($request->email);
        $summary = $this->exportService->getDataSummary($request->email);

        return response()->json([
            'success' => true,
            'message' => 'Data export genererad (DEMO)',
            'summary' => $summary,
            'data' => $data,
            'download_url' => null, // I riktig implementation skulle detta vara en nedladdningslänk
        ]);
    }

    /**
     * SHOWCASE: Delete request demo
     */
    public function deleteDemo(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $summary = $this->deletionService->getPreDeletionSummary($request->email);
        $gdprRequest = $this->deletionService->createDeletionRequest(
            $request->email,
            $request->ip()
        );

        $emailPreview = $this->deletionService->getDeletionConfirmationEmailPreview(
            $gdprRequest->token,
            $request->email
        );

        return response()->json([
            'success' => true,
            'message' => 'Deletion request skapad (DEMO - ingen email skickad)',
            'summary' => $summary,
            'email_preview' => $emailPreview,
            'confirmation_url' => url("/gdpr/confirm-deletion/{$gdprRequest->token}"),
        ]);
    }

    /**
     * SHOWCASE: Visa deletion confirmation page
     */
    public function confirmDeletion(string $token): View
    {
        $request = \App\Models\GdprDataRequest::findByToken($token);

        if (!$request) {
            abort(404, 'Invalid or expired deletion request');
        }

        $summary = $this->deletionService->getPreDeletionSummary($request->email);

        return view('gdpr.confirm-deletion', [
            'request' => $request,
            'summary' => $summary,
        ]);
    }

    /**
     * SHOWCASE: Processa deletion
     */
    public function processDeletion(Request $request, string $token): JsonResponse
    {
        $request->validate([
            'method' => 'required|in:anonymize,delete',
        ]);

        try {
            $result = $this->deletionService->processDeletionRequest(
                $token,
                $request->method === 'delete'
            );

            return response()->json([
                'success' => true,
                'message' => 'Dina personuppgifter har bearbetats enligt GDPR',
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
