<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TravelTime extends Model
{
    protected $fillable = [
        'origin',
        'duration',
        'duration_in_traffic',
        'difference',
    ];

    static public function getDTO(
        string $origin,
        string $duration,
        string $in_traffic,
        float $difference
    ) {
        return self::make([
            'origin' => $origin,
            'duration' => $duration,
            'duration_in_traffic' => $in_traffic,
            'difference' => $difference,
        ]);
    }
}
