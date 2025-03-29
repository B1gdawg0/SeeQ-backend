<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected static ?string $password;

    public function definition(): array
    {
        $faker = FakerFactory::create('th_TH');
        $shopTypes = [
            'Bakery', 'Bar', 'Cafe', 'Fast Food', 'Food Truck', 'Restaurant', 'Pub', 'Yakiniku', 'Shabu',
        ];
        $shopDescriptions = [
            'Bakery' => 'ร้านเบเกอรี่ที่มีขนมอบสดใหม่ เช่น ขนมปัง เค้ก และพาย',
            'Bar' => 'บาร์ที่ให้บริการเครื่องดื่มแอลกอฮอล์และค็อกเทล พร้อมบรรยากาศชิลๆ',
            'Cafe' => 'คาเฟ่ที่มีเมนูเครื่องดื่ม กาแฟ ขนม และบรรยากาศสบายๆ',
            'Fast Food' => 'ร้านอาหารฟาสต์ฟู้ดที่เสิร์ฟอาหารรวดเร็ว เช่น เบอร์เกอร์ ไก่ทอด และเฟรนช์ฟรายส์',
            'Food Truck' => 'ร้านอาหารเคลื่อนที่ที่ให้บริการอาหารสตรีทฟู้ด อร่อยและสะดวก',
            'Restaurant' => 'ร้านอาหารที่มีเมนูหลากหลายและบรรยากาศเหมาะกับการรับประทานอาหาร',
            'Pub' => 'ผับที่ให้บริการเครื่องดื่มและอาหาร พร้อมดนตรีสดหรือบรรยากาศสนุกสนาน',
            'Yakiniku' => 'ร้านปิ้งย่างสไตล์ญี่ปุ่นที่ลูกค้าสามารถย่างเนื้อและผักเองได้ที่โต๊ะ',
            'Shabu' => 'ร้านชาบูที่ให้บริการหม้อไฟและวัตถุดิบสดใหม่สำหรับการลวกในน้ำซุป',
        ];

        return [
            'user_id' =>  fake()->unique()->randomElement(User::all()->where('role', 'SHOP')->pluck('id')->toArray()),
            'name' => $faker->company,
            'image_url' => env('APP_URL') . '/api/images/shops+defaults+images+avatar.png',
            'phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'description' => $shopDescriptions[$faker->randomElement($shopTypes)],
            'is_open' => $faker->boolean,
            'latitude' => $faker->latitude(13.826, 13.846), // Adjusted range for Ratchayothin - Kasetsart University
            'longitude' => $faker->longitude(100.566, 100.592), // Adjusted range for Ratchayothin - Kasetsart University
        ];
    }
}
