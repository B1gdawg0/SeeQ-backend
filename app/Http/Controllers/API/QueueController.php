<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\IdResource;
use App\Http\Resources\QueueAdminCollection;
use App\Http\Resources\QueueAdminResource;
use App\Http\Resources\QueueCollection;
use App\Http\Resources\QueueResource;
use App\Models\Queue;
use App\Repositories\QueueRepository;
use App\Repositories\UserQueueRepository;
use App\Utils\JsonHelper;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0",
 * )
 */


class QueueController extends Controller
{
    public function __construct(
        private QueueRepository $queueRepository,
        private UserQueueRepository $userQueueRepository
    ) {}

    /**
     * Note about cacheKey
     * queue_shop:shop_id is about all type of queue in that shop
     * queue_shop is about all type of queue
     * queue_info:queue_id is about information of queue
     * queue is use for queue management
     *
     */


    /**
     * @OA\Get(
     *     path="/api/queues",
     *     summary="Get list of queues",
     *     tags={"Queues"},
     *     @OA\Parameter(
     *         name="shop_id",
     *         in="query",
     *         required=false,
     *         description="for specific shop",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of tables",
     *          @OA\JsonContent (
     *              type="object",
     *              @OA\Property (
     *                  property="name",
     *                  type="string",
     *                  description="name of queue"
     *              )
     *          )
     *     )
     * )
     */
    public function index(Request $request)
    {
        Gate::authorize("viewAny", Queue::class);
        $shop_id = $request->query("shop_id");
        //query section
        if($shop_id){
           $cacheKey = "queue_shop:$shop_id";
           $queuesJson = Redis::get($cacheKey);

           if($queuesJson){
               $queues = JsonHelper::parseJsonToCollection($queuesJson);
               return response()->json(["data" => $queues], 200);
           }

           $queues = $this->queueRepository->getAllByShopID($shop_id);
           if(count($queues) == 0){
               return response()->json([
                   "data" => [],
                   "message" => "No queues found"
               ], 200);
           }
           Redis::setex($cacheKey, 10, json_encode($queues));
           return response()->json(["data" => $queues], 200);
        }

        // no query section
        $cacheKey = "queue_shop";
        $queuesJson = Redis::get($cacheKey);

        if($queuesJson){
            $queues = JsonHelper::parseJsonToCollection($queuesJson);
            return response()->json(["data" => $queues], 200);
        }

        $queues = $this->queueRepository->getAll();
        Redis::setex($cacheKey, 10, json_encode($queues));
        return response()->json(["data" => $queues], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize("create", Queue::class);
        $isAvailable =  filter_var($request["is_available"], FILTER_VALIDATE_BOOLEAN);
        $request["is_available"] = $isAvailable;
        $request->validate([
            "name" => "string|required",
            "description" => "string|required",
            "is_available" => "boolean|required",
            "tag" => "string|required",
        ]);

        try{
            $queue = $this->queueRepository->create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'is_available' => $request->get('is_available'),
                'tag' => $request->get('tag'),
                'shop_id' => $request->get('shop_id'),
            ]);
        }catch ( QueryException $e ){
            return response()->json(["message" => "This name is already in use"], 500);
        }

        if ($request->hasFile('image')) {
            $file = $request->image;
            $filename = now()->format('Y-m-d_H:i:s.u') . '.png';
            $path = 'queues/'. $queue->id .'/images/logos/'. $filename;
            Storage::disk('s3')->put($path, file_get_contents($file), 'private');
            $uri = str_replace('/', '+', $path);
            $queue->update([
                'image_url' => env("APP_URL") . '/api/images/' . $uri
            ]);

        }
        return response()->json(["data" => $queue], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Queue $queue)
    {
        Gate::authorize("viewAny", Queue::class);
        $id = $queue->id;
        $cacheKey = "queue_info:$id";

        // get from redis if in redis return from redis
        $queueJson = Redis::get($cacheKey);
        if($queueJson){
            $queue = JsonHelper::parseJsonToObject($queueJson);
            return new QueueResource($queue);
        }

        // not in redis read from db and cache to redis
        $queue = $this->queueRepository->getById($id);
        Redis::setex($cacheKey, 30, json_encode($queue));
        return new QueueResource($queue);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Queue $queue)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image'); // Get the uploaded file
            $filename = now()->format('Y-m-d_H:i:s.u') . '.png';
            $path = 'queues/' . $queue->id . '/images/logos/' . $filename;

