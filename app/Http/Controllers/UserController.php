<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return user::all();
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $User = new user();
        
        $User -> name = $request ->post("nombre"); 
        $User -> surname = $request ->post("surname");
        $User -> age = $request ->post("age");
        $User -> gender = $request ->post("gender");
        $User -> mail = $request ->post("mail");
        $User -> passwd = $request ->post("passwd");
        $User -> description = $request ->post("description");
        $User -> homeland = $request ->post("homeland");
        $User -> residence = $request ->post("residence");
        
        $User -> save();       
        return $User;
    }



    /**
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user, $id)
    {
        return user::findOrFail($id);
    }

    /**
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(user $user, $id)
    {
        $User = user::findOrFail($id);
        $User -> name = $request ->post("nombre"); 
        $User -> surname = $request ->post("surname");
        $User -> age = $request ->post("age");
        $User -> gender = $request ->post("gender");
        $User -> mail = $request ->post("mail");
        $User -> passwd = $request ->post("passwd");
        $User -> description = $request ->post("description");
        $User -> homeland = $request ->post("homeland");
        $User -> residence = $request ->post("residence");
        
        $User -> save();  

        return $User;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {
        //
    }

    /**
     * 
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user, $id)
    {
        $User = user::findOrFail($id);
        $User->delete(); 

        return ["response" => "Object with ID $id Deleted"];
        
    }
}
