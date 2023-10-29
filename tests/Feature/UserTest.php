<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class UserTest extends TestCase
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
        $response = $this->get('/api/v1/user/1');
        
        $response -> assertStatus(200);
        $response->assertJsonStructure([
            "id",
            "name",
            "surname",
            "age",
            "gender",
            "email",
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


    public function test_RegisterGoodRequest(){
        $response = $this ->post('/api/v1/user', [
                "name"=> "Franco",
                "surname"=> "Fedullo",
                "age"=> 25,
                "email"=> "nashe@aasda111",
                "password"=> "nashe12345",
                "password_confirmation"=> "nashe12345",
        ]);
        $response -> assertStatus(201);
        $response -> assertJsonStructure([
                "name",
                "surname",
                "age",
                "email",
        ]);
        $this->assertDatabaseHas('users', [
            "name"=> "Franco",
            "surname"=> "Fedullo",
            "age"=> 25,
            "email"=> "nashe@aasda111",
        ]);
     
    }


        public function test_RegisterBadRequest(){
            
            $response = $this ->post('api/v1/user', [
                "name"=> "123",
                "surname"=> 22,
                "age"=> "aa",
                "email"=> "nash",
                "password"=> "na",
                "password_confirmation"=> "pa",
            ]);

            $response -> assertStatus(200);
            
            $response -> assertJsonFragment([
                    "name"=> ["The name must only contain letters."],
                    "surname"=> ["The surname must only contain letters."],
                    "age"=> ["The age must be an integer.","The age must be greater than or equal to 18."],
                    "email"=> ["The email must be a valid email address."],
                    "password"=>[ "The password must be at least 8  characters.",
                          "The password confirmation does not match." ],
            ]);
        }

        public function test_Register2GoodRequest(){
            
            $response = $this ->post('/api/v1/user/2', [
                
                    "gender"=> null,
                    "description"=>"a",
                    "homeland"=> 1,
                    "residence"=> 2
                
            ]);
            $response -> assertStatus(201);
           
            $response -> assertJsonStructure([
                '*' => [
                    "gender",
                    "description",
                    "homeland",
                    "residence"
                ]
            ]);
            $this->assertDatabaseHas('users', [
                "name"=> "usuario",
                "gender"=> null,
                "description"=>"a",
                "homeland"=> 1,
                "residence"=> 2
            ]);
        }
    
    
            public function test_Register2BadRequest(){
                $response = $this ->post('api/v1/user/2', [
                    "gender"=> 3,
                    "profile_pic"=> null,
                    "description"=>"aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                    "homeland"=> "paris",
                    "residence"=> 200000
                ]);
    
                $response -> assertStatus(200);
                $response -> assertJsonFragment([
                        "gender"=> ["The gender must only contain letters."],
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
    
        public function test_DeleteExisting(){
            $response = $this->delete('api/v1/user');
            $response -> assertStatus(200);
            $response -> assertJsonFragment(["response"=> "Object with ID 11 Deleted"]);
            $this->assertDatabaseMissing("users",[
                "id"=>"11",
                "deleted_at"=> null
            ]);
        }
}
