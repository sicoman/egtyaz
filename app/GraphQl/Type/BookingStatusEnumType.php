<?php


namespace App\GraphQL\Type;

use Rebing\GraphQL\Support\Type as GraphQLType;

class BookingStatusEnumType extends GraphQLType {
  
    protected $enumObject = true;
    protected $attributes = [
        'name' => 'BookingStatusEnum',
        'description' => 'BookingStatusEnum Description sa',
        'values' => [
            'AWAITING_APPROVAL' => 0, 
            'APPROVED' => 1,
            'PAID' => 2,  //system
            'CHECKIN' => 3, //system
            'CHECKOUT' => 4, //system 
            'KEY_DELIVERED' => 5 , // owner
            'CONFIRM_KEY_DELIVERED' => 6 , // guest
            'CLOSED' => -1,  //system
            'CANCELED'=> -2, //client ask  cross state
            'CANCELED_ACCEPTANCE'=> -3, // host answered and canceled
            'REJECTED'=> -4 , // reject a client booking request 
            'AWAITING_PAYMENT' => -5
        ],
    ];

}
