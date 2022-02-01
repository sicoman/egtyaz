<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;

use App\Channels\SmsChannel;

use App\Models\Options ;

class AdminNotify extends Notification
{
    use Queueable;

    protected $data ;
    protected $type ;
    protected $lang ;
    protected $title ;
    protected $via = [] ;
    protected $message = '' ;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data , $type , $lang = 'en' , $customMessage = '') {
        $this->data = $data ;
        $this->type = $type ;
        $this->lang = $lang ;
        $this->message = $customMessage ;
        $this->getVia() ;
    }

    protected function getVia(){
        $via = Options::where('option_var' , $this->type)->where('type' , 'admin_notifications_via')->select('option_value')->first();
        if( !isset($via->option_value) ){
            $this->via = ['mail'] ;
        }else{
            $this->via = json_decode($via->option_value);
            foreach($this->via as $k => $v){
                if( $v == 'sms' ){
                    $this->via[$k] = SmsChannel::class ;
                }elseif( $v == 'pushed' ){
                    $this->via[$k] = 'database' ;
                }
            }
        } 
    }

    protected function getPath($type = 'email'){
        if( $this->lang == 'ar' ){
            return 'notifications.admin.'.$type.'.ar.'.$this->type ;
        }
        return 'notifications.admin.'.$type.'.'.$this->type ;
    }

    protected function getTitle(){

        $template = 'notifications.admin.title.'.$this->type ;

        if( $this->lang == 'ar' ){
            $template = 'notifications.admin.title.ar.'.$this->type ;
        }

        

        if(View::exists($template)){ 
            $this->title = view($template , $this->data )->render() ;
        }else{
            $this->title = str_replace('_' , ' ' , $this->type ) ;
        }

        return $this->title ;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via( $notifiable ){
        return $this->via ;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
    
        $template =  $this->getPath('email') ; 

        $title    =  $this->getTitle() ;

        if( $this->message != '' ){
             file_put_contents( base_path('resources/views/notifications/email/customMessage.blade.php') , $this->message ) ;
             $template = 'notifications/email/customMessage' ;
        }

        $this->data['mail_data']     = $this->data ;
        $this->data['mail_template'] = $template ;
    
        if(View::exists($template)){ 
            
            $styleTemplate = 'notifications/email/mail'  ;
            return (new MailMessage)->subject( $title )->markdown( $styleTemplate , $this->data );
            
        }

    }
    
    public function toSms($notifiable) {

        if( !isset($notifiable->mobile{5}) ){
            return false ; 
        }
    
        $data = $this->toArray( $notifiable ) ;

        if( isset( $data['message']{2} ) ) {
           sms( $notifiable->mobile , $data['message'] ) ;
        }
        

    }




    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $template =  $this->getPath('sms') ; 

        if( $this->message != '' ){
            file_put_contents( base_path('resources/views/notifications/email/customMessage.blade.php') , $this->message ) ;
            $template = 'notifications/email/customMessage' ;
        }

        $title    =  $this->getTitle() ;

    
        if ( view()->exists( $template )) {
            $message  =  view( $template , $this->data )->render() ;
        }else{
            $message  = 'Template Not Found' ;
        }

        return [
             'title' => $title ,
             'message' => $message ,
             'date' => json_encode( $this->data ) 
        ];
    }

    public function toDatabase($notifiable){
        return $this->toArray($notifiable) ;
    }

}