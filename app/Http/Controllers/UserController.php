<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{

    public function foto(){

    }

    public function ListCountry($id){
        $user = User::find($id);
        return $homeland = $user->homeland()->get();
        $residence = $user->residence()->get();
    }

    public function List()
    {
        return user::all();
    }



    public function Register(Request $request){
        
        $validation = self::RegisterValidation($request);

        if ($validation->fails())
        return $validation->errors();
    
        return $this -> Registercreate($request);
    }
    public function EditValidation(Request $request, $id){
        $validation = Validator::make($request->all(),[
            'name' => 'required | alpha:ascii ',
            'surname' => 'required | alpha:ascii',
            'age' => 'required | integer',
            'gender' => 'nullable | alpha',
            'email' => ['required', 'email',  Rule::unique('users')->ignore($id)],
            'password' =>'required | min:8 | confirmed',
            'profile_pic' => 'nullable',
            'description' => 'nullable | max:255',
            'homeland' => ' nullable | integer | exists:country,id_country',
            'residence' => 'nullable | integer | exists:country,id_country'
        ]);
        return $validation;    
    }
    public function RegisterValidation(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required | alpha:ascii ',
            'surname' => 'required | alpha:ascii',
            'age' => 'required | integer',
            'gender' => 'nullable | alpha',
            'email' => 'email | required | unique:users',
            'password' =>'required | min:8 | confirmed',
            'profile_pic' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
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
        
        $User -> description = $request ->post("description");
        $User -> homeland = $request ->post("homeland");
        $User -> residence = $request ->post("residence");
        $User -> save();       
        return $User;
    }



    public function ValidateToken(Request $request){
        return auth('api')->user();
    }

    public function checkPassword(int $id, string $password){
        $user = user::find($id);
         if (Hash::check($password, $user->password)) {
            return false;
         }
         return true;
    }

    public function edit(Request $request, $id){
        $User = new user();
        $User = user::findOrFail($id);   
        $User -> name = $request ->post("name"); 
        $User -> surname = $request ->post("surname");
        $User -> age = $request ->post("age");
        $User -> gender = $request ->post("gender");
        $User -> email = $request ->post("email");
        $password = Hash::make($request -> post("password"));
        if (checkPassword())
        $User -> password = $password;

        if ($request->profile_pic)
        Storage::delete($User->profile_pic);
        $path = $request->profile_pic('avatar')->store('avatars');
        $User -> profile_pic = $path;
        
        $User -> description = $request ->post("description");
        $User -> homeland = $request ->post("homeland");
        $User -> residence = $request ->post("residence");     
        $User -> save();  
        return $User;

        return "Password can´t be the same";
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return ['message' => 'Logout succesful, token revoked'];   
    }

    public function delete(user $user, $id)
    {
        $User = user::findOrFail($id);
        $User->delete(); 

        return ["response" => "Object with ID $id Deleted"];
        
    }
}
