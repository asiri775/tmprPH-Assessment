<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CSVFileAccessibilityTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_file_accessibility()
    {
        $path=storage_path() .env('DATA_SOURCE_FILE');
        $this->assertFileExists($path, "CSV does not exist");
        
    }
    
}


