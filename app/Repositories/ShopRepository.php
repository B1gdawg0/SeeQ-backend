<?php

namespace App\Repositories;

use App\Repositories\Traits\SimpleCRUD;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class ShopRepository
{
    use SimpleCRUD;

    private string $model = Shop::class;

    public function getByEmail(string $email) {
        return $this->model::where('email', $email)->first();
    }

    public function getByName(string $name) {
        return $this->model::where('name', 'LIKE' ,"%$name%")->paginate(6);
    }

    public function filter(array $array) {
        $query = $this->model::query()->withTrashed()->orderByDesc('updated_at');

        if (isset($array['name'])) {
            $query->where('name', 'LIKE', "%$array[name]%");
        }

        if (isset($array['status'])) {
            if ($array['status'] == 'confirm') {
                $query->whereNotNull('email_verified_at')->whereNull('deleted_at');
            }
            if ($array['status'] == 'ban') {
                $query->whereNotNull('email_verified_at')->whereNotNull('deleted_at');
            }
            if ($array['status'] == 'pending') {
                $query->whereNull('email_verified_at');
            }
        }

        return $query->paginate(6);

    }

    public function getAllShopWithTrashed(){
        return (new $this->model)->withTrashed()->get();
    }

    public function getByIdWithTrashed(string $id)
    {
        return $this->model::withTrashed()->findOrFail($id);
    }

    public function getNearbyShops($latitude, $longitude)
    {
        $threshold = 2; // 2 km threshold

        return Shop::select('*',
            DB::raw("(6371 * acos(
            cos(radians($latitude)) * cos(radians(latitude))
            * cos(radians(longitude) - radians($longitude))
            + sin(radians($latitude)) * sin(radians(latitude))
        )) AS distance"))
            ->whereRaw("(6371 * acos(
            cos(radians($latitude)) * cos(radians(latitude))
            * cos(radians(longitude) - radians($longitude))
            + sin(radians($latitude)) * sin(radians(latitude))
        )) <= $threshold")
            ->orderBy('distance')
            ->get();
    }}
