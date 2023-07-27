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
            'id_followed'=>'required | exists:users,id',
            'friends' => 'required | boolean'
        ]);
        return $validation;
    }

    public function List()
    {
        return follows::all();
    }

    public function ListOne(follows $follows, $id)
    {
        return follows::findOrFail($id);
    }


    public function ListFollowers($id){
        return follows::all()->where("id_followed", $id);
    }

    public function ListFolloweds($id){
        return follows::all()->where("id_follower", $id);
    }

    public function ListFriends($id){
        return follows::all()->where("id_follower", $id)
                             ->where("friends", true);
    }

    public function FindFollow(request $request){
        $id_followed = $request ->post("id_followed");
        $id_follower = $request ->post("id_follower");
        $follow = follows::all()->where("id_follower", $id_follower)
                                ->where("id_followed", $id_followed);

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
        $Likes -> save();
        return $Likes;
    }

    public function UnFollow(request $request){
        $validation = self::FollowValidation($request);
        if ($validation->fails())
        return $validation->errors();

        $follow = FindFollow($request);

        $follow -> delete();
        return ["response" => "Object with ID $id Deleted"];
    }


    public function MakeFriend(request $request){
        $validation = self::FollowValidation($request);
        if ($validation->fails())
        return $validation->errors();

        $follow1 = FindFollow($request);
        $follow2 = FindFollow($request);

        if ($follow1->isEmpty() || follow2->isEmpty())
        return ["response" => "Error, se tienen que seguir mutuamente para ser amigos"];

        FriendRequest($follow1, $follow2, true);
    }

    public function UnFriend(request $request){
        $follow1 = FindFollow($request);
        $follow2 = FindFollow($request);

        FriendRequest($follow1, $follow2, false);
    }

    public function FriendRequest(follow $follow1, follow $follow2, $state){
        $follow1 -> friends = $state;
        $follow1 -> save();

        $follow2 -> friends = $state;
        $follow2 -> friends = save();
    }

   
}
