<?php

namespace App\Repositories;

use App\Repositories\Traits\SimpleCRUD;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class ShopRepository
{
    use SimpleCRUD;

    private string $model = Shop::class;

    // Add any custom repository methods here
    public function getNearbyShops($latitude, $longitude){
        $threshold = 2; // 2 km threshold

        $nearby_shops = DB::table(DB::raw("(SELECT *,
        (6371 * acos(
            cos(radians($latitude)) * cos(radians(latitude))
            * cos(radians(longitude) - radians($longitude))
            + sin(radians($latitude)) * sin(radians(latitude))
        )) AS distance FROM shops) as subquery"))
            ->where("distance", "<=", $threshold)
            ->orderBy("distance")
            ->get();

        return $nearby_shops;
    }
}
