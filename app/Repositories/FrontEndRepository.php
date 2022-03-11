<?php

namespace App\Repositories;

use App\Repositories\BaseRepository ;

use Illuminate\Container\Container as Application;


use App\Models\Posts ;
use App\Models\Options ;
use App\Contact ;
use View ;

use Mail;

/**
 * Interface ChallengeRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class FrontEndRepository extends BaseRepository
{

    public function __construct(Application $app , Posts $posts)
    {
        parent::__construct($app); 

        $this->posts = $posts ;

        $default_settings = $this->getSetting(['seo' , 'social']) ;

        View::share('options' , $default_settings ) ;

    }

    public function model(){
        return ('App\\Models\Options') ;  
    }

    public function getSetting( $type ){
        if( is_array($type) ){
            $returnOptions = [] ;
            $options = $this->model->whereIn('type' , $type )->select([ 'type' ,'option_value' , 'option_var'])->get() ;
            
            foreach($options as $option){
                $returnOptions[$option->type][ $option->option_var] =  $option->option_value ; 
            }
            return  $returnOptions ;
        }
        return  $this->model->where('type' , $type )->pluck('option_value' , 'option_var') ;
    }

    public function homePage(){ 
        $data = [
            'first_slider' => [] ,
            'second_slider' => [] ,
            'six_pages' => [] ,
            'what_we_produce' => [] ,
            'counts'  => [
                'questions' => 0 ,
                'forms' => 0 ,
                'exams' => 0 ,
                'videos' => 0 
            ] ,
            'first_students' => [] 
        ] ;

        $options = $this->getSetting('homepage') ;

        // $data['first_slider'] = $this->posts->where('type' , 'pages')->where('status' , 1)->whereIn( 'id' , (array) json_decode($options['first_slider']) )->select(['title' , 'photo' , 'description' ])->get() ;
        // $data['second_slider'] = $this->posts->where('type' , 'pages')->where('status' , 1)->whereIn( 'id' , (array) json_decode($options['second_slider']) )->select(['title' , 'photo' , 'description' , 'file'])->get() ;
        // $data['six_pages'] = $this->posts->where('type' , 'pages')->where('status' , 1)->whereIn( 'id' , (array) json_decode($options['six_pages']) )->select(['title' , 'photo' , 'description'])->get() ;
        // $data['what_we_produce'] = $this->posts->where('type' , 'pages')->where('status' , 1)->whereIn( 'id' , (array) json_decode($options['what_we_produce']) )->select(['id' , 'description' , 'title' , 'photo'])->get() ;

        $data['counts']['questions'] = $options['question_bank_count'] ;
        $data['counts']['forms'] = $options['forms_count'] ;
        $data['counts']['exams'] = $options['exams_count'] ;
        $data['counts']['videos'] = $options['videos_count'] ;

        $data['are_you_student_title'] = $options['are_you_student_title'] ;
        $data['are_you_student_desc'] = $options['are_you_student_desc'] ;
        
        return $data ;
    }


    public function page($page){
        
        return $data ;
    }

    public function contact(){
        return $this->getSetting('contact' ) ;
    }

    public function postContact($request){
    	
        $data = $this->getSetting('contact') ;

        $contact = $data ;

        Mail::raw(  $request->message."\r\n \r\n". $request['mobile'] , function ($message) use ( $request , $contact ) {
            $message->from( $request->email , $request->name );
            $to = explode( ',' , $contact['sendto'] ?? '') ;
            $message->to( trim($to[0]) );
            unset($to[0]) ;
            if( count($to) > 0 ){
                $message->cc( array_map('trim' , $to) );
            }
            $message->subject( $contact['subject'] );
        });

        Contact::create([
            'type' => 'callus' ,
            'name'  => $request->name ,
            'email' => $request->email ,
            'mobile' =>  $request->mobile ?? '' ,
            'subject' => $request->subject ,
            'message' => $request->message ,
            'status' => 0
        ]) ;

        return ['success' => true , 'message' => 'تم ارسال الرسالة بنجاح'];

    }

    public function AddChallengers($challengeObject , $challenger , $status = 0 ){
        return $challengeObject->Challengers()->create(
            [
                'user_id' => $challenger  ,
                'status' => $status
            ]
        ) ;
    }

    public function dashcpanel(){
        $data = [
            'dash_blocks' => [] , 
        ] ;

        $options = $this->getSetting('dashboard') ;

        $data['dash_blocks'] = $this->posts->where('type' , 'pages')->where('status' , 1)->whereIn( 'id' , (array) json_decode($options['dash_blocks']) )->select(['title' , 'photo' , 'description' ])->get() ;

        return $data ;
    }

    public function rewardsData(){
        $data = [
            'dash_blocks' => [] , 
        ] ;
        $options = $this->getSetting('dashboard') ;
        return ['rewards_page_image' => $options['rewards_page_image'] , 'rewards_page_text' => $options['rewards_page_text'] ] ;
    }

}
