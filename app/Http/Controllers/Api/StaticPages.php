<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Options ;
use App\Models\Posts ;

use App\Models\Taxonomy;

use App\Mail\Contact ;
use App\Mail\Job  ;
use Illuminate\Support\Facades\Mail;


class StaticPages extends Controller
{

    /*
     *  contact
     *  why_ezuru
     *  payments
     *  terms
     *  policy
     *  how_work
     *  trust_saftey
     *  news
     *  partnerships
     *  faq
     *  about
     */



    public function handle($page = 'contact' , Request $request){
         if( method_exists( $this , 'c_p_'.$page ) ){
             return $this->{'c_p_'.$page}($request) ;
         }else{
             return ['error' => 404 , 'msg' => 'page not found'] ;
         }
    }

    public function c_p_about($request){
        $settings = Options::where('type' , 'about')->pluck('option_value' , 'option_var');

        $pages = Posts::whereIn( 'id' , (array) json_decode($settings['pages']) ) ->get();

        $settings['pages'] = $pages ;

        if(  $settings['show_team'] == 'yes' ){
            $team = Posts::whereIn( 'id' , (array) json_decode($settings['team']) ) ->get();
            $settings['team'] = $team ;
        }

        return $settings ;
    }

    public function c_p_why_ezuru($request){
        $settings = Options::where('type' , 'why_ezuru')->pluck('option_value' , 'option_var');

        $pages = Posts::whereIn( 'id' , (array) json_decode($settings['pages']) ) ->get();

        $settings['pages'] = $pages ;

        $pages2 = Posts::whereIn( 'id' , (array) json_decode($settings['pages2']) ) ->get();
        $settings['pages2'] = $pages2 ;
        

        return $settings ;
    }

    public function c_p_trust_saftey($request){
        $settings = Options::where('type' , 'trust')->pluck('option_value' , 'option_var');

        $pages = Posts::whereIn( 'id' , (array) json_decode($settings['pages']) ) ->get();

        $settings['pages'] = $pages ;

        $pages2 = Posts::whereIn( 'id' , (array) json_decode($settings['one_page']) ) ->get();
        $settings['one_page'] = $pages2 ;
        

        return $settings ;
    }

    public function c_p_how_work($request){
        $settings = Options::where('type' , 'how_work')->pluck('option_value' , 'option_var');

        $pages = Posts::whereIn( 'id' , (array) json_decode($settings['pages']) ) ->get();

        $settings['pages'] = $pages ;

        $overview = Posts::whereIn( 'id' , (array) json_decode($settings['overview']) ) ->get();
        $settings['overview'] = $overview ;

        $as_guest = Posts::whereIn( 'id' , (array) json_decode($settings['as_guest']) ) ->get();
        $settings['as_guest'] = $as_guest ;

        $as_host = Posts::whereIn( 'id' , (array) json_decode($settings['as_host']) ) ->get();
        $settings['as_host'] = $as_host ;

        $saftey = Posts::whereIn( 'id' , (array) json_decode($settings['saftey']) ) ->get();
        $settings['saftey'] = $saftey ;
        

        return $settings ;
    }


    public function c_p_payment_methods($request){
        $settings = Options::where('type' , 'payment_methods')->pluck('option_value' , 'option_var');

        $payin = Posts::whereIn( 'id' , (array) json_decode($settings['payin']) ) ->get();
        $settings['payin'] = $payin ;

        $payout = Posts::whereIn( 'id' , (array) json_decode($settings['payout']) ) ->get();
        $settings['payout'] = $payout ;

        

        return $settings ;
    }

    public function c_p_checkin($request){
        $settings = Options::where('type' , 'checkin')->pluck('option_value' , 'option_var');

        $guest = Posts::whereIn( 'id' , (array) json_decode($settings['guest']) ) ->get();
        $settings['guest'] = $guest ;

        $host = Posts::whereIn( 'id' , (array) json_decode($settings['host']) ) ->get();
        $settings['host'] = $host ;

        

        return $settings ;
    }

