<?php

namespace App\Repositories;

use App\Repositories\Traits\SimpleCRUD;
use App\Models\Queue;
use Illuminate\Support\Facades\DB;

class QueueRepository
{
    use SimpleCRUD;

    private string $model = Queue::class;


    public function getQueueByShopID(int $shopID, int $queueID){
        return $this->model::where('shop_id', $shopID)->where('id', $queueID)->first();
    }

    public function getAllByShopID(int $shopID){
        return $this->model::where('shop_id', $shopID)->get();
    }

    public function increaseQueueCounter(int $queueID){
        $this->model::where('id', $queueID)->increment('queue_counter');
    }

    public function checkQueueIsInShop(int $shop_ID,int $queueID){
        $exist = $this->model::where('shop_id', $shop_ID)->where('id', $queueID)->get();
        if ($exist->isEmpty()){
            return false;
        }
        return true;
    }

}
