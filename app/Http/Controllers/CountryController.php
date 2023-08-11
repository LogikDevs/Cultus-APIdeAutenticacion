<?php

namespace App\Http\Controllers;

use App\Models\country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function List()
    {
        return country::all();
    }

    public function ListOne(country $country, $id)
    {
        return country::findOrFail($id);
    }

}
