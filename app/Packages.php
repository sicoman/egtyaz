<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    protected $guarded = ['id'];

    public $appends = ['roles_list'] ;
    
    public function getNewPriceAttribute(){
        $new_price = $this->attributes['price'] ;
 
        if( $this->attributes['sale_amount'] > 0 ){
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

        $payPrice = xE( $new_price , env('currency_code' , 'SAR')) ;
 
        return  $this->attributes['pay_price'] = round($payPrice,2) ;
    }

    public function getRolesListAttribute(){
        if( !isset($this->roles) ){
            return [] ;
        }
        if( is_array( $this->roles ) || is_object( $this->roles ) ) {
            return $this->roles ;    
        }else{
            return json_decode($this->roles) ;
        }
    }


    public static function PackgeRoles(){
        return [
            'bank' => 'بنك الأسئلة' ,
            'mock_exam' =>  'اختبار قدرات' ,
            'free_exam' => 'الاختبارات التجريبية' ,
            'videos' => 'الفيديوهات التعليمية' ,
            'foundation' => 'التأسيس والمهارات' ,
            'challenges' => 'المسابقات' ,
            'comunity' => 'مجتمع المنصة' ,
            'courses' =>  'الدورات المتقدمة' ,
            'ask_teacher' => 'اسأل معلم' 
        ];
    }
    
}
