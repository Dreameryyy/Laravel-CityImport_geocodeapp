<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    public function autocomplete(Request $request)
    {
        $search = $request->get('term');

        $cities = City::where('name', 'LIKE', "%{$search}%")->get();

        $result = [];
        foreach ($cities as $city) {
            $result[] = [
                'id' => $city->id,
                'label' => $city->name,
                'value' => $city->name,
            ];
        }

        return response()->json($result);
    }
    public function index()
    {
        return view('cities.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
        ]);

        $city = City::where('name', 'like', '%' . $request->city . '%')->first();

        if ($city) {
            return redirect()->route('city.show', ['id' => $city->id]);
        } else {
            return redirect()->back()->with('error', 'Požadované mesto nebolo nájdené');
        }
    }

    public function show($id)
    {
        // Find the city by its ID
        $city = City::findOrFail($id);

        // Pass the city data to the view
        return view('cities.show', compact('city'));
    }


}
