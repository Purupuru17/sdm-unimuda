<?php

class Location {
    
    protected $CI;
    
    function __construct() {
        $this->CI = get_instance();
    }
    function detectRadius($userLat, $userLng, $lokasi)
    {
        $candidates = [];

        foreach ($lokasi as $l) {
            $distance = $this->haversineDistance(
                $userLat,
                $userLng,
                $l['latitude'],
                $l['longitude']
            );
            // cek apakah masuk radius gedung tsb
            if ($distance <= $l['radius']) {
                $l['distance'] = $distance;
                $candidates[] = $l;
            }
        }
        // kalau tidak ada yg masuk radius
        if (empty($candidates)) {
            return null;
        }
        // kalau lebih dari 1 (overlap), ambil yg paling dekat
        usort($candidates, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
        return $candidates[0];
    }
    function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // meter
    }
}