<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLegalPageRequest;
use App\Http\Requests\Admin\UpdateLegalPageRequest;
use App\Models\LegalPage;
use App\Services\LegalPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegalPageAdminController extends Controller
{
    public function __construct(
        protected LegalPageService $legalPageService,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', LegalPage::class);

        $pages = LegalPage::query()
            ->when($request->input('q'), function ($query, $term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('title', 'like', "%{$term}%")
                        ->orWhere('slug', 'like', "%{$term}%");
                });
            })
            ->when($request->input('status') === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->input('status') === 'inactive', fn ($q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.legal-pages.index', [
            'pages' => $pages,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', LegalPage::class);

        return view('admin.legal-pages.create', [
            'page' => new LegalPage(['is_active' => true, 'sort_order' => LegalPage::query()->max('sort_order') + 1]),
        ]);
    }

    public function store(StoreLegalPageRequest $request): RedirectResponse
    {
        $this->authorize('create', LegalPage::class);

        $data = $this->legalPageService->prepareForStorage(
            $this->validatedData($request),
            $request->boolean('is_active', true),
        );

        if (empty($data['slug'])) {
            $data['slug'] = LegalPage::generateSlug($data['title']);
        } else {
            $data['slug'] = \Illuminate\Support\Str::slug($data['slug']);
        }

        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        LegalPage::query()->create($data);

        return redirect()->route('admin.legal-pages.index')->with('success', 'Legal page created.');
    }

    public function edit(LegalPage $legal_page): View
    {
        $this->authorize('update', $legal_page);

        return view('admin.legal-pages.edit', ['page' => $legal_page]);
    }

    public function update(UpdateLegalPageRequest $request, LegalPage $legal_page): RedirectResponse
    {
        $this->authorize('update', $legal_page);

        $data = $this->legalPageService->prepareForStorage(
            $this->validatedData($request),
            $request->boolean('is_active', true),
        );

        $data['updated_by'] = $request->user()->id;

        if ($legal_page->is_active && ! $data['is_active']) {
            $data['published_at'] = $legal_page->published_at;
        } elseif ($data['is_active'] && ! $legal_page->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $legal_page->update($data);

        return redirect()->route('admin.legal-pages.index')->with('success', 'Legal page updated.');
    }

    public function destroy(LegalPage $legal_page): RedirectResponse
    {
        $this->authorize('delete', $legal_page);

        $legal_page->delete();

        return redirect()->route('admin.legal-pages.index')->with('success', 'Legal page moved to trash.');
    }

    public function trashed(Request $request): View
    {
        $this->authorize('viewAny', LegalPage::class);

        $pages = LegalPage::onlyTrashed()
            ->when($request->input('q'), fn ($q, $term) => $q->where('title', 'like', "%{$term}%")->orWhere('slug', 'like', "%{$term}%"))
            ->latest('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.legal-pages.trashed', [
            'pages' => $pages,
            'search' => $request->input('q'),
        ]);
    }

    public function restore(int $id): RedirectResponse
    {
        $legal_page = LegalPage::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $legal_page);

        $legal_page->restore();

        return redirect()->route('admin.legal-pages.trashed')->with('success', 'Legal page restored.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validatedData(StoreLegalPageRequest|UpdateLegalPageRequest $request): array
    {
        return $request->validated();
    }
}
