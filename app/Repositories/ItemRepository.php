<?php

namespace App\Repositories;

use App\Repositories\Traits\SimpleCRUD;
use App\Models\Item;

class ItemRepository
{
    use SimpleCRUD;

    private string $model = Item::class;

    public function getAllItemByShopID(int $id){
        return $this->model::where('shop_id', $id)->get();
    }

    public function getItemByItemID(int $shopID, int $id){
        return $this->model::where('shop_id', $shopID)->where('id', $id)->first();
    }
}
