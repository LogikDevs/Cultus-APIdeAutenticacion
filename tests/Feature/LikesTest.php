<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class LikesTest extends TestCase
{
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
   
    public function test_ListUserInterestGoodRequest(){
        $response = $this->get('api/v1/likes/user/1');
        $response -> assertStatus(200);
        $response->assertJsonCount(1, "interests");
        $response->assertJsonStructure([
            "interests" => [
                [
                    "id_label",
                    "interest",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                    "pivot" => [
                        "id_user",
                        "id_interest"
                    ]
                ]
            ]
        ]);
        $response -> assertJsonFragment([
            "id_label"=> 1,
            "interest"=> "gonzalito"
        ]);
    }

    public function test_ListUserInterestBadRequest(){
        $response = $this->get('api/v1/likes/user/999999');
        $response -> assertStatus(404);
    }

    public function test_ListInterestUserGoodRequest(){
        $response = $this->get('api/v1/likes/interest/1');
        $response -> assertStatus(200);
    }

    public function test_ListInterestUserBadRequest(){
        $response = $this->get('api/v1/likes/interest/11111111111');
        $response -> assertStatus(404);
    }


    public function test_CreateGoodRequest(){
        $response = $this->post('api/v1/likes/', [
            "id_interest"=>10,
        ]);

        $response -> assertStatus(201);
        $response -> assertJsonStructure([
                "id_interest",
                "id_user",
                "updated_at",
                "created_at",
                "id"
        ]);
        $this->assertDatabaseHas('likes',[
            "id_interest"=> 10,
            "id_user"=>11
        ]);
    }

    public function test_CreateBadRequest(){
        $response = $this->post('api/v1/likes/', [
            "id_interest"=>"aa",
        ]);
        $response -> assertStatus(200);
        $response -> assertJsonFragment([
            "id_interest"=>["The selected id interest is invalid."],
        ]);
    }

    public function test_DeleteGoodRequest(){
        $response = $this->delete('api/v1/likes/10' );
        $response -> assertStatus(200);
        $response -> assertJsonFragment(["response"=> "Object Deleted"]);
        $this->assertDatabaseMissing("likes",[
            "id_interest"=>10,
            "id_user"=>11,
            "deleted_at"=> null
        ]);
    }

    public function test_DeleteBadRequest(){
        $response = $this->delete('api/v1/likes/10000');
        $response -> assertStatus(404);
    }
}
