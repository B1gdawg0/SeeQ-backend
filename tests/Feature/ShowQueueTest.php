<?php

namespace Tests\Feature;

use App\Http\Controllers\API\QueueController;
use App\Models\Queue;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\QueueRepository;
use App\Repositories\UserQueueRepository;
use App\Utils\JsonHelper;
use Faker\Factory as FakerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class ShowQueueTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;
    private $queueRepository;
    private $userQueueRepository;
    private $queueController;

    protected function setUp(): void
    {
        parent::setUp();

        // Use real repositories with database interactions
        $this->queueRepository = $this->app->make(QueueRepository::class);
        $this->userQueueRepository = $this->app->make(UserQueueRepository::class);

        $this->queueController = new QueueController(
            $this->queueRepository,
            $this->userQueueRepository
        );
    }

    /* TODO: 3. User Story Show Type Queue
        Acceptance Criteria:
        1.Given queue types exist in the system, when the shop views the queue type list,
        then the system should display all available queue types.
        2.Given no queue types exist, when the shop views the queue type list, then the system
        should display an appropriate message indicating no queue types are available.
        3.Given a queue type has been recently created or updated, when the shop refreshes the queue type list,
        then the system should display the latest queue type data.
    */

    public function test_shop_can_show_all_queue_correct()
    {
        // สร้าง User ที่มี role เป็น SHOP
        $user = User::factory()->create([
            'name' => "test01",
            'email' => "test@test.com",
            'password' => Hash::make('password'),
            'phone' => "0000000000",
            'role' => 'SHOP',
            'email_verified_at' => now(),
        ]);

        // ใช้ Faker สร้างข้อมูลร้านค้า
        $faker = FakerFactory::create('th_TH');
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
            'name' => $faker->company,
            'image_url' => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'description' => "Open leaw",
            'is_open' => $faker->boolean,
            'latitude' => $faker->latitude(13.826, 13.846), // Adjusted range for Ratchayothin - Kasetsart University
            'longitude' => $faker->longitude(100.566, 100.592), // Adjusted range for Ratchayothin - Kasetsart University
        ]);

        // สร้าง Queue ที่เกี่ยวข้องกับร้าน
        $queue1 = Queue::create([
            'name' => 'Table 1',
            'description' => 'โต๊ะ TEST',
            'is_available' => true,
            'tag' => "TS",
            'shop_id' => $shop->id,
        ]);

        $queue2 = Queue::create([
            'name' => 'Table 2',
            'description' => 'โต๊ะ TEST',
            'is_available' => true,
            'tag' => "TS",
            'shop_id' => $shop->id,
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // เรียก API
        $response = $this->getJson("/api/queues?shop_id={$shop->id}");

        // ตรวจสอบ Response Status
        $response->assertStatus(200);

        // ตรวจสอบข้อมูล JSON ที่ส่งกลับมา
        $response->assertJson([
            "data" => [
                [
                    "id" => $queue1->id,
                    "name" => $queue1->name,
                    "description" => $queue1->description,
                    "is_available" => $queue1->is_available,
                    "tag" => $queue1->tag,
                    "shop_id" => $queue1->shop_id,
                ],
                [
                    "id" => $queue2->id,
                    "name" => $queue2->name,
                    "description" => $queue2->description,
                    "is_available" => $queue2->is_available,
                    "tag" => $queue2->tag,
                    "shop_id" => $queue2->shop_id,
                ],
            ],
        ]);
    }

    public function test_shop_show_appropriate_message_when_queue_not_exist()
    {
        // สร้าง User ที่มี role เป็น SHOP
        $user = User::factory()->create([
            'name' => "test01",
            'email' => "test@test.com",
            'password' => Hash::make('password'),
            'phone' => "0000000000",
            'role' => 'SHOP',
            'email_verified_at' => now(),
        ]);

        // ใช้ Faker สร้างข้อมูลร้านค้า
        $faker = FakerFactory::create('th_TH');
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
            'name' => "Shop test",
            'image_url' => $faker->imageUrl(640, 480, 'business'),
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'is_open' => $faker->boolean,
            'latitude' => $faker->latitude(12, 14),
            'longitude' => $faker->longitude(100, 104),
        ]);

        // Authenticate the user
        $this->actingAs($user);

        // เรียก API
        $response = $this->getJson("/api/queues?shop_id={$shop->id}");

        // ตรวจสอบ Response Status
        $response->assertStatus(200);

        // ตรวจสอบข้อมูล JSON ที่ส่งกลับมา
        $response->assertJson([
            "data" => [],
            "message"=>  "No queues found",
        ]);
    }

    public function test_shop_show_latest_queue()
    {
        // สร้าง User ที่มี role เป็น SHOP
        $user = User::factory()->create([
            'name' => "test01",
            'email' => "test@test.com",
            'password' => Hash::make('password'),
            'phone' => "0000000000",
            'role' => 'SHOP',
            'email_verified_at' => now(),
        ]);

        // ใช้ Faker สร้างข้อมูลร้านค้า
        $faker = FakerFactory::create('th_TH');
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
            'name' => "Shop test",
            'image_url' => $faker->imageUrl(640, 480, 'business'),
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'is_open' => $faker->boolean,
            'latitude' => $faker->latitude(12, 14),
            'longitude' => $faker->longitude(100, 104),
        ]);

        // Authenticate the user
        $this->actingAs($user);

        $queue = Queue::factory()->create([
            'name' => 'Table TEST',
            'description' => 'โต๊ะ TEST',
            'is_available' => true,
            'tag' => "TS",
            'shop_id' => $shop->id,
        ]);

        // Prepare updated data
        $updatedData = [
            'name' => 'Updated Queue Type',
            'description' => 'Updated Description',
        ];

        // Create request
        $request = Request::create(
            "/api/queues/{$queue->id}",
            'PATCH',
            $updatedData
        );

        // Call update method
        $this->queueController->update($request, $queue);

        // เรียก API
        $response = $this->getJson("/api/queues?shop_id={$shop->id}");

        // ตรวจสอบ Response Status
        $response->assertStatus(200);

        // ตรวจสอบข้อมูล JSON ที่ส่งกลับมา
        $response->assertJson([
            "data" => [
                [
                    "id" => $queue->id,
                    "name" => $updatedData["name"],
                    "description" => $updatedData["description"],
                    "is_available" => $queue->is_available,
                    "tag" => $queue->tag,
                    "shop_id" => $queue->shop_id,
                ],
            ],
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
