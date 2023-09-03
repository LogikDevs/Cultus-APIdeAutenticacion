<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
class UserController extends Controller
{

    public function ListOne($id){
        $User = user::findOrFail($id);
        $User->makeHidden(['password']);
        return $User;
    }

    public function ListOnePost($id){
        $User = new user();
        $User = User::with(['homeland', 'residence'])->select('name', 'surname', 'age', 'homeland', 'residence')->findOrFail($id);
        return ($User);
    }
 

    public function ListOneProfile($id){
        $User = user::with(['homeland', 'residence'])->findOrFail($id);
        $User->makeHidden(['email']);
        $User->makeHidden(['password']);
        $User->interests = $User->interests()->get();
        return $User;
    }


    public function Register(Request $request){
        
        $validation = self::RegisterValidation($request);

        if ($validation->fails())
        return $validation->errors();
    
        return $this -> Registercreate($request);
    }
    public function Register2(Request $request){
        
        $validation = self::Register2Validation($request);

        if ($validation->fails())
        return $validation->errors();
        $user = $this -> ValidateToken($request);
        return $this -> Register2create($request, $user->id);
    }
    public function EditValidation(Request $request, $id){
        $validation = Validator::make($request->all(),[
            'name' => 'required | alpha:ascii ',
            'surname' => 'required | alpha:ascii',
            'age' => 'required | integer',
            'gender' => 'nullable | alpha',
            'email' => ['required', 'email',  Rule::unique('users')->ignore($id)],
            'password' =>'required | min:8 | confirmed',
             'profile_pic' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
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
            'email' => 'email | required | unique:users',
            'password' =>'required | min:8 | confirmed',
        ]);
        return $validation;    
    }

    public function RegisterCreate (Request $request){
        $User = new user();
        
        $User -> name = $request ->post("name"); 
        $User -> surname = $request ->post("surname");
        $User -> age = $request ->post("age");
        $User -> email = $request ->post("email");
        $User -> password = Hash::make($request -> post("password"));
        $User -> save();       
        return $User;
    }

    public function Register2Validation(Request $request){
        $validation = Validator::make($request->all(),[
            'gender' => 'nullable | alpha',
            'profile_pic' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'description' => 'nullable | max:255',
            'homeland' => ' nullable | integer | exists:country,id_country',
            'residence' => 'nullable | integer | exists:country,id_country'
        ]);
        return $validation;
    }
    public function Register2Create (Request $request, $id){
        $User = user::findOrFail($id);
        $User -> gender = $request ->post("gender");
        $User -> description = $request ->post("description");
        
        if ($request->hasFile('profile_pic')){
        $image = $request->file('profile_pic');
        $imageExtension = $image->getClientOriginalExtension();
        $path = $image->store('/public/profile_pic');
        $User -> profile_pic = basename($path);
        }
        $User -> homeland = $request ->post("homeland");
        $User -> residence = $request ->post("residence");
        $User -> save();       
        return response()->json([$User], 201);
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

    public function edit(Request $request){
        $user = $this -> ValidateToken($request);
        $id = $user -> id;
        
        $validation = self::EditValidation($request, $id);

        if ($validation->fails())
        return $validation->errors();
        
        return $this -> editRequest($request, $user);

    }

    public function editRequest(request $request, user $User ){ 
        $User -> name = $request ->post("name"); 
        $User -> surname = $request ->post("surname");
        $User -> age = $request ->post("age");
        $User -> gender = $request ->post("gender");
        $User -> email = $request ->post("email");
        $password = Hash::make($request -> post("password"));
        $User -> password = $password;

        if ($request->hasFile('profile_pic')){
            Storage::delete($User->profile_pic);
            $image = $request->file('profile_pic');
            $imageExtension = $image->getClientOriginalExtension();
            $path = $image->store('/public/profile_pic');
            $User -> profile_pic = basename($path);
            }
        
        $User -> description = $request ->post("description");
        $User -> homeland = $request ->post("homeland");
        $User -> residence = $request ->post("residence");     
        $User -> save();  
        return $User;

    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return ['message' => 'Logout succesful, token revoked'];   
    }

    public function delete(Request $request)
    {
        $User = $this-> ValidateToken($request);
        $User->delete(); 

        return ["response" => "Object with ID $User->id Deleted"];
        
    }
}
