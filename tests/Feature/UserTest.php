<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
class UserTest extends TestCase
{
        private $clientId = 100;
        private $clientSecret = "wsBa0mp4jwSTYssUGHX5xoqD9IC0X95Gfpg0w3uY";

        private $userName = "usuario@email.com";
        private $userPassword = "12345678";


    public function test_ListOneThatExist(){
        $response = $this->get('/api/v1/user/1');
        
        $response -> assertStatus(200);
        $response->assertJsonStructure([
            "id",
            "name",
            "surname",
            "age",
            "gender",
            "email",
            "password",
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
                "email"=> "nashe@aasda111",
                "password"=> "nashe12345",
                "password_confirmation"=> "nashe12345",
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
                "email",
                "password",
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
            "email"=> "nashe@aasda111",
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
                "email"=> "nash",
                "password"=> "na",
                "password_confirmation"=> "pa",
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
                    "email"=> ["The email must be a valid email address."],
                    "password"=>[ "The password must be at least 8  characters.",
                          "The password confirmation does not match." ],
                    "description"=>["The description must not be greater than 255 characters."],
                    "homeland"=>["The homeland must be an integer."],
                    "residence"=> ["The selected residence is invalid."]
            ]);
        }

        public function test_loginGoodRequest(){
            $response = $this->post('/oauth/token',[
                "username" => $this -> userName,
                "password" => $this -> userPassword,
                "grant_type" => "password",
                "client_id" => $this -> clientId,
                "client_secret" => $this -> clientSecret
            ]);

            $response->assertStatus(200);

            $response->assertJsonStructure([
                "token_type",
                "expires_in",
                "access_token",
                "refresh_token"
            ]);

            $response->assertJsonFragment([
                "token_type" => "Bearer"
            ]);
        }
        public function test_loginBadRequest(){
            $response = $this->post('/oauth/token',[
                "grant_type" => "password",
                "client_id" => "234",
                "client_secret" => Str::Random(8)
            ]);

            $response->assertStatus(401);
    
            $response->assertJsonFragment([
                "error" => "invalid_client",
                "error_description" => "Client authentication failed",
                "message" => "Client authentication failed"
            ]);
        }

        public function test_ValidateNoToken(){    
            $response = $this->get('/api/v1/validate');

            $response->assertStatus(500);
        }

        public function test_ValidateBadRequest(){
            $response = $this->get('/api/v1/validate',[
                [ "Authorization" => "Bearer " . Str::Random(40)]
            ]);
    
            $response->assertStatus(500);
        }

        public function test_ValidateGoodRequest(){
            $tokenResponse = $this->post('/oauth/token',[
                "username" => $this -> userName,
                "password" => $this -> userPassword,
                "grant_type" => "password",
                "client_id" => $this -> clientId,
                "client_secret" => $this -> clientSecret
            ]);
    
            $token = json_decode($tokenResponse -> content(),true);
            
            $response = $this->get('/api/v1/validate',
                [ "Authorization" => "Bearer " . $token ['access_token']]
            );
    
            $response->assertStatus(200);
        }

        public function test_LogoutNoToken(){

        $response = $this->get('/api/v1/logout');

        $response->assertStatus(500);
        
        }

        public function test_LogoutBadRequest(){
        $response = $this->get('/api/v1/logout',[
            [ "Authorization" => "Bearer " . Str::Random(40)]
        ]);

        $response->assertStatus(500);
        }

        public function test_LogoutGoodRequest(){
            $tokenResponse = $this->post('/oauth/token',[
                "username" => $this -> userName,
                "password" => $this -> userPassword,
                "grant_type" => "password",
                "client_id" => $this -> clientId,
                "client_secret" => $this -> clientSecret
            ]);
    
            $token = json_decode($tokenResponse -> content(),true);
            
            $response = $this->get('/api/v1/logout',
                [ "Authorization" => "Bearer " . $token ['access_token']]
            );
    
            $response->assertStatus(200);
    
            $response->assertJsonFragment(
                ['message' => 'Logout succesful, token revoked']
            );
        }

        public function test_EditGoodRequest(){

            $response = $this ->put('/api/v1/user/1', [
                "name"=> "Francaaao",
                "surname"=> "Fedullasdasdo",
                "age"=> 3,
                "gender"=> "male",
                "email"=> "naaa@aaaa",
                "password"=> "nashe12345",
                "password_confirmation"=> "nashe12345",
                "profile_pic"=> "http://dummyimage.com/136x100.png/cc0000/ffffff",
                "description"=>"nashe",
                "homeland"=> 2,
                "residence"=> 23
        ]);
        $response -> assertStatus(200);
        $response -> assertJsonStructure([
                "name",
                "surname",
                "age",
                "gender",
                "email",
                "password",
                "profile_pic",
                "description",
                "homeland",
                "residence"
        ]);
        $this->assertDatabaseHas('users', [
            "name"=> "Francaaao",
            "surname"=> "Fedullasdasdo",
            "age"=> 3,
            "gender"=> "male",
            "email"=> "naaa@aaaa",
            "profile_pic"=> "http://dummyimage.com/136x100.png/cc0000/ffffff",
            "description"=>"nashe",
            "homeland"=> 2,
            "residence"=> 23
        ]);
        }

        public function test_EditBadRequest(){
            $response = $this ->put('api/v1/user/1', [
                "name"=> "1",
                "surname"=> 1,
                "age"=> "a",
                "gender"=> 11,
                "email"=> "usuario@email.com",
                "password"=> "franc",
                "password_confirmation"=> "aa",
                "profile_pic"=> "alguna",
                "description"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "homeland"=> "23a",
                "residence"=> "111111111111"
            ]);

            $response -> assertStatus(200);
            $response -> assertJsonFragment([
                    "name"=> ["The name must only contain letters."],
                    "surname"=> ["The surname must only contain letters."],
                    "age"=> ["The age must be an integer."],
                    "gender"=> ["The gender must only contain letters."],
                    "email"=> ["The email has already been taken."],
                    "password"=>[ "The password must be at least 8  characters.",
                          "The password confirmation does not match." ],
                    "description"=>["The description must not be greater than 255 characters."],
                    "homeland"=>["The homeland must be an integer."],
                    "residence"=> ["The selected residence is invalid."]
            ]);
        }
}
