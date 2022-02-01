<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Points ;
use App\User ;

class Payments extends Basic 
{

    protected $table = 'payments';

    public $timestamps = true;
    
    protected $fillable = array('gateway' ,'package_id', 'user_id', 'cost', 'is_paid', 'status','gateway_answer');

    public function Package()
    {
        return $this->belongsTo('App\Packages', 'package_id');
    }

    public function Course()
    {
        return $this->belongsTo('App\Courses', 'package_id');
    }

    public function User()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


    protected static function boot()
    {
        parent::boot();
        static::updated(function ($payment) {
            if($payment->status == 1 || $payment->is_paid == 1){
                // Get My User
                $userPayments = $payment->user->payments()->where('is_paid' , 1)->get() ;
                if( $userPayments->count() == 1 ){
                    $amount = $userPayments[0]->cost ;
                    $referer_id = User::where('code' , $payment->user->referer )->first()->id ?? 0 ;
                    if( $referer_id > 0) {
                        $data = [
                            'user_id' => $referer_id ,
                            'type' => 'deposit' ,
                            'points' => floor($amount) ,
                            'refrence_user' => $payment->user_id
                        ];
                        Points::create($data) ;

                        User::find($referer_id)->increment('points' , floor($amount) ) ;
                    }
                }
            }
        });
    }

}