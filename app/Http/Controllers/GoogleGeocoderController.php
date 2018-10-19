<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class GoogleGeocoderController extends Controller
{
    public static function getGeocode($param){
// $param = array("address"=>"76 Buckingham Palace Road London SW1W 9TQ");
        $response = \Geocoder::geocode('json', $param);
        return json_decode($response, true);
    }

    public static function setGeocode($id)
    {
        $client = Client::where('id', $id)->with('cities')->first();
        $street = $client->address;
        $city = $client['cities']->name;
        $country = 'Armenia';
        $address = $street . ' ' . $city . ' ' . $country;
        $param = ["address" => $address];
        $geocode = self::getGeocode($param);
        if($geocode['status'] !== "ZERO_RESULTS"){
            $geocode = $geocode['results'][0]['geometry']['location'];
            $client->lat = $geocode['lat'];
            $client->lng = $geocode['lng'];
            $client->save();
            return $client;
        }
        else return false;
    }
}
