<?php


namespace App\App;


use App\Models\Estate;
use App\Models\In_Charge;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Faker\Factory;

class FakerInterface
{
    //permet de creer des biens fake
    public function GenerateFake($key,$number){

        $faker = Factory::create('fr_FR');
        for($i= 0; $i<$number; $i++) {

            // genere des biens
            if ($key === 'estates') {
                $ref = reference_generator(4);

                Estate::create([
                    'estate_name' => $faker->words(3, true),
                    'estate_address' => $faker->streetAddress,
                    'estate_town' => $faker->city,
                    'estate_town_id'=>$faker->numberBetween(1,39192),
                    'estate_postal_code' => $faker->postcode,
                    'estate_type_id' => $faker->numberBetween(1, 3),
                    'current_price'=>$faker->numberBetween(90000,3000000),
                    'current_rent'=>$faker->numberBetween(450,2300),
                    'floor_space' => $faker->numberBetween(20, 350),
                    'number_of_rooms' => $faker->numberBetween(1, 5),
                    'balconies_space' => $faker->numberBetween(5, 30),
                    'number_of_bedrooms' => $faker->numberBetween(0, 3),
                    'number_of_bathrooms' => $faker->numberBetween(0, 3),
                    'number_of_garages' => Null,
                    'parking_type_id' => $faker->numberBetween(1, 3),
                    'pets_allowed' => Null,
                    'estate_description' => $faker->sentence(3, true),
                    'estate_status_id' => $faker->numberBetween(1, 4),
                    'estate_location_id' => Null,
                    'estate_main_picture_filename' => 'default',
                    'estate_reference' => $ref,
                    'estate_lat' => $faker->latitude(42.5,48.5),
                    'estate_lng' => $faker->longitude(-2,5)
                ]);
            }
            if ($key === 'users'){
                Sentinel::register(array(
                    'email'    => $faker->email,
                    'password' => 'password',
                    'first_name'=>$faker->firstName,
                    'last_name'=>$faker->lastName
                ));
            }
            if ($key === 'in_charge'){
                In_Charge::create([
                    'estate_id'=>$faker->numberBetween(6, 100),
                    'user_id'=>$faker->numberBetween(1,102 ),
                    'date_from'=>$faker->dateTime()
                ]);
            }

        }
    }

}