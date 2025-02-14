<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Http\Resources\ShopResource;
use App\Http\Resources\IdResource;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        private ShopRepository $shopRepository
    ) {}

    public function index()
    {
        Gate::authorize('viewAny', Shop::class);
        $shops = $this->shopRepository->getAll();
        return ShopResource::collection($shops);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateShopRequest $request)
    {
        Gate::authorize('create', Shop::class);
        $shop = $this->shopRepository->create([
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'phone' => $request->get('shop_phone'),
            'description' => $request->get('description'),
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
            'image_uri' => $request->get('image_uri'),
            'user_id' => auth()->id(),
        ]);
        return IdResource::make($shop);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        Gate::authorize('view', Shop::class);
        return ShopResource::make($shop);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {
        Gate::authorize('update', $shop);
        $shop->update([
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'phone' => $request->get('shop_phone'),
            'description' => $request->get('description'),
            'image_uri' => $request->get('image_uri'),
            'is_open' => $request->get('is_open'),
            'approve_status' => $request->get('approve_status'),
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude'),
        ]);
        return IdResource::make($shop);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        Gate::authorize('delete', $shop);
        $shop->delete();
    }
    
}
