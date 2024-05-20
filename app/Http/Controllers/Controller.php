<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

abstract class Controller
{
    public function getUsersByCountry(Request $request)
    {
        $countryName = $request->input('country', 'Canada');

        // Get the country by name
        $country = Country::where('name', $countryName)->firstOrFail();

        // Get companies in that country
        $companies = $country->companies;

        // Get users associated with those companies and eager load the company relationships
        $users = User::whereHas('companies', function($query) use ($companies) {
            $query->whereIn('companies.id', $companies->pluck('id'));
        })->with(['companies' => function($query) use ($companies) {
            $query->whereIn('companies.id', $companies->pluck('id'))->withPivot('connected_at');
        }])->get();

        return response()->json($users);
    }
}
