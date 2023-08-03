<?php

namespace App\Http\Controllers;

use App\Models\likes;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class LikesController extends Controller
{
    public function List()
    {
        return likes::all();
    }

    public function ListUserInterest($id){
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User Not found'], 404);
        }
    
        $interests = $user->interests()->get();

    return response()->json(['interests' => $interests], 200);
    }

    public function ListInterestUsers($id){
        return likes::all()->where("id_interest",$id);
    }

    public function ListOne(likes $likes, $id)
    {
        return likes::findOrFail($id);
    }

    public function Create(request $request){
        $validation = self::CreateValidation($request);
        if ($validation->fails())
        return $validation->errors();
    
        return $this -> CreateRequest($request);
    }

    public function CreateValidation(request $request){
        $validation = Validator::make($request->all(),[
            'id_interest'=>'required | exists:interest_label,id_label',
            'id_user'=>'required | exists:users,id' 
        ]);
        return $validation;
    }

    public function CreateRequest(request $request)
    {
        $Likes = new likes();

        $Likes -> id_interest = $request ->post("id_interest");
        $Likes -> id_user = $request ->post("id_user");
        $Likes -> save();
        return $Likes;
    }

    public function delete(likes $likes, $id)
    {
        $Likes = likes::findOrFail($id);
        $Likes->delete(); 

        return ["response" => "Object with ID $id Deleted"];
        
    }

}