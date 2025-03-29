<?php

namespace Tests\Feature;

use App\Http\Controllers\API\QueueController;
use App\Models\Queue;
use App\Models\Shop;
use App\Models\User;
use App\Repositories\QueueRepository;
use App\Repositories\UserQueueRepository;
use Faker\Factory as FakerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class CreateQueueTest extends TestCase
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


    /* TODO: 2. User Story Create Queue Type
        Acceptance Criteria:
        1.Given the shop wants to create a new queue type, when they submit valid details,
        then the system should successfully create the queue type and store it.
        2.Given the shop provides incomplete or invalid data, when they attempt to create a queue type,
        then the system should reject the request and display an appropriate error message.
        3.Given a queue type with the same name already exists, when the shop attempts to create a duplicate queue type,
        then the system should prevent it and notify the shop.
    */
    public function test_shop_can_create_queue_successfully(){
        $user = User::factory()->create([
            'name' => "test01",
            'email' => "test@test.com",
            'password' => Hash::make('password'),
            'phone' => "0000000000",
            'role' => 'SHOP',
            'email_verified_at' => now(),
        ]);
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

        // Prepare updated data
        $createData = [
            'name' => 'Table TEST',
            'description' => 'โต๊ะ TEST',
            'is_available' => true,
            'tag' => "TS",
            'shop_id' => $shop->id,
        ];

        // Create request
        $request = Request::create(
            "/api/queues",
            'POST',
            $createData
        );

        // Call update method
        $response = $this->queueController->store($request);

        // Assert successful update
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_shop_cannot_create_queue_with_invalid_data(){
        $user = User::factory()->create([
            'name' => "test01",
            'email' => "test@test.com",
            'password' => Hash::make('password'),
            'phone' => "0000000000",
            'role' => 'SHOP',
            'email_verified_at' => now(),
        ]);
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

        // Prepare updated data
        $createData = [
            'name' => 1,
            'description' => 1,
            'is_available' => 'true',
            'tag' => 2,
            'shop_id' => $shop->id,
        ];

        // Create request
        $request = Request::create(
            "/api/queues",
            'POST',
            $createData
        );

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $response = $this->queueController->store($request);
    }


    public function test_shop_cannot_create_queue_with_already_exist(){
        $user = User::factory()->create([
            'name' => "test01",
            'email' => "test@test.com",
            'password' => Hash::make('password'),
            'phone' => "0000000000",
            'role' => 'SHOP',
            'email_verified_at' => now(),
        ]);
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

        $queue1 = Queue::factory()->create([
            'name' => 'Table TEST',
            'description' => 'โต๊ะ TEST',
            'is_available' => true,
            'tag' => "TS",
            'shop_id' => $shop->id,
        ]);

        // Prepare updated data
        $createData = [
            'name' => 'Table TEST',
            'description' => 'โต๊ะ TEST',
            'is_available' => true,
            'tag' => "TS",
            'shop_id' => $shop->id,
        ];

        // Create request
        $request = Request::create(
            "/api/queues",
            'POST',
            $createData
        );

        $response = $this->queueController->store($request);
        $this->assertEquals(500, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

}
