<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{

    protected $table = 'courses';

    public $timestamps = true;

    protected $fillable = array( 'title', 'author_id', 'description', 'photo', 'file' , 'taxonomy_id', 'status', 'price');

    public function Category()
    {
        return $this->belongsTo('App\Models\Taxonomy', 'taxonomy_id');
    }

    public function Author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function Items(){
        return $this->hasMany('App\CourseItems' , 'course_id');
    }

    public function GetThumbsAttribute(){
        return str_replace( 'category/' , 'category/thumbs/' , $this->photo ) ;
    }

    public function getNewPriceAttribute(){
        $new_price = $this->attributes['price'] ;
 
        if( isset($this->attributes['sale_amount']) && $this->attributes['sale_amount'] > 0 ){
            if( $this->attributes['sale_type'] == 'percent' ){
                 $new_price = $new_price - (($new_price * $this->attributes['sale_amount'] ) / 100) ;
            }else{
                $new_price = $new_price - $this->attributes['sale_amount'] ;
            }
        }
 
 
        return  $this->attributes['new_price'] = $new_price ;
     }

     public function getPayPriceAttribute(){
        $new_price = $this->new_price;

        $payPrice = $new_price ; // xE( $new_price , env('currency_code' , 'SAR')) ;
 
        return  $this->attributes['pay_price'] = round($payPrice,2) ;
     }



}