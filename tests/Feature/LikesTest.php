<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikesTest extends TestCase
{
   
    public function test_ListUserInterestGoodRequest(){
        $response = $this->get('api/v1/likes/user/1');
        $response -> assertStatus(200);
    }

    public function test_ListUserInterestBadRequest(){
        $response = $this->get('api/v1/likes/user/');
        $response -> assertStatus(404);
    }

    public function test_ListInterestUserGoodRequest(){
        $response = $this->get('api/v1/likes/interest/1');
        $response -> assertStatus(200);
    }

    public function test_ListInterestUserBadRequest(){
        $response = $this->get('api/v1/likes/interest/');
        $response -> assertStatus(404);
    }


    public function test_CreateGoodRequest(){
        $response = $this->post('api/v1/likes/', [
            "id_interest"=>1,
            "id_user"=>1
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
            "id_interest"=> 1,
            "id_user"=>1
        ]);
    }

    public function test_CreateBadRequest(){
        $response = $this->post('api/v1/likes/', [
            "id_interest"=>"aa",
            "id_user"=>"bb"
        ]);
        $response -> assertStatus(200);
        $response -> assertJsonFragment([
            "id_interest"=>["The selected id interest is invalid."],
            "id_user"=>["The selected id user is invalid."]
        ]);
    }

    public function test_DeleteGoodRequest(){
        $response = $this->delete('api/v1/likes/delete/1' );
        $response -> assertStatus(200);
        $response -> assertJsonFragment(["response"=> "Object with ID 1 Deleted"]);
        $this->assertDatabaseMissing("likes",[
            "id"=>"1",
            "deleted_at"=> null
        ]);
    }

    public function test_DeleteBadRequest(){
        $response = $this->delete('api/v1/likes/delete/10000');
        $response -> assertStatus(404);
    }
}
