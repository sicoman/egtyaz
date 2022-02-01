<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Taxonomy ;
use App\Models\Posts ;

class Options extends Basic 
{

    protected $table = 'options';
    public $timestamps = true;
    protected $fillable = array('type', 'option_var', 'option_value', 'html', 'status', 'description', 'description_en');

    public function options(){
        if( $this->html == 'callback' and method_exists( $this , $this->type.'_'.$this->option_var.'_callback' ) ) {
            return $this->{$this->option_var.'_callback'}() ; 
         }elseif( ($this->html == 'select' || $this->html == 'multi_select') and method_exists( $this , $this->type.'_'.$this->option_var.'_callback' ) ) {
                return $this->{$this->type.'_'.$this->option_var.'_callback'}() ; 
         }elseif( $this->html == 'callback' and method_exists( $this , $this->type.'___callback' ) ) {
            return $this->{$this->option_type.'___callback'}() ; 
         }elseif( ($this->html == 'select' || $this->html == 'multi_select') and method_exists( $this , $this->type.'___callback' ) ) {
                return $this->{$this->type.'___callback'}() ; 
         }elseif( $this->html == 'yesno' ){
            return [ 'no' => 'No' , 'yes' => 'Yes' ] ;
         }else{
             return false;
         }
    }

    /*
     Callback example
    */
    public static function facebook_callback(){
        return 'facebook' ;
    }

    public static function getPages($type , $pluck = ''){
        return Posts::where('type', $type )->where('status' , 1)->pluck('title','id') ;
    }

    public static function getTaxonomies($type , $pluck = ''){
        return Taxonomy::where('type', $type )->where('status' , 1)->selectRaw('name as title , id')->pluck('title','id') ;
    }

    public static function about_pages_callback(){
        return self::getPages( 'page' ) ;
    }

    public static function homepage_first_slider_callback(){
        return self::getPages( 'pages' ) ;
    }
    public static function homepage_second_slider_callback(){
        return self::getPages( 'pages' ) ;
    }

    public static function homepage_six_pages_callback(){
        return self::getPages( 'pages' ) ;
    }

    public static function homepage_what_we_produce_callback(){
        return self::getPages( 'pages' ) ;
    }

    

    public static function about_team_callback(){
        return self::getPages( 'team' ) ;
    }

    public static function checkin_guest_callback(){
            return self::getPages( 'checkin' ) ;
    }

        public static function checkin_host_callback(){
            return self::getPages( 'checkin' ) ;
        }

        public static function contact_categories_callback(){
            return self::getTaxonomies( 'faq' ) ;
        }

        public static function contact_6_cats_callback(){
            return self::getTaxonomies( 'faq' ) ;
        }

        public static function contact_3_pages_callback(){
            return self::getPages( 'contact' ) ;
        }

        public static function payment_methods_payin_callback(){
            return self::getPages( 'payment_methods' ) ;
        }

        public static function payment_methods_payout_callback(){
            return self::getPages( 'payment_methods' ) ;
        }

        public static function contact_form_cats_callback(){
            return self::getTaxonomies( 'contact' ) ;
        }

        public static function faq_categories_callback(){
            return self::getTaxonomies( 'faq' ) ;
        }

        public static function faq_6_cats_callback(){
            return self::getTaxonomies( 'faq' ) ;
        }

        public static function how_work_pages_callback(){
            return self::getPages( 'how_work' ) ;
        }

        public static function how_work_overview_callback(){
            return self::getPages( 'how_work' ) ;
        }

        public static function how_work_as_guest_callback(){
            return self::getPages( 'how_work' ) ;
        }

        public static function how_work_as_host_callback(){
            return self::getPages( 'how_work' ) ;
        }

        public static function how_work_saftey_callback(){
            return self::getPages( 'how_work' ) ;
        }

        public static function partiners_gold_callback(){
            return self::getPages( 'partiners' ) ;
        }

        public static function partiners_silver_callback(){
            return self::getPages( 'partiners' ) ;
        }

        public static function partiners_bronze_callback(){
            return self::getPages( 'partiners' ) ;
        }

        public static function news_featured_news_callback(){
            return self::getPages( 'news' ) ;
        }

        public static function news_categories_callback(){
            return self::getTaxonomies( 'news' ) ;
        }

        public static function trust_pages_callback(){
            return self::getPages( 'trust' ) ;
        }

        public static function trust_one_page_callback(){
            return self::getPages( 'trust' ) ;
        }

        public static function why_ezuru_pages_callback(){
            return self::getPages( 'why_ezuru' ) ;
        }

        public static function why_ezuru_pages2_callback(){
            return self::getPages( 'why_ezuru' ) ;
        }


        public static function cancle_pages_callback(){
            return self::getPages( 'cancle' ) ;
        }
        public static function cancle_terms_callback(){
            return self::getPages( 'cancle' ) ;
        }

        public static function careers_categories_callback(){
            return self::getTaxonomies( 'careers' ) ;
        }

        public static function careers_jobs_callback(){
            return self::getPages( 'careers' ) ;
        }

        public static function notifications_via___callback(){
            return ['mail' =>"Email" , 'sms' => "SMS" , 'database' => "Pushed"] ;
        }

        public static function admin_notifications_via___callback(){
            return ['mail' =>"Email" , 'sms' => "SMS" , 'database' => "Pushed"] ;
        }

        public static function dashboard_dash_blocks_callback(){
            return self::getPages( 'pages' ) ;
        }

    }