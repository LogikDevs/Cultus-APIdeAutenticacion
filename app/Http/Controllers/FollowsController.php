<?php

namespace App\Http\Controllers;

use App\Models\follows;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class FollowsController extends Controller
{
    public function FollowValidation(request $request){
        $userId = auth()->id();
        $validation = Validator::make($request->all(), [
            'id_followed' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($userId) {
                    if ($value == $userId) {
                        $fail('The user cannot follow himself');
                    }
                },
                'unique_follow_relation:' . $userId . ',' . $request->input('id_followed'),
            ]
        ]);
        return $validation;
    }

    public function UnFollowValidation(request $request){
        $validation = Validator::make($request->all(),[
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
            'id_followed'=>'required | exists:users,id',
            'friends' => 'required | boolean'
        ]);
        return $validation;
    }


    public function ListFollowers(){
        $user = Auth::user();
        $id = $user->id;
        $follows = follows::all()->where("id_followed", $id);
        return $follows;
    }

    public function ListFolloweds(){
        $user = Auth::user();
        $id = $user->id;
        $follows =  follows::all()->where("id_follower", $id);
        return $follows;
    }

    public function ListFriends(){
        $user = Auth::user();
        $id = $user->id;
        return follows::all()->where("id_follower", $id)
                             ->where("friends", true);
                            
    }

    public function FindFollowFriends(request $request, int $id, $state){
    $userId1 = $request ->post("id_followed");
    $userId2 = $id;
    $follow1 = follows::where('id_follower', $userId1)
    ->where('id_followed', $userId2)
    ->first();

    $follow2 = follows::where('id_follower', $userId2)
    ->where('id_followed', $userId1)
    ->first();
    if ($follow1 && $follow2) {
    self::FriendRequest($follow1, $follow2, $state);
    return true;
    }
    return false;
    }

    public function FindFollow(request $request, int $id){
        $userId1 = $request ->post("id_followed");
        $userId2 = $id;
        return $follow = follows::where('id_follower', $userId1)
            ->where('id_followed', $userId2)
            ->first();
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
        $follows -> id_follower = auth()->id();
        $follows -> friends = false;
        $follows -> save();
        return $follows;
    }

    public function UnFollow(request $request){
        $validation = self::UnFollowValidation($request);
        if ($validation->fails())
        return $validation->errors();
        $userId = auth()->id();
        $follow = self::FindFollow($request, $userId);
        if (!$follow) 
            return ["response" => "No follow record found for the given conditions"];
        
            $follow->delete();
            return ["response" => "Object with IDfollowed $follow->id_followed Deleted"];
    }


    public function FollowEachOther($follow1, $follow2){
        if ($follow1 and $follow2){
            return true;
        }
            return false;
        }


    public function Friend(request $request){
        $user = Auth::user();
        $id = $user->id;
        $validation = self::FriendValidation($request);
        if ($validation->fails())
        return $validation->errors();
        $state = $request->post("friends");
        $follows = self::FindFollowFriends($request, $id, $state);
        if ($follows)
        return ["response" => "Change friend status to." . $state . "successfully"];

        return ["response" => "Users not following each other."];
    }

    public function FriendRequest(follows $follow1, follows $follow2, $state){
        $follow1->friends = $state;
        $follow2->friends = $state;
        $follow1->save();
        $follow2->save();
    }

   
}