            // Store the file in S3 with 'private' visibility
            Storage::disk('s3')->put($path, file_get_contents($file), 'private');

            // Convert path to a URL-safe format
            $uri = str_replace('/', '+', $path);

            // Merge new data into the request
            $request->merge([
                'image_url' => env("APP_URL") . '/api/images/' . $uri
            ]);
        }

        // Authorize the action
        Gate::authorize("update", $queue);

        // Validate request
        $validate = $request->validate([
            "name" => "string|nullable",
            "description" => "string|nullable",
            "is_available" => "boolean|nullable",
            "image_url" => "string|nullable",
            "tag" => "string|nullable",
        ]);


        // Update Queue data
        $this->queueRepository->update($validate, $queue->id);

        // Clear and refresh cache
        $cacheKey = "queue_info:$queue->id";
        Redis::del($cacheKey);
        Redis::setex($cacheKey, 30, json_encode($queue->refresh()));
        return response()->json([
            "data" => $queue,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Queue $queue)
    {
        $queue = $this->queueRepository->getById($queue->id);
        if(!$queue){
            return response()->json(["message" => "Queue not found"], 404);
        }

        $cacheKey = "queue_info:$queue->id";
        $queueCacheJson = Redis::get($cacheKey);
        if($queueCacheJson){
            Redis::del($cacheKey);
        }

        $this->queueRepository->delete($queue->id);
        $queues = $this->queueRepository->getAll();
        $cacheKey = "queue_shop";
        Redis::setex($cacheKey, 300, json_encode($queues));

        return response()->json(["message" => "Queue deleted successfully"], 200);
    }

    public function joinQueue(Request $request, $queue_id)
    {
        Gate::authorize("viewAny", Queue::class);
        $user_id = auth()->id();

        $queueUserGot = $request->get("queue_user_got"); // A_02
        $value = "$user_id" . "_" . $queueUserGot;
        $queue = $this->queueRepository->getById($queue_id);

        if (!$queue){
            return response()->json(["message" => "Queue not found"], 404);
        }

        $queueKey = "queue:$queue->id";

        $alreadyExist = $this->userQueueRepository->checkUserAlreadyJoinQueue($user_id, $queue_id);
        if($alreadyExist){
            return response()->json(["message" => "Already joined this queue"], 400);
        }

        $attributes = ["user_id" => $user_id, "queue_id" => $queue->id, "queue_number" => $queueUserGot];

        $userQueueId =  $this->userQueueRepository->create($attributes);
        if(!$userQueueId){
            return response()->json(["message" => "Error When try to create queueUser"], 404);
        }


        Redis::lpush($queueKey, $value);

        $this->queueRepository->increaseQueueCounter($queue_id); // เพิ่ม counter ของ queue

        $cacheKey = "queue_info:$queue->id";
        Redis::del($cacheKey);
        Redis::setex($cacheKey, 30, json_encode($queue->refresh()));

        return response()->json(["message" => "User is in queue now",], 200);
    }

    public function status(Request $request, $queue_id)
    {
        Gate::authorize("viewAny", Queue::class);
        $user_id = auth()->id();

        $queueUserGot = $request->get("queue_user_got"); // A_02
        $value = "$user_id" . "_" . $queueUserGot;

        $queue = $this->queueRepository->getById($queue_id);
        if (!$queue){
            return response()->json(["message" => "Queue not found"], 404);
        }

        $queueKey = "queue:$queue->id";

        // Get the list of users in the queue
        $queueList = Redis::lrange($queueKey, 0, -1);

        // Reverse the list so that the front of the queue is at index 0
        $queueList = array_reverse($queueList);

        // Find the position of the user in the queue list
        $position = array_search($value, $queueList);

        if ($position === false) {
            return response()->json([
                "message" => "User is not in queue",
                "list" => $queueList,
                "value" => $value
            ], 303);
        }

        // Human-readable position (1-based index)
        $humanReadablePosition = $position + 1;

        return response()->json([
            "message" => "User's queue position",
            "position" => $humanReadablePosition,
            "user_id" => $user_id,
            "queue_name" => $queueUserGot,
        ], 200);

    }

    public function cancel(Request $request, $queue_id)
    {
        Gate::authorize("viewAny", Queue::class);
        $user_id = auth()->id();
        $queueUserGot = $request->get("queue_user_got"); // A_02
        $value = "$user_id" . "_" . $queueUserGot;

        $queue = $this->queueRepository->getById($queue_id);
        if (!$queue){
            return response()->json(["message" => "Queue not found"], 404);
        }

        $join = $this->userQueueRepository->checkUserAlreadyJoinQueue($user_id, $queue_id);
        if($join){
            $this->userQueueRepository->updateStatusToCancel($user_id, $queue_id, $queueUserGot);
        }

        $queueKey = "queue:$queue_id";

        // Check if user is in queue
        if (Redis::lpos($queueKey, $value) === false) {
            return response()->json(["message" => "User is not in queue"], 404);
        }

        Redis::lrem($queueKey, 1, $value);
        // Notify via Redis Pub/Sub
        Redis::publish("queue_updates:$queue_id", json_encode([
            "event" => "cancel",
            "user_id" => $user_id,
            "queue_name" => $queueUserGot,
        ]));

        return response()->json([
            "message" => "Remove from queue now",
            "removeStatus" => true
        ], 200);

    }

    public function next(Request $request, $queue_id)
    {
        $queue = $this->queueRepository->getById($queue_id);
        Gate::authorize("nextQueue", $queue); // if in development comment this line
        if (!$queue){
            return response()->json(["message" => "Queue not found"], 404);
        }
        $queueKey = "queue:$queue_id";

        // ใช้ Redis Transaction
        $nextQueue = Redis::transaction(function ($redis) use ($queueKey) {
            return $redis->rpop($queueKey);
        });

        if (!$nextQueue[0]){
            return response()->json(["message" => "Queue is empty"], 200);
        }

        $array = explode("_", $nextQueue[0]);
        $user_id = $array[0];
        $queueUserGot = $array[1];
        $this->userQueueRepository->updateStatusToComplete($user_id, $queue_id, $queueUserGot);

        // Notify via Redis Pub/Sub
        Redis::publish("queue_updates:$queue_id", json_encode([
            "event" => "next",
            "nextQueue" => $nextQueue[0]
        ]));

        return response()->json([
            "message" => "Next user called",
            "next_queue" => $nextQueue[0]
        ], 200);
    }

    public function getQueueNumber(Request $request, $queue_id){
        $user_id = auth()->id();
        $queueNumberWithQueueInfo = $this->userQueueRepository->checkQueueNumberAndQueueInfo($user_id, $queue_id);
        return response()->json([
            "data" =>$queueNumberWithQueueInfo
        ]);
    }

    public function getCompleteQueue(Request $request, $queue_id){
        $user_id = auth()->id();
        $queueNumberWithQueueInfo = $this->userQueueRepository->getCompleteQueueInfo($user_id, $queue_id);
        return response()->json([
            "data" =>$queueNumberWithQueueInfo
        ]);
    }

    public function getAllQueues(Request $request, $queue_id)
    {
        $usersInQueue = $this->userQueueRepository->userWaitingQueue($queue_id);


        return response()->json([
            "data" => $usersInQueue
       ], 200);
    }

    public function getAllQueuesAllShops(Request $request)
    {
        $queues = $this->userQueueRepository->getAllQueuesAllShops();

        return response()->json([
            "data" => $queues
        ]);
    }

    public function getQueueReserved(Request $request)
    {
        $user_id = auth()->id();
        $queues = $this->userQueueRepository->getAllQueueReservedComplete($user_id);
        return response()->json([
           "data" => $queues
        ]);
    }

    public function getQueueReservedWaiting(Request $request){
        $user_id = auth()->id();
        $queues = $this->userQueueRepository->getAllQueueReservedWaiting($user_id);
        return response()->json([
            "data" => $queues
        ]);
    }

    public function getShopStat(Request $request){
        $shop_id = $request->query("shop_id");
        $stat = $this->userQueueRepository->getShopStat($shop_id);
        $userInQueues = $this->userQueueRepository->getUserShopStat($shop_id);
        return response([
            "data" => [
                "shop_stat" => $stat,
                "users_in_queues" => $userInQueues,
                ]
        ]);
    }

    public function testConnection(Request $request)
    {
        try {
            $redis = Redis::connection();
            $result = $redis->ping();

            return response()->json([
                'status' => 'success',
                'message' => 'Redis connection successful',
                'ping_result' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Redis connection failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkPublisherChannel(Request $request)
    {
        $channels = Redis::command('PUBSUB', ['CHANNELS']);
        dd($channels);
    }
}
