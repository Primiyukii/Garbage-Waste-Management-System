<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBarangayRequest;
use App\Http\Requests\Admin\StoreCityRequest;
use App\Http\Requests\Admin\StoreProvinceRequest;
use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $this->authorize('manage-locations');

        $provinces = Province::withCount('cities')->orderBy('name')->get();
        $cities = City::with('province')->withCount('barangays')->orderBy('name')->get();
        $barangays = Barangay::with('city.province')->orderBy('name')->get();

        return view('admin.locations.index', compact('provinces', 'cities', 'barangays'));
    }

    public function storeProvince(StoreProvinceRequest $request): RedirectResponse
    {
        $this->authorize('manage-locations');

        Province::create(['name' => $request->validated('province_name')]);

        return back()->with('success', 'Province added.');
    }

    public function storeCity(StoreCityRequest $request): RedirectResponse
    {
        $this->authorize('manage-locations');

        City::create([
            'province_id' => $request->validated('province_id'),
            'name' => $request->validated('city_name'),
        ]);

        return back()->with('success', 'City added.');
    }

    public function storeBarangay(StoreBarangayRequest $request): RedirectResponse
    {
        $this->authorize('manage-locations');

        Barangay::create([
            'city_id' => $request->validated('city_id'),
            'name' => $request->validated('barangay_name'),
        ]);

        return back()->with('success', 'Barangay added.');
    }

    public function destroyProvince(Province $province): RedirectResponse
    {
        $this->authorize('manage-locations');

        $province->delete();

        return back()->with('success', 'Province removed.');
    }

    public function destroyCity(City $city): RedirectResponse
    {
        $this->authorize('manage-locations');

        $city->delete();

        return back()->with('success', 'City removed.');
    }

    public function destroyBarangay(Barangay $barangay): RedirectResponse
    {
        $this->authorize('manage-locations');

        $barangay->delete();

        return back()->with('success', 'Barangay removed.');
    }
}
