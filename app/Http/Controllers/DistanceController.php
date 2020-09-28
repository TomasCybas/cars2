<?php

namespace App\Http\Controllers;

use App\Car;
use App\Spot;
use Illuminate\Http\Request;

class DistanceController extends Controller
{
    public function showDistance($carId) {


        $car = Car::find($carId);

        $imei = $car->tracker['imei'];

        $total_distance = 0;


        Spot::where('imei' , '=', $imei)->orderBy('time')->chunk(20000, function ($spots) use (&$total_distance) {

            $last_spot = $spots[0];
            $last_location = [];

            $last_location = $this->getLocation($last_spot);


            foreach ($spots as $spot) {
                $location = $this->getLocation($spot);
                //dump($location);
                //dump($last_location);
                if($location[0] == $last_location[0] && $location[1] == $last_location[1]) {
                    continue;
                }
                if($location[0] == null || $location[1] == null) {
                    continue;
                }
                $distance = $this->calculateDistance($last_location[1], $last_location[0], $location[1], $location[0]);
                //dump($distance);
                $total_distance = $total_distance + $distance;
                $last_location = $location;
            }
        });

        return response()->json([
            'distance' => round($total_distance,2) . ' km'
        ]);
    }


    /**
     * Calculates distance between two sets of coordinates using the Haversine formula.
     *
     * @param float $lat1  latitude of the origin location
     * @param float $long1 longtitude of the origin location
     * @param float $lat2 latitude of the destination location
     * @param float $long2 longtitude of the destination location
     * @param string $units *units to return: metric (km) or imperial (miles). Default: metric
     * @return float|int distance in km or miles between given points
     *
     */
    private function calculateDistance ($lat1, $long1, $lat2, $long2, $units = 'metric') {

        $earth_radius = 0;

        switch ($units) {
            case 'metric':
                $earth_radius = 6371;
                break;
            case 'imperial' :
                $earth_radius = 3959;
            break;
        }

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($long2 - $long1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return $d ;
    }

    /**
     * Checks if a spot has a location attribute and if not, combines lat and lng attributes to make sure the location array always follows the same pattern
     * @param Spot $spot
     * @return array set of coordinates
     */
    private function getLocation($spot) {
        if($spot->loc) {
            $location = $spot->loc;
        } else {
            $location = [(float)$spot->lng, (float)$spot->lat];
        }
        return $location;
    }
}
