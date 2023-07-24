<?php
//para el gonza
namespace App\Http\Controllers;

use App\Models\interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{

    public function List()
    {
        return interest::all();
    }

    public function ListOne(interest $interest, $id)
    {
        return interest::findOrFail($id);
    }

    public function Create(request $request)
    {
        $Interest = new interest();

        $Interest -> id_label = $request ->post("id_label");
        $Interest -> interest = $request ->post("interest");
        $Interest -> save();
        return $Interest;
    }

    

}
