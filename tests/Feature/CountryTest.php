<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountryTest extends TestCase
{

    public function test_ListOneThatExist(){
        $response = $this->get('/api/v1/country/1');
        
        $response -> assertStatus(200);
        $response->assertJsonStructure([
            "id_country",
            "country_name",
            "created_at",
            "updated_at",
            "deleted_at"
        ]);
    }

    public function test_ListOneThatDoesntExist(){
        $response = $this->get('api/v1/country/1000');
        $response -> assertStatus(404);
    }
}