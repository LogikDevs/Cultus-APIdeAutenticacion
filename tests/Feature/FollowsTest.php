<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FollowsTest extends TestCase
{

    public function test_ListFollowersGoodRequest(){
      $response = $this->get('api/v1/followers/1');
      $response -> assertStatus(200);
    }

    public function test_ListFollowersBadRequest(){
      $response = $this->get('api/v1/followers/200000');
      $response -> assertStatus(404);
    }


    public function test_ListFollowedsGoodRequest(){
      $response = $this->get('api/v1/followeds/1');
      $response -> assertStatus(200);
    }

    public function test_ListFollowedsBadRequest(){
      $response = $this->get('api/v1/followeds/20000');
      $response -> assertStatus(404);
    }


    public function test_ListFriendGoodRequest(){
      $response = $this->get('api/v1/friends/1');
      $response -> assertStatus(200);
    }

    public function test_ListFriendBadRequest(){
      $response = $this->get('api/v1/friends/20000');
      $response -> assertStatus(404);
    }
    
    
    public function test_FollowGoodRequest(){
      $response = $this->post('api/v1/follow', [
          "id_followed"=>2,
          "id_follower"=>1
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
        "id_follower"=>1
      ]);
    }

    public function test_FollowBadRequest(){
      $response = $this->post('api/v1/follow', [
        "id_followed"=>"a"
      ]);
      $response -> assertStatus(200);
      $response -> assertJsonFragment([
          "id_follower"=>["The id follower field is required."],
          "id_followed"=>["The selected id followed is invalid."]
      ]);
    }
    
    
    public function test_UnfollowGoodRequest(){
      $response = $this->post('api/v1/unfollow', [
        "id_followed"=>52,
        "id_follower"=>51
    ]);

    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response"=> "Object with IDfollowed 52 Deleted"]);
    $this->assertDatabaseMissing('follows',[
      "id_followed"=>52,
      "id_follower"=>51,
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
        "id_follower"=>1,
        "friends"=>true
    ]);

    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response"=> "Friends created succesfully."]);
    $this->assertDatabaseHas('follows',[
      "id_followed"=>2,
      "id_follower"=>1,
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
      $response = $this->post('api/v1/friends/unfriend', [
        "id_followed"=>2,
        "id_follower"=>1,
        "friends"=>true
    ]);

    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response" => "Friends eliminated succesfully."]);
    $this->assertDatabaseMissing('follows',[
      "id_followed"=>2,
      "id_follower"=>1,
      "friends"=>true     
    ]);
    }

    public function test_UnFriendBadRequest1(){
      $response = $this->post('api/v1/friends/unfriend', [
        "id_followed"=>2,
        "id_follower"=>1,
        "friends"=>true
    ]);
    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response" => "Users are not friends."]);
    }

    public function test_UnFriendBadRequest2(){
      $response = $this->post('api/v1/friends/unfriend', [
        "id_followed"=>1,
        "id_follower"=>111,
        "friends"=>true
    ]);
    $response -> assertStatus(200);
    $response -> assertJsonFragment(["response" => "Users not following each other."]);
    }
  
}
