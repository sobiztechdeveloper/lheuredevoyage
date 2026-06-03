<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHomeBlockRequest;
use App\Http\Requests\Admin\UpdateHomeBlockRequest;
use App\Models\HomeBlock;
use App\Services\HomeBlockAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeBlockController extends Controller
{
    public function __construct(
        protected HomeBlockAdminService $homeBlockAdmin,
    ) {}

    public function index(Request $request): View
    {
        $blocks = HomeBlock::query()
            ->when($request->input('section'), fn ($q, $section) => $q->where('section', $section))
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")
                        ->orWhere('subtitle', 'like', "%{$term}%")
                        ->orWhere('content', 'like', "%{$term}%");
                });
            })
            ->orderBy('section')
            ->orderBy('sort_order')
            ->paginate(25)
            ->withQueryString();

        return view('admin.home-blocks.index', [
            'blocks' => $blocks,
            'sections' => $this->sections(),
            'filterSection' => $request->input('section'),
            'search' => $request->input('q'),
        ]);
    }

    public function create(): View
    {
        return view('admin.home-blocks.create', [
            'block' => new HomeBlock,
            'sections' => $this->sections(),
        ]);
    }

    public function store(StoreHomeBlockRequest $request): RedirectResponse
    {
        HomeBlock::query()->create($this->homeBlockAdmin->prepareFromRequest($request));

        return redirect()->route('admin.home-blocks.index')->with('success', 'Home block created.');
    }

    public function edit(HomeBlock $homeBlock): View
    {
        return view('admin.home-blocks.edit', [
            'block' => $homeBlock,
            'sections' => $this->sections(),
        ]);
    }

    public function update(UpdateHomeBlockRequest $request, HomeBlock $homeBlock): RedirectResponse
    {
        $homeBlock->update($this->homeBlockAdmin->prepareFromRequest($request, $homeBlock));

        return redirect()->route('admin.home-blocks.index')->with('success', 'Home block updated.');
    }

    public function destroy(HomeBlock $homeBlock): RedirectResponse
    {
        $homeBlock->delete();

        return redirect()->route('admin.home-blocks.index')->with('success', 'Home block deleted.');
    }

    /**
     * @return array<string, string>
     */
    private function sections(): array
    {
        return [
            'partner' => 'Partner logo',
            'feature' => 'Feature box',
            'counter' => 'Counter stat',
            'cta' => 'Call to action',
            'choose' => 'Why choose us item',
            'choose_header' => 'Why choose us header',
        ];
    }
}
