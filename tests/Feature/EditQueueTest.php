<?php

namespace Tests\Feature;

use Mockery;
use App\Models\Shop;
use App\Models\User;
use App\Models\Queue;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Http\Request;
use App\Repositories\QueueRepository;
use App\Repositories\UserQueueRepository;
use App\Http\Controllers\API\QueueController;
use Faker\Factory as FakerFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditQueueTest extends TestCase
{

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

    /* TODO: 1. User Story Edit Queue Type
        Acceptance Criteria:
        1.Given a queue type exists, when the shop edits its details and submits the changes,
        then the system should update the queue type successfully.
        2.Given a queue type exists, when the shop updates its name, description, or other attributes,
        then the changes should be reflected in the system.
        3.Given the shop submits invalid data while editing a queue type, when they attempt to save changes,
        then the system should reject the request and display an appropriate error message.
    */
    public function test_shop_can_edit_queue_type_successfully()
    {
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

        // Create an existing queue type
        $queueType = Queue::factory()->create([
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
            "/api/queues/{$queueType->id}",
            'PATCH',
            $updatedData
        );

        // Call update method
        $response = $this->queueController->update($request, $queueType);

        // Assert successful update
        $this->assertEquals(200, $response->getStatusCode());

        // Refresh the model and verify updates
        $queueType->refresh();
        $this->assertEquals($updatedData['name'], $queueType->name);
        $this->assertEquals($updatedData['description'], $queueType->description);
    }
    /**
     * Test editing queue type with invalid data
     */
    public function test_shop_cannot_edit_queue_type_with_invalid_data()
    {
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

        // Create an existing queue type
        $queueType = Queue::factory()->create([
            'name' => 'Table TEST',
            'description' => 'โต๊ะ TEST',
            'is_available' => true,
            'tag' => "TS",
            'shop_id' => $shop->id,
        ]);

        // Prepare updated data
        $updatedData = [
            'name' => '1',
            'description' => '08347123',
            'is_available' => 'true',
        ];

        // Create request
        $request = Request::create(
            "/api/queues/{$queueType->id}",
            'PATCH',
            $updatedData
        );

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->queueController->update($request, $queueType);
    }

    /**
     * Test that a shop can only edit its own queue type
     */
    public function test_shop_cannot_edit_another_shops_queue_type()
    {
        $user = User::factory()->create([
            'name' => "test01",
            'email' => "test@test.com",
            'password' => Hash::make('password'),
            'phone' => "0000000000",
            'role' => 'SHOP',
            'email_verified_at' => now(),
        ]);

        $user2 = User::factory()->create([
            'name' => "test02",
            'email' => "test2@test.com",
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
        $this->actingAs($user2);

        // Create an existing queue type
        $queueType = Queue::factory()->create([
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
            "/api/queues/{$queueType->id}",
            'PATCH',
            $updatedData
        );

        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        $this->queueController->update($request, $queueType);
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
