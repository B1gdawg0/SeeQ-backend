<?php

namespace App\Http\Controllers;

use App\Repositories\ReminderRepository;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function __construct(
        private ReminderRepository $reminderRepository
    ){}


    public function index(){
        $reminders = $this->reminderRepository->getAll();
        return json_encode($reminders);
    }

    public function show($shop_id)
    {
        $shopId = (int)$shop_id;


        if (!$shopId) {
            return response()->json(['error' => 'shop_id is required'], 400);
        }

        $reminder = $this->reminderRepository->getAllRemindersByShopId($shopId);
        return response()->json($reminder);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'shop_id' => 'required | numeric',
            'title' => 'required',
            'description' => 'required',
            'due_date' => 'required | date',
        ]);

        $shopId = (int)$request->get('shop_id');
        if (!$shopId) {
            return response()->json(['error' => 'shop_id is required'], 400);

        } else {
            return $this->reminderRepository->create($validated);
        }
    }
    public function markAsDone($id){
        $shopId = (int)$id;
        if (!$shopId) {
            return response()->json(['error' => 'shop_id is required'], 400);
        }
        return $this->reminderRepository->markAsDone($shopId);
    }



}
