<?php

namespace App\Http\Controllers;

use App\Services\LegalPageService;
use Illuminate\View\View;

class LegalPageController extends Controller
{
    public function __construct(
        protected LegalPageService $legalPageService,
    ) {}

    public function show(string $slug): View
    {
        $page = $this->legalPageService->findActiveBySlug($slug);

        abort_unless($page, 404);

        $companyInfo = $slug === 'company-information'
            ? $this->legalPageService->companyInformationData()
            : null;

        return view('pages.publicView.legal.show', [
            'page' => $page,
            'companyInfo' => $companyInfo,
        ]);
    }
}
