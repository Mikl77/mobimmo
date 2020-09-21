<?php
//Geocode base sur le Curl de data.gouv.fr
// Prend en compte le certificat du site data.gouv.fr. cf dossier certificat
//si erruer du geocode, la position du bien par defaut est celle du centre de la ville indique et stocke dans la table fr.postal.code


namespace App\Controllers\Geocoder;

class Geocoder
{

    /**
     * @var String
     */
    private $full_address;

    public function __construct(String $full_address)
    {
        $this->full_address = $full_address;
    }

    public function Geocode(){

        $estate_coordinates = [];

        $base_url = "https://api-adresse.data.gouv.fr/search/?q=";
        $query = $this->full_address;
        $url = $base_url . $query;
        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_CAINFO, __DIR__ . DIRECTORY_SEPARATOR.'certificat/cert.cer');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $data = curl_exec($curl);
        if($data === false){
            //var_dump(curl_error($curl));
            return false;
        }else{
            $data = json_decode($data, true);
            $compute_lat = ($data['features'][0]['geometry']['coordinates'][1]);
            $compute_lng = ($data['features'][0]['geometry']['coordinates'][0]);

            $estate_coordinates['lat'] = $compute_lat;
            $estate_coordinates['lng'] = $compute_lng;

            return $estate_coordinates;

        }
        curl_close($curl);

        return $data;
    }

}