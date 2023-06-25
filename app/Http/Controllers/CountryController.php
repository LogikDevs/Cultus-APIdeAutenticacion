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

    public function Create(request $request)
    {
        $Country = new country();

        $Country -> id_country = $request ->post("id_country");
        $Country -> country_name = $request ->post("country_name");
        $Country -> save();
        return $Country;
    }
}
