<?php

namespace App\Integration;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class GoogleMap
{
    public const BASE_URL = 'https://maps.googleapis.com/maps/api/place';

    public string $key;

    public function __construct()
    {
        $this->key = env('GOOGLE_MAP_API_KEY');
    }

    public function getDataFromAddressComponent(array $addressComponents, string $searchFor): ?string
    {
        foreach ($addressComponents as $address) {
            if (in_array($searchFor, $address['types'])) {
                return $address['long_name'];
            }
        }

        return null;
    }

    public function addressId(string $address)
    {
        $url = sprintf(
            '%s/autocomplete/json?%s',
            self::BASE_URL,
            http_build_query([
                'input' => $address,
                'types' => 'address',
                'key' => $this->key,
            ])
        );
        try {
            $client = new Client();
            $response = $client->request('get', $url);
            $responseJson = $response->getBody()->getContents();
            $responseArray = json_decode($responseJson, true);

            return response()->json(collect($responseArray['predictions'])->map(
                fn ($value) => [
                    'id' => $value['place_id'],
                    'label' => $value['description'],
                ]
            ));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addressBasedOnPlaceId(string $placeId)
    {
        $url = sprintf(
            '%s/details/json?%s',
            self::BASE_URL,
            http_build_query([
                'place_id' => $placeId,
                'key' => $this->key,
            ])
        );
        try {
            $client = new Client();
            $response = $client->request('get', $url);
            $responseJson = $response->getBody()->getContents();
            $responseArray = json_decode($responseJson, true);
            return [
                'streetNumber' => $this->getDataFromAddressComponent($responseArray['result']['address_components'], 'street_number'),
                'streetName' => $this->getDataFromAddressComponent($responseArray['result']['address_components'], 'route'),
                'locality' => $this->getDataFromAddressComponent($responseArray['result']['address_components'], 'locality'),
                'state' => $this->getDataFromAddressComponent($responseArray['result']['address_components'], 'administrative_area_level_1'),
                'area' => $this->getDataFromAddressComponent($responseArray['result']['address_components'], 'administrative_area_level_2'),
                'country' => $this->getDataFromAddressComponent($responseArray['result']['address_components'], 'country'),
                'postal_code' => $this->getDataFromAddressComponent($responseArray['result']['address_components'], 'postal_code'),
                'location' => $responseArray['result']['geometry']['location']
            ];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'exception' => get_class($e)], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAutocompleteLocations(string $input)
    {
        $client = new Client();
        $response = [];
        try {

            $url = sprintf(
                '%s/autocomplete/json?%s',
                self::BASE_URL,
                http_build_query([
                    'input' => $input,
                    'types' => 'address',
                    'key' => $this->key,
                ])
            );
            
            $client = new Client();
            $res = $client->request('get', $url);
            $response = json_decode($res->getBody(), true);

            if ($response['status'] === 'OK') {
                return $response['predictions'];
            } elseif ($response['status'] === 'ZERO_RESULTS') {
                return ['error' => 'No addresses found for the input.'];
            } elseif(isset($response['error_message'])) {
                if (!empty($response['error_message'])) {
                    return ['error' => $response['error_message']];
                }
            }
    
            return ['error' => 'An unexpected error occurred.'];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage(), 'exception' => get_class($e)];
        }

        return [];
    }

    public function getCoordinates($placeId) {
        try {
            
            $client = new Client();
            $url = self::BASE_URL."/details/json?place_id={$placeId}&key={$this->key}";
            return json_decode(file_get_contents($url), true);
            
        } catch (\Exception $e) {
            return ['error' => $e->getMessage(), 'exception' => get_class($e)];
        }
    }
}
