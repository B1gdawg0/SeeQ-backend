<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserQueueRepository
{

    public function checkQueueNumberAndQueueInfo(int $user_id, int $queueID){
        $record = DB::table('users_queues')
            ->join('queues', 'users_queues.queue_id', '=', 'queues.id') // เชื่อมกับตาราง queues
            ->join('shops', 'queues.shop_id', '=', 'shops.id') // เชื่อมกับตาราง shops
            ->where('users_queues.user_id', $user_id)
            ->where('users_queues.queue_id', $queueID)
            ->where('users_queues.status', "waiting")
            ->select([
                'users_queues.*',
                'queues.name as queue_name',
                'queues.tag as queue_tag',
                'shops.name as shop_name',
                'shops.description as shop_description',
                'shops.address as shop_address',
            ])
            ->first();

        return $record;
    }

    public function getCompleteQueueInfo(int $user_id, int $queueID){
        $record = DB::table('users_queues')
            ->join('queues', 'users_queues.queue_id', '=', 'queues.id') // เชื่อมกับตาราง queues
            ->join('shops', 'queues.shop_id', '=', 'shops.id') // เชื่อมกับตาราง shops
            ->where('users_queues.user_id', $user_id)
            ->where('users_queues.queue_id', $queueID)
            ->where('users_queues.status', "completed")
            ->select([
                'users_queues.*',
                'queues.name as queue_name',
                'queues.tag as queue_tag',
                'shops.name as shop_name',
                'shops.description as shop_description',
            ])
            ->first();

        return $record;
    }

    public function create(array $attributes){
        $now = now();
        $attributes["created_at"] = $now;
        $attributes["updated_at"] = $now;
        return DB::table('users_queues')->insertGetId($attributes);
    }

    public function checkUserAlreadyJoinQueue(int $user_id, int $queueID){
        $exist =  DB::table('users_queues')->where('user_id', $user_id)->where('queue_id', $queueID)->where("status", "waiting")->first();
        return $exist;
    }

    public function getAllQueueReservedComplete(int $user_id){
        $queues = DB::table('users_queues')
            ->join('queues', 'users_queues.queue_id', '=', 'queues.id') // เชื่อมกับตาราง queues
            ->join('shops', 'queues.shop_id', '=', 'shops.id') // เชื่อมกับตาราง shops
            ->where('users_queues.user_id', $user_id)
            ->where("users_queues.status", "completed")
            ->select([
                'users_queues.queue_id',
                'users_queues.created_at',
                'shops.name as shop_name',
                'shops.image_url as shop_image_url'
            ])
            ->get();
        return $queues;
    }

    public function getAllQueueReservedWaiting(int $user_id){
        $queues = DB::table('users_queues')
            ->join('queues', 'users_queues.queue_id', '=', 'queues.id') // เชื่อมกับตาราง queues
            ->join('shops', 'queues.shop_id', '=', 'shops.id') // เชื่อมกับตาราง shops
            ->where('users_queues.user_id', $user_id)
            ->where("users_queues.status", "waiting")
            ->select([
                'users_queues.queue_id',
                'users_queues.status',
                'shops.name as shop_name',
                'shops.image_url as shop_image_url'
            ])
            ->get();
        return $queues;
    }

    public function updateStatusToCancel(int $user_id, int $queueID, string $queue_number){
        return DB::table('users_queues')
            ->where('user_id', $user_id)
            ->where('queue_id', $queueID)
            ->where('queue_number', $queue_number)
            ->orderBy('created_at', 'desc') // เรียงจากล่าสุดไปเก่าสุด
            ->limit(1) // จำกัดให้เลือกแค่ 1 record ล่าสุด
            ->update(['status' => "canceled"]);
    }

    public function updateStatusToComplete(int $user_id, int $queueID, string $queue_number){
        return DB::table('users_queues')
            ->where('user_id', $user_id)
            ->where('queue_id', $queueID)
            ->where('queue_number', $queue_number)
            ->orderBy('created_at', 'desc') // เรียงจากล่าสุดไปเก่าสุด
            ->limit(1) // จำกัดให้เลือกแค่ 1 record ล่าสุด
            ->update(['status' => "completed"]);
    }

    public function userWaitingQueue(int $queueID){
        $queues = DB::table('users_queues')
            ->join('queues', 'users_queues.queue_id', '=', 'queues.id') // เชื่อมกับตาราง queues
            ->join('users', 'users_queues.user_id', '=', 'users.id') // เชื่อมกับตาราง users
            ->where('users_queues.queue_id', $queueID)
            ->where("users_queues.status", "waiting")
            ->select([
                'queues.*',
                'users.name as user_name',
                'users.id as user_id',
                'users.phone as user_phone',
                'users_queues.status as status'
            ])
            ->get();
        return $queues;
    }

    public function getShopStat($shop_id)
    {
        $queues = DB::table('users_queues as uq')
            ->join('queues as q', 'uq.queue_id', '=', 'q.id')
            ->select(
                'q.shop_id',
                DB::raw('COUNT(uq.id) AS total_queues'),
                DB::raw("SUM(CASE WHEN uq.status = 'waiting' THEN 1 ELSE 0 END) AS waiting_count"),
                DB::raw("SUM(CASE WHEN uq.status = 'completed' THEN 1 ELSE 0 END) AS completed_count"),
                DB::raw("SUM(CASE WHEN uq.status = 'canceled' THEN 1 ELSE 0 END) AS canceled_count")
            )
            ->where('q.shop_id', $shop_id)
            ->groupBy('q.shop_id')
            ->orderBy('q.shop_id')
            ->get();
        return $queues;
    }

    public function getUserShopStat($shop_id){
        $queues = DB::table('users_queues as uq')
            ->join('queues as q', 'uq.queue_id', '=', 'q.id')
            ->join('users as u', 'uq.user_id', '=', 'u.id')
            ->select(
                'uq.queue_number',
                'u.name as customer_name',  // ชื่อคนจอง
                'uq.status',
                'u.phone as phone_number',  // เบอร์โทร
                'q.shop_id'
            )
            ->where('q.shop_id', $shop_id) // กรองเฉพาะ shop_id ที่กำหนด
            ->orderBy('uq.queue_number') // เรียงตาม queue_number
            ->get();
        return $queues;
    }

    public function getAllQueuesAllShops(){
        $queues = DB::table('users_queues')
            ->join('queues', 'users_queues.queue_id', '=', 'queues.id')
            ->join('users', 'users_queues.user_id', '=', 'users.id')
            ->select([
                'queues.*',
                'users.name as user_name',
                'users.email as user_email',
                'users_queues.status as status',
            ])
            ->get();
        return $queues;
    }
}
