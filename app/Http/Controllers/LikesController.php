<?php

namespace App\Http\Controllers;

use App\Models\likes;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function List()
    {
        return likes::all();
    }

    public function ListUserInterest($id){
        return likes::all()->where("id_user", $id);
    }

    public function ListOne(likes $likes, $id)
    {
        return likes::findOrFail($id);
    }

    public function Create(request $request)
    {
        $Likes = new likes();

        $Likes -> id_interest = $request ->post("id_interest");
        $Likes -> id_user = $request ->post("id_user");
        $Likes -> save();
        return $Likes;
    }

}