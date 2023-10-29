<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class FollowsTest extends TestCase
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

    public function test_ListFollowersGoodRequest(){
      $response = $this->get('api/v1/followers');
      $response -> assertStatus(200);
    }

    public function test_ListFollowersBadRequest(){
      $response = $this->get('api/v1/followers/1');
      $response -> assertStatus(404);
    }


    public function test_ListFollowedsGoodRequest(){
      $response = $this->get('api/v1/followeds');
      $response -> assertStatus(200);
    }

    public function test_ListFollowedsBadRequest(){
      $response = $this->get('api/v1/followeds/2');
      $response -> assertStatus(404);
    }


    public function test_ListFriendGoodRequest(){
      $response = $this->get('api/v1/friends');
      $response -> assertStatus(200);
    }

    public function test_ListFriendBadRequest(){
      $response = $this->get('api/v1/friends/2');
      $response -> assertStatus(404);
    }
    
    public function test_FollowGoodRequest(){
      $response = $this->post('api/v1/follow', [
          "id_followed"=>3,
      ]);

      $response -> assertStatus(201);
      $response -> assertJsonStructure([
          "id_followed",
          "id_follower",
          "friends",
          "updated_at",
          "created_at",
          "id_follows"
      ]);
      $this->assertDatabaseHas('follows',[
        "id_followed"=>2,
        "id_follower"=>11
      ]);
    }

    public function test_FollowBadRequest(){
      $response = $this->post('api/v1/follow', [
        "id_followed"=>"a"
      ]);
      $response -> assertStatus(200);
      $response -> assertJsonFragment([
          "id_followed"=>["The selected id followed is invalid."]
      ]);
    }
    

    public function test_UnfollowGoodRequest(){
      $response = $this->post('api/v1/unfollow', [
        "id_followed"=>22,
    ]);

    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response"=> "Object with IDfollowed 22 Deleted"]);
    $this->assertDatabaseMissing('follows',[
      "id_followed"=>22,
      "id_follower"=>11,
      "deleted_at"=>null
    ]);
    }

    public function test_UnfollowBadRequest(){
      $response = $this->post('api/v1/unfollow', [
        "id_followed"=>1,
        "id_follower"=>100
    ]);
    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response"=> "No follow record found for the given conditions"]);
    }


    public function test_MakeFriendGoodRequest(){
      $response = $this->post('api/v1/friends', [
        "id_followed"=>2,
        "friends"=>true
    ]);

    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response"=> "Change friend status to 1 successfully"]);
    $this->assertDatabaseHas('follows',[
      "id_followed"=>2,
      "id_follower"=>11,
      "friends"=>true
    ]);
    }

    public function test_MakeFriendBadRequest(){
      $response = $this->post('api/v1/friends', [
        "id_followed"=>1,
        "id_follower"=>111,
        "friends"=>true
    ]);
    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response"=> "Users not following each other."]);
    }


    public function test_UnFriendGoodRequest(){
      $response = $this->post('api/v1/friends', [
        "id_followed"=>2,
        "friends"=>false
    ]);

    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response" => "Change friend status to false successfully"]);
    $this->assertDatabaseMissing('follows',[
      "id_followed"=>2,
      "id_follower"=>11,
      "friends"=>1     
    ]);
    }

    public function test_UnFriendBadRequest2(){
      $response = $this->post('api/v1/friends', [
        "id_followed"=>1,
        "friends"=>false
    ]);
    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response" => "Users not following each other."]);
    }
  
}
