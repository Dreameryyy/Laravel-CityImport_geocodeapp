<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\City;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodeCities extends Command
{
    protected $signature = 'data:geocode';
    protected $description = 'Geocode city data';

    public function handle()
    {
        // Fetch cities that do not have latitude and longitude data
        $cities = City::whereNull('latitude')->orWhereNull('longitude')->get();

        if ($cities->isEmpty()) {
            $this->info('All cities have been geocoded already.');
            return;
        }

        foreach ($cities as $city) {
            $this->geocodeCity($city);
        }

        $this->info('Cities geocoded successfully.');
    }

    private function geocodeCity(City $city)
    {
        $address = urlencode($city->city_hall_address);
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        if (!$apiKey) {
            $this->error('Google Maps API key is not set in the environment file.');
            return;
        }

        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['results'][0]['geometry']['location'])) {
                    $location = $data['results'][0]['geometry']['location'];
                    $city->latitude = $location['lat'];
                    $city->longitude = $location['lng'];
                    $city->save();

                    $this->info("Geocoded: {$city->name} (Lat: {$city->latitude}, Lng: {$city->longitude})");
                } else {
                    $this->warn("No location found for: {$city->name}. Please check the address: {$city->city_hall_address}");
                }
            } else {
                $this->error("Failed to geocode: {$city->name}. HTTP status code: " . $response->status());
            }
        } catch (\Exception $e) {
            $this->error("An error occurred while geocoding: {$city->name}");
        }
    }
}
