<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Storage\FileStorage;
class CurveDataTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_curve_data()
    {
        $response = $this->getJson('/api/charts/weeklyData');
        $response->assertStatus(200);
        $response = $this->getJson('/api/charts/upcaseData');
        $response->assertStatus(200);
    }
}
