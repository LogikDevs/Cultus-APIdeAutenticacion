<?php

namespace App\Http\Controllers;

use App\Models\follows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class FollowsController extends Controller
{
    public function FollowValidation(request $request){
        $validation = Validator::make($request->all(),[
            'id_follower'=>'required | exists:users,id',
            'id_followed'=>[
            'required',
            'exists:users,id',
            'different:id_follower',
            'unique_follow_relation:' . $request->input('id_follower') . ',' . $request->input('id_followed'),
            ]
        ]);
        return $validation;
    }

    public function UnFollowValidation(request $request){
        $validation = Validator::make($request->all(),[
            'id_follower'=>'required | exists:users,id',
            'id_followed'=>[
            'required',
            'exists:users,id',
            'different:id_follower',
            ]
        ]);
        return $validation;
    }

    public function FriendValidation(request $request){
        $validation = Validator::make($request->all(),[
            'id_follower'=>'required | exists:users,id',
            'id_followed'=>'required | exists:users,id',
            'friends' => 'required | boolean'
        ]);
        return $validation;
    }


    public function ListFollowers($id){
        return follows::all()->where("id_followed", $id);
    }

    public function ListFolloweds($id){
        return follows::all()->where("id_follower", $id);
    }

    public function ListFriends($id){
        follows::where("id_follower", $id)->firstOrFail();
        return follows::all()->where("id_follower", $id)
                             ->where("friends", true);
                            
    }

    public function FindFollow(request $request){
        $id_followed = $request ->post("id_followed");
        $id_follower = $request ->post("id_follower");
        $follow = follows::where("id_follower", $id_follower)
                                ->where("id_followed", $id_followed)
                                ->first();

        return $follow;
    }

    public function Follow(request $request){
        $validation = self::FollowValidation($request);
        if ($validation->fails())
        return $validation->errors();
        
        return $this -> FollowRequest($request);
    }

    public function FollowRequest(request $request)
    {
        $follows = new follows();

        $follows -> id_followed = $request ->post("id_followed");
        $follows -> id_follower = $request ->post("id_follower");
        $follows -> friends = false;
        $follows -> save();
        return $follows;
    }

    public function UnFollow(request $request){
        $validation = self::UnFollowValidation($request);
        if ($validation->fails())
        return $validation->errors();

        $follow = self::FindFollow($request);
        
        if (!$follow) 
            return ["response" => "No follow record found for the given conditions"];
        
            $follow->delete();
            return ["response" => "Object with IDfollowed $follow->id_followed Deleted"];
    }


    public function FollowEachOther($follow1, $follow2){
        if ($follow1 and $follow2){
            return true;
        }
         else { 
            return false;
         }
    }


    public function MakeFriend(request $request){
        $validation = self::FriendValidation($request);
        if ($validation->fails())
        return $validation->errors();

        $follow1 = self::FindFollow($request);
        $request->merge([
            "id_follower" => $request->post("id_followed"),
            "id_followed" => $request->post("id_follower"),
        ]);
        $follow2 = self::FindFollow($request);

        if (self::FollowEachOther($follow1, $follow2))
        return ["response" => "Friends created succesfully."];

        return ["response" => "Users not following each other."];
    }

    public function UnFriend(request $request){
        $validation = self::FriendValidation($request);
        if ($validation->fails())
        return $validation->errors();
        
        $follow1 = self::FindFollow($request);
        $request->merge([
            "id_follower" => $request->post("id_followed"),
            "id_followed" => $request->post("id_follower"),
        ]);
        $follow2 = self::FindFollow($request);

        if (self::FollowEachOther($follow1, $follow2)){
    
         if($follow1->friends and $follow2->friends){
            self::FriendRequest($follow1, $follow2, false);
            return ["response" => "Friends eliminated succesfully."];
            }
            else {
            return ["response" => "Users are not friends."];
            }
        }
        else {
            return ["response" => "Users not following each other."];
        }
    }

    public function FriendRequest(follows $follow1, follows $follow2, $state){
        $follow1 -> friends = $state;
        $follow1 -> save();

        $follow2 -> friends = $state;
        $follow2 -> save();

      
    }

   
}
