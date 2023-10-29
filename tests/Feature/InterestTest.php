<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class InterestTest extends TestCase
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
    public function test_ListAll(){
        $response = $this->get('api/v1/interest');
        $response -> assertStatus(200);
    }
}
