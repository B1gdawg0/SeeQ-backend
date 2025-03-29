<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiCallingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_call_api(): void
    {
        $iterations = 1000;  // กำหนดจำนวนรอบที่จะเรียก API
        $success = 0;        // นับจำนวนครั้งที่เรียกได้สำเร็จ
        for ($i = 0; $i < $iterations; ++$i) {
            $response = $this->get('/api');     // เรียก API
            if ($response->status() === 200) {  // ถ้าเรียกได้สำเร็จ เพิ่มค่า $success
                $success++;
            }
        }
        $expected = 60;      // คาดหวังว่าจะเรียกได้สำเร็จ 60 ครั้ง
        $this->assertEquals($expected, $success);    // ยืนยันว่าเรียกได้สำเร็จตามที่คาดหวัง
    }
}
