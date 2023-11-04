<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class CountryTest extends TestCase
{
    private $BearerToken;
    
    public function setUp() :void{
     parent::setUp();

     $this->userName = getenv("USERNAME");
     $this->userPassword = getenv("USERPASSWORD");
     $this->clientId = getenv("CLIENTID");
     $this->clientSecret = getenv("CLIENTSECRET");

     $tokenHeader = [ "Content-Type" => "application/json"];
     $Bearer = Http::withHeaders($tokenHeader)->post(getenv("API_AUTH_URL") . "/oauth/token",
      [
         'username' => $this->userName,
         'password' => $this->userPassword,
         "grant_type" => "password",
         'client_id' => $this->clientId,
         'client_secret' => $this->clientSecret,
     ])->json();

     $this->BearerToken = $Bearer['access_token'];
     $this->withHeaders(['Authorization' => 'Bearer ' . $this->BearerToken]);
    }
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