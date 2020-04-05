<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\TravelTime;

class GoogleMaps {
    private $api_key;
    private $destination;
    private $traffic_model;

    function __construct(
        string $api_key,
        string $destination,
        string $traffic_model = 'pessimistic'
    ) {
        $this->api_key = $api_key;
        $this->destination = $destination;
        $this->traffic_model = $traffic_model;
    }

    public function getTravelTimesFor(array $origins): array {
        $existing_origins = TravelTime::query()
            ->whereIn('origin', $origins)
            ->where('created_at', '>', Carbon::now()->subWeek())
            ->get();

        $not_found_origins = array_diff($origins, $existing_origins->pluck('origin')->toArray());

        if (count($not_found_origins)) {
            $new_entries = $this->getFromGoogle($not_found_origins);
            foreach($new_entries as $entry) {
                TravelTime::create($entry);
            }
        }

        return TravelTime::query()
            ->whereIn('origin', $origins)
            ->where('created_at', '>', Carbon::now()->subWeek())
            ->get()
            ->toArray();
    }

    protected function getFromGoogle(array $origins): array {
        $suffix = ',ZA';
        $origins_string = join($suffix . '|', $origins) . $suffix;

        $response = Http::get(
            sprintf(
                'https://maps.googleapis.com/maps/api/distancematrix/json?origins=%s&destinations=%s&departure_time=now&traffic_model=%s&key=%s',
                $origins_string,
                $this->destination,
                $this->traffic_model,
                $this->api_key,
            )
        );

        $response->throw();

        $formatted_response = $this->formatGoogleResponse($response->json());

        return $formatted_response;
    }

    protected function formatGoogleResponse(array $json): array {
        // we need to track index because for some silly reason laravel's reduce doesn't give it to us -_-
        $index = 0;
        $result = collect($json['rows'])->reduce(function (array $carry, array $row) use ($json, &$index) {
            // because google returns way more than just the suburb we're only going to keep the first part of the origin
            // also let's always deal with lower case strings to make comparisons easier
            $origin = strtolower(explode(',', $json['origin_addresses'][$index])[0]);

            $next = collect($row['elements'])->map(function ($element, $key) use ($origin) {
                $duration = $element['duration'];
                $in_traffic = $element['duration_in_traffic'];
                $difference = $this->getDurationDifference($duration['value'], $in_traffic['value']);

                $travel_time_dto = TravelTime::getDTO(
                    $origin,
                    $duration['text'],
                    $in_traffic['text'],
                    $difference,
                );

                return $travel_time_dto->toArray();
            })->toArray();

            // increment index since laravel refuses to include it in the reduce params
            $index++;
            return [...$carry, ...$next];
        }, []);

        return $result;
    }

    protected function getDurationDifference(int $without_traffic, int $traffic): float {
        return (float) (($traffic / $without_traffic) - 1) * 100;
    }
}
