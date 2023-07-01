<?php

namespace App\Http\Controllers;


use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
 
    public function List()
    {
        return user::all();
    }

    public function ListOne(user $user, $id){
        return user::findOrFail($id);
    }


    public function Register(Request $request){
        
        $validation = self::RegisterValidation($request);

        if ($validation->fails())
        return $validation->errors();
    
        return $this -> Registercreate($request);
    }
    public function RegisterValidation(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required | alpha:ascii ',
            'surname' => 'required | alpha:ascii',
            'age' => 'required | integer',
            'gender' => 'nullable | alpha',
            'email' => 'email | required | unique:users',
            'password' =>'required | min:8 | confirmed',
            'profile_pic' => 'nullable',
            'description' => 'nullable | max:255',
            'homeland' => ' nullable | integer | exists:country,id_country',
            'residence' => 'nullable | integer | exists:country,id_country'
        ]);
        return $validation;    
    }
    public function RegisterCreate (Request $request){
        $User = new user();
        
        $User -> name = $request ->post("name"); 
        $User -> surname = $request ->post("surname");
        $User -> age = $request ->post("age");
        $User -> gender = $request ->post("gender");
        $User -> email = $request ->post("email");
        $User -> password = Hash::make($request -> post("password"));
        $User -> profile_pic = $request ->post("profile_pic");
        $User -> description = $request ->post("description");
        $User -> homeland = $request ->post("homeland");
        $User -> residence = $request ->post("residence");
        $User -> save();       
        return $User;
    }

    public function ValidateToken(Request $request){
        return auth('api')->user();
    }


    public function edit(user $user, $id){
        $User = user::findOrFail($id);
        $User -> name = $request ->post("name"); 
        $User -> surname = $request ->post("surname");
        $User -> age = $request ->post("age");
        $User -> gender = $request ->post("gender");
        $User -> email = $request ->post("email");
        $User -> password = $request ->post("password");
        $User -> description = $request ->post("description");
        $User -> homeland = $request ->post("homeland");
        $User -> residence = $request ->post("residence");
        
        $User -> save();  

        return $User;
    }

    public function delete(user $user, $id)
    {
        $User = user::findOrFail($id);
        $User->delete(); 

        return ["response" => "Object with ID $id Deleted"];
        
    }
}