    public function c_p_contact($request){
        $settings = Options::where('type' , 'contact')->pluck('option_value' , 'option_var');

        $settings['categories'] = Taxonomy::whereIn('id' , (array) json_decode($settings['categories']))->get();

        $settings['6_cats'] = (array) json_decode($settings['6_cats']) ;

        $posts = [] ;

        $fild = 'title' ;
        if( isset($request->lang) && $request->lang == 'en' ){
            $fild = 'title_en' ;
        }

        foreach( $settings['6_cats'] as $cat ) {
            
            $pos = Posts::where('taxonomy_id' , (int)$cat)->where('status' , 1)->pluck( $fild , 'id');

            $posts[(int)$cat] = $pos ;
        }

        $settings['6_cats'] = Taxonomy::whereIn('id' , $settings['6_cats'] )->get() ;

        $settings['6_cats_posts'] = $posts ;

        $settings['3_pages'] = Posts::whereIn('id' , (array) json_decode($settings['3_pages']))->get() ;

        $settings['form_cats'] = Taxonomy::whereIn('id' , (array) json_decode($settings['form_cats']) )->get() ;

        return $settings ;
    }

    public function c_p_partiners($request){
        $settings = Options::where('type' , 'partiners')->pluck('option_value' , 'option_var');

        $settings['gold'] = Posts::whereIn('id' , (array) json_decode($settings['gold']))->get();

        $settings['silver'] = Posts::whereIn('id' , (array) json_decode($settings['silver']))->get();

        $settings['bronze'] = Posts::whereIn('id' , (array) json_decode($settings['bronze']))->get();

        return $settings ;
    }


    public function c_p_blog($request){
        $settings = Options::where('type' , 'blog')->pluck('option_value' , 'option_var');

        $posts = Posts::where('type' , 'blog') ;

        if( isset($request->s{3}) ){
            $posts->whereRaw('concat_ws(" " , title,title_en,description,description_en) LIKE "%'.addslashes($request->s).'%" ') ;
        }


        $list = $posts->paginate( $settings['perPage'] ) ;

        foreach($list as $post){
            $post->excerpt = mb_substr( strip_tags($post->description) , 0 , 150 , 'utf-8' ) ;
            $post->excerpt_en = mb_substr( strip_tags($post->description_en) , 0 , 150 , 'utf-8' ) ;

            unset( $post->description , $post->description_en ) ;
        }

        $settings['posts'] =  $list ;

        return $settings ;

    }

    public function c_p_news($request){
        $settings = Options::where('type' , 'news')->pluck('option_value' , 'option_var');

        $settings['featured'] = Posts::where('id' , $settings['featured_news'])->with('category')->first();

        if( isset($settings['featured']->id) ){
            $settings['featured']->excerpt = mb_substr( strip_tags( $settings['featured']->description ) , 0 , 250 , 'utf-8' ) ;
            $settings['featured']->excerpt_en = mb_substr( strip_tags( $settings['featured']->description_en ) , 0 , 250 , 'utf-8' ) ;
            unset( $settings['featured']->description , $settings['featured']->description_en ) ;
        }

        $settings['latest'] = Posts::orderBy('id' , 'desc')->with('category')->take($settings['last_news_count'])->get() ;

        foreach( $settings['latest'] as $post ){
            $post->excerpt = mb_substr( strip_tags( $post->description ) , 0 , 250 , 'utf-8' ) ;
            $post->excerpt_en = mb_substr( strip_tags( $post->description_en ) , 0 , 250 , 'utf-8' ) ;
            unset( $post->description , $post->description_en ) ;
        }

        $posts = Posts::where('type' , 'news') ;

        if( isset($request->cat) and $request->cat > 0 ){
            $posts->where('taxonomy_id' , $request->cat ) ;
        }



        $list = $posts->with('category')->paginate( $settings['category_count'] ) ;

        foreach($list as $post){
            $post->excerpt = mb_substr( strip_tags($post->description) , 0 , 150 , 'utf-8' ) ;
            $post->excerpt_en = mb_substr( strip_tags($post->description_en) , 0 , 150 , 'utf-8' ) ;

            unset( $post->description , $post->description_en ) ;
        }

        $settings['posts'] =  $list ;

        $fild = 'name' ;
        if( isset($request->lang) && $request->lang == 'en' ){
            $fild = 'name_en' ;
        }
        $settings['Categories'] = Taxonomy::where('type' , 'news')->whereIn('id' , (array) json_decode( $settings['Categories'] ) )->pluck( $fild  , 'id') ;

        return $settings ;

    }

