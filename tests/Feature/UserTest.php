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
            "deleted_at"=> null
        ]);
        /*
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
        */
    }

    public function test_ListOneThatDoesntExist(){
        $response = $this->get('/api/v1/user/1000');
        $response -> assertStatus(404);
    }

    public function test_DeleteExisting(){
        $response = $this->delete('api/v1/user/2' );
        $response -> assertStatus(200);
        $response -> assertJsonFragment(["response"=> "Object with ID 2 Deleted"]);
        $this->assertDatabaseMissing("users",[
            "id"=>"2",
            "deleted_at"=> null
        ]);
    }

    public function test_DeleteNotExisting(){
        $response = $this->delete('api/v1/user/1000');
        $response -> assertStatus(404);
    }

    public function test_RegisterGoodRequest(){
        $response = $this ->post('/api/v1/user', [
                "name"=> "Franco",
                "surname"=> "Fedullo",
                "age"=> 25,
                "gender"=> null,
                "mail"=> "nashe@aasda111",
                "passwd"=> "nashe12345",
                "passwd_confirmation"=> "nashe12345",
                "profile_pic"=> "http://dummyimage.com/136x100.png/cc0000/ffffff",
                "description"=>null,
                "homeland"=> 1,
                "residence"=> 2
        ]);
        $response -> assertStatus(201);
        $response -> assertJsonStructure([
                "name",
                "surname",
                "age",
                "gender",
                "mail",
                "passwd",
                "profile_pic",
                "description",
                "homeland",
                "residence"
        ]);
        $this->assertDatabaseHas('users', [
            "name"=> "Franco",
            "surname"=> "Fedullo",
            "age"=> 25,
            "gender"=> null,
            "mail"=> "nashe@aasda111",
            "profile_pic"=> "http://dummyimage.com/136x100.png/cc0000/ffffff",
            "description"=>null,
            "homeland"=> 1,
            "residence"=> 2
        ]);
    }


        public function test_RegisterBadRequest(){
            $response = $this ->post('api/v1/user', [
                "name"=> "123",
                "surname"=> 22,
                "age"=> "aa",
                "gender"=> 3,
                "mail"=> "nash",
                "passwd"=> "na",
                "passwd_confirmation"=> "pa",
                "profile_pic"=> null,
                "description"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "homeland"=> "paris",
                "residence"=> 200000
            ]);

            $response -> assertStatus(200);
            $response -> assertJsonFragment([
                    "name"=> ["The name must only contain letters."],
                    "surname"=> ["The surname must only contain letters."],
                    "age"=> ["The age must be an integer."],
                    "gender"=> ["The gender must only contain letters."],
                    "mail"=> ["The mail must be a valid email address."],
                    "passwd"=>[ "The passwd must be at least 8  characters.",
                          "The passwd confirmation does not match." ],
                    "description"=>["The description must not be greater than 255 characters."],
                    "homeland"=>["The homeland must be an integer."],
                    "residence"=> ["The selected residence is invalid."]
            ]);
        }
}
