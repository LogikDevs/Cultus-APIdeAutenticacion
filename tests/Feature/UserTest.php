<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_ListOneThatExist(){
        $response = $this->get('/api/v1/user/1');
        
        $response -> assertStatus(200);
        $response->assertJsonStructure([
            "id",
            "name",
            "surname",
            "age",
            "gender",
            "mail",
            "passwd",
            "profile_pic",
            "description",
            "homeland",
            "residence",
            "created_at",
            "updated_at",
            "deleted_at"
        ]);
        $response -> assertJsonFragment([
            "id"=> 1,
            "name"=> "Javonte",
            "surname"=> "Davis",
            "age"=> 55,
            "gender"=> "Female",
            "mail"=> "white.andres@example.com",
            "passwd"=> "$2y$10$Uk2OSlc15LylMdKUNQan7uNki84\/SuJo4dJ9TH7PdBUxSrDya9XxK",
            "profile_pic"=> "https:\/\/via.placeholder.com\/200x200.png\/003311?text=ullam",
            "description"=> "Natus consectetur et necessitatibus ex sunt.",
            "homeland"=> 101,
            "residence"=> 102,
            "created_at"=> "2023-06-26T14:48:54.000000Z",
            "updated_at"=> "2023-06-26T14:48:54.000000Z",
            "deleted_at"=> null
        ]);
    }

    public function test_ListOneThatDoesntExist(){
        $response = $this->get('/api/v1/user/1000');
        $response -> assertStatus(404);
    }

    public function test_DeleteExisting(){
        $response = $this->delete('api/v1/user/2' );
        $response -> assertStatus(200);
        $response -> assertJsonFragment(["response"=>"Object with ID 2 deleted"]);
        $this->assertDatabaseMissing("users",[
            "id"=>"2",
            "deleted_at"=> null
        ]);
    }

    public function test_DeleteNotExisting(){
        
    }
}
