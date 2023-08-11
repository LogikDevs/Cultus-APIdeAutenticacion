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
}
