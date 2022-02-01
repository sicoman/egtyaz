<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Taxonomy;
use Faker\Generator as Faker;

$factory->define(Taxonomy::class, function (Faker $faker) {
    $res = [
        'type' => $faker->randomElement(['category' ,'city' , 'policy' , 'rest' , 'aminites' , 'views' , 'badge' , 'review_type']),
        'name' => '',
        'description' => $faker->text(200),
        'name_en' => '' ,
        'description_en' => $faker->text(200),
        'photo' => 'https://i.pravatar.cc/128' ,
        'parent' => null ,
        'status' => 1
    ];

    if($res['type'] == 'city'){
        $res['name'] = $res['name_en'] = $faker->city ;
    }else{
        $res['name'] = $res['name_en'] = $faker->name ;
    }

    return $res ;

});