    public function c_p_faq($request){
        $settings = Options::where('type' , 'faq')->pluck('option_value' , 'option_var');

        $settings['categories'] = Taxonomy::whereIn('id' , (array) json_decode($settings['categories']))->get();

        $settings['6_cats'] = (array) json_decode($settings['6_cats']) ;

        $posts = [] ;

        $fild = 'title' ;
        if( isset($request->lang) && $request->lang == 'en' ){
            $fild = 'title_en' ;
        }

        foreach( $settings['6_cats'] as $cat ) {
            
            $pos = Posts::where('taxonomy_id' , (int)$cat)->where('status' , 1)->limit((int)$settings['child_count'])->pluck( $fild , 'id');

            $posts[(int)$cat] = $pos ;
        }

        $settings['6_cats'] = Taxonomy::whereIn('id' , $settings['6_cats'] )->get() ;

        $settings['6_cats_posts'] = $posts ;

        return $settings ;
    }

    

    public function post($id = 0){
        return Posts::findOrFail($id) ;
    }

    public function search(Request $request){

        $typex = $request->type ?? "faq" ;

        $posts = Posts::where('type' , $typex) ;
        $title = '' ;
        if( isset($request->s{3}) ){
            $posts->whereRaw('concat_ws(" " , title,title_en,description,description_en) LIKE "%'.addslashes($request->s).'%" ') ;
            $title =  addslashes($request->s) ;
        }elseif( isset($request->cat) and (int) $request->cat > 0){
            $posts->where('taxonomy_id' , (int) $request->cat ) ;
            $cat = Taxonomy::findOrFail((int) $request->cat );
            if( $cat ){
                $title =  $cat  ;
            }
        }

        $posts = $posts->select('id' , 'title' , 'title_en')->get() ;

        return ['posts' => $posts , 'title' => $title] ;
        
    }


    public function c_p_cancle($request){
        $settings = Options::where('type' , 'cancle')->pluck('option_value' , 'option_var');
        $settings['pages'] = Posts::whereIn('id' , (array) json_decode($settings['pages']))->get();
        if( $settings['show_global_terms'] == 'yes' ) {
            $settings['terms'] = Posts::whereIn('id' , (array) json_decode($settings['terms']))->get();
        }
        return $settings ;
    }

    public function c_p_careers($request){
        $settings = Options::where('type' , 'careers')->pluck('option_value' , 'option_var');
        $settings['jobs'] = Posts::whereIn('id' , (array) json_decode($settings['jobs']))->get();
        $settings['categories'] = Taxonomy::whereIn('id' , (array) json_decode($settings['categories']))->withcount('posts')->get();
        ;
        return $settings ;
    }

    public function c_p_terms($request){
        $settings = Options::where('type' , 'terms')->pluck('option_value' , 'option_var');   
        return $settings ;
    }

    public function c_p_privacy($request){
        $settings = Options::where('type' , 'privacy')->pluck('option_value' , 'option_var');   
        return $settings ;
    }




    public function contact(Request $request){

        $message = new \stdClass();

        $message->name = $request->name ;

        $message->email = $request->email;

        $message->mobile = $request->mobile;

        $message->type  = $request->type;

        $message->message  = $request->message;

        $settings = Options::where('type' , 'contact')->pluck('option_value' , 'option_var');

        $mailed = Mail::to( $settings['reciver'] )->send(new Contact($message));

        if( $mailed ){
            return 'Your Message Sent Succefully' ;
        }

        return 'Unable to send Message' ;


    }

    public function apply_job(Request $request){

        $message = new \stdClass();

        $message->name = $request->name ;

        $message->email = $request->email;

        $message->mobile = $request->mobile;

        $message->type  = $request->cv ;

        $message->message  = $request->message;

        $settings = Options::where('type' , 'contact')->pluck('option_value' , 'option_var');

        $mailed = Mail::to( $settings['reciver'] )->send(new Job($message));

        if( $mailed ){
            return [ 'res' => 1 , 'msg' => 'Your Message Sent Succefully' ] ;
        }

        return [ 'res' => 0 , 'msg' => 'Unable to send Message' ] ;

    }

    


}
