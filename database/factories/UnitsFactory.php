<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User;
use App\Models\Units;
use App\Models\Taxonomy ;
use Faker\Generator as Faker;

$factory->define(Units::class, function (Faker $faker) {
    $res = [
        'user_id' => User::all()->random()->id ,
        'title' => $faker->text(rand(15,25)) ,
        'description' => $faker->text(rand(150,500)) ,
        'type' => Taxonomy::where('type' , 'category')->get()->random()->id,
        'child_type' => 18 ,
        'allow_childrens' => rand(0,1) ,
        'allow_infants' => rand(0,1) ,
        'allow_animals' => rand(0,1) ,
        'allow_extra' => rand(0,1) ,
        'guests' => rand(3,10) ,
        'rooms' => rand(2,8) ,
        'beds' => rand(4,12) ,
        'bathrooms' => rand(1,3)  ,
        'min_guests' => rand(5,10) ,
        'max_childrens' => rand(1,4) ,
        'min_days' => rand(3,5) ,
        'max_days' => rand(7,20) ,
        'longitude' => $faker->longitude($min = -180, $max = 180) ,
        'latitude' => $faker->latitude($min = -90, $max = 90),
        'area_city' => $faker->address ,
        'address' => $faker->streetAddress ,
        'building_number' => rand(1,5000) ,
        'unit_number' => rand(1,20) ,
        'floor_number' => rand(1,60) ,
        'bank_account' => $faker->name ,
        'bank_number' => $faker->creditCardNumber ,
        'fee' => rand(0,20) ,
        'checkin' => $faker->time('H:i:s') ,
        'checkout' => $faker->time('H:i:s') ,
        'deliver_keys' => $faker->text(50) ,
        'get_keys' => $faker->text(50) ,
        'notes' => $faker->text( rand(5,200) ) ,
        'contract_image' => 'http://lorempixel.com/800/1200' ,
        'cancle_policy' => Taxonomy::where('type' , 'policy')->get()->random()->id, 
        'status' => rand(0,10) 
    ];

    return $res ;
});
