<?php

namespace App\Repositories;

use App\Repositories\Traits\SimpleCRUD;
use App\Models\Reminder;

class ReminderRepository
{
    use SimpleCRUD;

    private string $model = Reminder::class;


    public function getAllRemindersByShopId(int $shopId){
        return $this->model::where('shop_id', $shopId)->where('status','pending')->orderBy('due_date', 'asc')->get();
    }

    public function markAsDone(int $id){
        return $this->model::where('id', $id)->update(['status' => 'completed']);
    }
}
