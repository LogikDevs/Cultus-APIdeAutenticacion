<?php

namespace App\Http\Controllers;

use App\Models\likes;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class LikesController extends Controller
{

    public function ListUserInterest($id){
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User Not found'], 404);
        }
    
        $interests = $user->interests()->get();

    return response()->json(['interests' => $interests], 200);
    }

    public function ListOtherUserInterest($id){
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

    public function Create(request $request){
        $validation = self::CreateValidation($request);
        if ($validation->fails()){
        return $validation->errors();
        }
        $user = (new UserController)->ValidateToken($request);
        $id_user = $user -> id;
        return $this -> CreateRequest($request, $id_user);
    }

    public function CreateValidation(request $request){
        $validation = Validator::make($request->all(),[
            'id_interest'=>'required | exists:interest_label,id_label',
        ]);
        return $validation;
    }

    public function CreateRequest(request $request, int $id_user)
    {
        $Likes = new likes();

        $Likes -> id_interest = $request ->post("id_interest");
        $Likes -> id_user = $id_user;
        $Likes -> save();
        return $Likes;
    }

    public function delete($id_user, $id_interest)
    {
        $Likes = likes::where("id_interest", $id_interest)
                        ->where("id_user", $id_user)
                        ->first();
                                
        if ($Likes){
        $Likes->delete(); 
        return ["response" => "Object Deleted"];
        }

        return response()->json(['response' => 'Object not found'], 404);
        
    }

}