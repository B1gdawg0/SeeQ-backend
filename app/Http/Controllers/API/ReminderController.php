<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use App\Repositories\ReminderRepository;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function __construct(
        private ReminderRepository $reminderRepository
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reminders = $this->reminderRepository->getAll();
        return response()->json($reminders);
    }

    public function store(Request $request, $shop_id)
    {
        $validated = $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['required'],
            'reminder_time' => ['required'],
        ]);
        $reminder = $this->reminderRepository->create([
            'title'=> $validated["title"],
            'description'=> $validated["description"],
            'shop_id' => $shop_id,
            'reminder_time' => $validated["reminder_time"],
        ]);
        return new ReminderResource($reminder);
    }
}
