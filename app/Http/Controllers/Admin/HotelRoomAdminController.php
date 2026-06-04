<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Services\CatalogImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HotelRoomAdminController extends Controller
{
    public function __construct(
        protected CatalogImageUploader $uploader,
    ) {}

    public function index(Hotel $hotel): View
    {
        $rooms = $hotel->rooms()->orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('admin.hotels.rooms.index', [
            'hotel' => $hotel,
            'rooms' => $rooms,
        ]);
    }

    public function create(Hotel $hotel): View
    {
        return view('admin.hotels.rooms.form', [
            'hotel' => $hotel,
            'room' => new HotelRoom(['hotel_id' => $hotel->id, 'is_active' => true, 'currency' => 'USD']),
        ]);
    }

    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $data = $this->validated($request);
        $data['hotel_id'] = $hotel->id;
        $this->applyImages($request, $data);

        HotelRoom::query()->create($data);

        return redirect()->route('admin.hotels.rooms.index', $hotel)
            ->with('success', 'Room created.');
    }

    public function edit(Hotel $hotel, HotelRoom $room): View
    {
        abort_unless($room->hotel_id === $hotel->id, 404);

        return view('admin.hotels.rooms.form', [
            'hotel' => $hotel,
            'room' => $room,
        ]);
    }

    public function update(Request $request, Hotel $hotel, HotelRoom $room): RedirectResponse
    {
        abort_unless($room->hotel_id === $hotel->id, 404);

        $data = $this->validated($request);
        $this->applyImages($request, $data, $room);
        $room->update($data);

        return redirect()->route('admin.hotels.rooms.index', $hotel)
            ->with('success', 'Room updated.');
    }

    public function destroy(Hotel $hotel, HotelRoom $room): RedirectResponse
    {
        abort_unless($room->hotel_id === $hotel->id, 404);
        $room->delete();

        return redirect()->route('admin.hotels.rooms.index', $hotel)
            ->with('success', 'Room deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'room_type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'room_size' => ['nullable', 'string', 'max:50'],
            'max_adults' => ['required', 'integer', 'min:1', 'max:20'],
            'max_children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'bed_type' => ['nullable', 'string', 'max:100'],
            'meal_plan' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
            'features' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'featured_image_path' => ['nullable', 'string', 'max:255'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        if (! empty($data['features'])) {
            $data['features'] = array_values(array_filter(array_map('trim', explode(',', $data['features']))));
        } else {
            $data['features'] = null;
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function applyImages(Request $request, array &$data, ?HotelRoom $room = null): void
    {
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploader->uploadFeatured(
                $request->file('featured_image'),
                'hotel-rooms',
                $room?->featured_image,
            );
        } elseif ($request->filled('featured_image_path')) {
            $data['featured_image'] = $request->input('featured_image_path');
        }

        unset($data['featured_image_path']);
    }
}
