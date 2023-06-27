<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InterestTest extends TestCase
{
    public function test_ListAll(){
        $response = $this->get('api/v1/interest');
        $response -> assertStatus(200);
    }
}
