<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Schema; 

use DB ;

class SystemTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    protected $del = "\r\n" ;
    protected $tables_columns = '{"admin_area":{"id":{},"user_id":{},"area_id":{},"created_at":{},"updated_at":{},"type":{}},"agent_report_images":{"id":{},"report_id":{},"image":{},"alt":{},"created_at":{},"updated_at":{}},"agent_reports":{"id":{},"user_id":{},"unit_id":{},"booking_id":{},"type":{},"report":{},"notes":{},"answer":{},"answered_at":{},"status":{},"created_at":{},"updated_at":{}},"badges":{"id":{},"type":{},"u_u_id":{},"badge":{},"created_at":{},"updated_at":{}},"booking_cancel":{"id":{},"booking_id":{},"cancel_id":{},"price":{},"price_fee":{},"ezuru_fee":{},"tourism":{},"tax":{},"object":{},"created_at":{},"updated_at":{}},"cancel":{"id":{},"name":{},"name_en":{},"description":{},"description_en":{},"before":{},"before_percent":{},"before_fee":{},"within":{},"within_percent":{},"within_fee":{},"within_minus":{},"checkin_out":{},"checkin_minus":{},"checkin_fee":{},"status":{},"created_at":{},"updated_at":{}},"days":{"id":{},"unit_id":{},"date":{},"price":{},"price_before":{},"status":{}},"flags":{"id":{},"type":{},"description":{},"flagged_id":{},"flagged_by":{},"status":{},"created_at":{},"updated_at":{}},"holidays":{"id":{},"date":{},"title":{},"status":{},"created_at":{},"updated_at":{}},"logs":{"id":{},"user_id":{},"log_type":{},"referer":{},"data":{},"model":{},"created_at":{},"updated_at":{},"deleted_at":{}},"messages":{"id":{},"unit_id":{},"user_id":{},"owner_id":{},"created_at":{},"updated_at":{}},"messages_admins":{"id":{},"message_id":{},"message_item_id":{},"answer":{},"created_at":{},"updated_at":{}},"messgaes_items":{"id":{},"message_id":{},"user_id":{},"message":{},"readed":{},"created_at":{},"updated_at":{}},"migrations":{"id":{},"migration":{},"batch":{}},"model_has_permissions":{"permission_id":{},"model_type":{},"model_id":{}},"model_has_roles":{"role_id":{},"model_type":{},"model_id":{}},"notifications":{"id":{},"type":{},"notifiable_type":{},"notifiable_id":{},"data":{},"read_at":{},"created_at":{},"updated_at":{}},"options":{"id":{},"type":{},"option_var":{},"option_value":{},"html":{},"status":{},"description":{},"description_en":{},"created_at":{},"updated_at":{}},"password_resets":{"email":{},"token":{},"created_at":{}},"payments":{"id":{},"gateway":{},"unit_id":{},"booking_id":{},"user_id":{},"owner_id":{},"cost":{},"fee":{},"total":{},"is_paid":{},"status":{},"gateway_answer":{},"created_at":{},"updated_at":{}},"permissions":{"id":{},"name":{},"guard_name":{},"created_at":{},"updated_at":{}},"posts":{"id":{},"type":{},"user_id":{},"title":{},"description":{},"photo":{},"title_en":{},"description_en":{},"taxonomy_id":{},"status":{},"views":{},"created_at":{},"updated_at":{}},"reviews":{"id":{},"unit_id":{},"booking_id":{},"guest_id":{},"owner_id":{},"score":{},"guest_review":{},"review":{},"status":{},"guest_score":{},"guest_review_date":{},"unit_review_date":{},"created_at":{},"updated_at":{}},"reviews_items":{"id":{},"for":{},"review_id":{},"type":{},"score":{},"note":{},"reviewed_by":{},"created_at":{},"updated_at":{}},"role_has_permissions":{"permission_id":{},"role_id":{}},"roles":{"id":{},"name":{},"guard_name":{},"created_at":{},"updated_at":{}},"search":{"id":{},"user_id":{},"search":{},"search_query":{},"count":{},"created_at":{},"updated_at":{}},"taxonomies":{"id":{},"type":{},"name":{},"description":{},"name_en":{},"description_en":{},"photo":{},"parent":{},"status":{},"created_at":{},"updated_at":{}},"unit_booking":{"id":{},"unit_id":{},"user_id":{},"owner_id":{},"date_start":{},"date_end":{},"day_price":{},"price":{},"fee":{},"tax":{},"tourism":{},"ezuru_fee":{},"check_in":{},"check_out":{},"status":{},"childrens":{},"adults":{},"token":{},"payerid":{},"gateway":{},"auth":{},"cancel_message":{},"created_at":{},"updated_at":{}},"unit_days":{"id":{},"unit_id":{},"date_start":{},"date_end":{},"day_price":{},"day_price_before":{},"created_at":{},"updated_at":{}},"unit_fees":{"id":{},"unit_id":{},"fee_id":{},"amount":{},"fee_type":{},"created_at":{},"updated_at":{}},"unit_images":{"id":{},"unit_id":{},"image":{},"title":{},"ordr":{},"created_at":{},"updated_at":{}},"unit_options":{"id":{},"unit_id":{},"type":{},"taxonomy_id":{}},"unit_promo":{"id":{},"unit_id":{},"day_id":{},"date_start":{},"date_end":{},"percent":{},"created_at":{},"updated_at":{}},"unit_views":{"id":{},"unit_id":{},"created_at":{},"updated_at":{}},"units":{"id":{},"user_id":{},"title":{},"description":{},"type":{},"child_type":{},"allow_childrens":{},"allow_infants":{},"allow_animals":{},"allow_extra":{},"guests":{},"rooms":{},"beds":{},"bathrooms":{},"balacons":{},"min_guests":{},"max_childrens":{},"max_extra":{},"min_days":{},"max_days":{},"longitude":{},"latitude":{},"country":{},"government":{},"city":{},"area":{},"address":{},"building_number":{},"unit_number":{},"floor_number":{},"zipcode":{},"bank_account":{},"bank_number":{},"fee":{},"fee_static":{},"vat":{},"tourism":{},"tourism_static":{},"checkin":{},"checkout":{},"deliver_keys":{},"get_keys":{},"notes":{},"contract_image":{},"video":{},"cancle_policy":{},"status":{},"featured":{},"currency":{},"price":{},"rate_count":{},"rate_score":{},"created_at":{},"updated_at":{}},"user_account_log":{"id":{},"account_id":{},"created_at":{},"updated_at":{}},"user_accounts":{"id":{},"user_id":{},"provider":{},"provider_id":{}},"users":{"id":{},"name":{},"email":{},"email_verified_at":{},"password":{},"mobile":{},"photoid":{},"city":{},"mobile_verified_at":{},"photoid_verified_at":{},"description":{},"avatar":{},"rate_count":{},"rate_score":{},"status":{},"lang":{},"add_by":{},"percent":{},"accept":{},"remember_token":{},"created_at":{},"updated_at":{}},"wishlist":{"id":{},"user_id":{},"unit_id":{},"created_at":{},"updated_at":{}}}' ;
    protected $pages_settings = '{"about":["banner","pages","show_team","team","title","title_en"],"blog":["banner","perPage","search","title","title_en"],"cancle":["pages","show_global_terms","terms","text","text_en","title","title_en","warning"],"careers":["banner","categories","jobs","reciver","text","text_en","title","title_en"],"checkin":["banner","guest","host","title","title_en"],"contact":["3_pages","6_cats","banner","categories","form_cats","reciver","title","title_en"],"currency":["USD_EGP"],"faq":["6_cats","banner","categories","child_count","title","title_en"],"hidden":["fee","static","tourism","tourism_static","vat"],"how_work":["as_guest","as_host","banner","overview","pages","saftey","title","title_en"],"news":["Categories","category_count","featured_news","last_news_count"],"partiners":["banner","bronze","gold","silver","text","text_en","title","title_en"],"payment_methods":["banner","payin","payout","text","text_en","title","title_en"],"privacy":["banner","text","text_en","title","title_en"],"seo":["analytics","description","keywords","phone1","phone2","pixel","tagmanager","title","title_en"],"social":["android","facebook","ios","pintrest","twitter","youtube"],"terms":["banner","text","text_en","title","title_en"],"trust":["banner","one_page","pages","text","text_en","title","title_en"],"why_ezuru":["banner","pages","pages2","text","text_en","title","title_en"]}' ;
    protected $notifications_options = '{"admin_notifications_email":["booking_key_delivered","booking_confirm_key_delivered","booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_add_photoid","user_verifed_email","user_verifed_mobile","user_verifed_photoid","user_welcome"],"admin_notifications_sms":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_add_photoid","user_verifed_email","user_verifed_mobile","user_verifed_photoid","user_welcome"],"admin_notifications_title":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_add_photoid","user_verifed_email","user_verifed_mobile","user_verifed_photoid","user_welcome"],"admin_notifications_via":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_add_photoid","user_verifed_email","user_verifed_mobile","user_verifed_photoid","user_welcome"],"notifications_email":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","last_2_search","new_message","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_active_mobile","user_forget_password","user_verifed_photoid","user_welcome"],"notifications_email_ar":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","last_2_search","new_message","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_active_mobile","user_forget_password","user_verifed_photoid","user_welcome"],"notifications_email_ar_title":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","last_2_search","new_message","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_active_mobile","user_forget_password","user_welcome"],"notifications_email_title":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","last_2_search","new_message","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_active_mobile","user_forget_password","user_welcome"],"notifications_sms":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","new_message","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_active_mobile","user_forget_password","user_verifed_photoid","user_welcome"],"notifications_sms_ar":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","booking_unpaid","new_message","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_active_mobile","user_forget_password","user_verifed_photoid","user_welcome"],"notifications_sms_ar_title":["new_message"],"notifications_via":["booking_cancel_accept","booking_canceled","booking_checkin","booking_checkout","booking_confirm","booking_expired","booking_paid","booking_reject","booking_request","new_message","review_add","review_submitted","review_trip","unit_changes_confirm","unit_confirm","unit_incomplete","unit_missing_photos","user_active_mobile","user_forget_password","user_verifed_photoid","user_welcome"]}' ;


    /*
     *   Test Tables
     */
    public function testExample()
    {
        $del = $this->del ;
        echo $del.$del.' ==========================  Tables Test  ==========================='.$del.$del ; 
        $this->withoutExceptionHandling();
        $list = (array) json_decode($this->tables_columns) ;
        foreach( $list as $table=>$columns ) {
            // check table exists
            $this->assertEquals( true ,  \Schema::hasTable( $table ) ) ;
            echo $del."Table '".$table."' OK ." ;
        }
        
    }

    /*
     *   Test Columns
     */

    public function testExample2(){
        $del = $this->del ;
        $this->withoutExceptionHandling();
        echo $del.$del.'==========================  Columns Test  ==========================='.$del.$del ;
        $list = (array) json_decode($this->tables_columns) ;
        foreach( $list as $table=>$columns ) {
            // Check table Columns
            foreach( $columns as $column => $null ){
                $this->assertEquals( true ,  \Schema::hasColumn( $table , $column ) ) ;
                echo $del."Table '".$table."' -> Column '".$column."'  Ok ." ;
            }
        }
    }


    /*
     *   Test Files Premissions
     */

    public function testExample3()
    {
        $del = $this->del ;

        echo $del.$del."==========================  Apache Test  =========================== $del.$del " ;

        $this->assertTrue(true , (version_compare(PHP_VERSION, '7.2.0') >= 0 ) ) ;

        echo $del."PHP Version is ".PHP_VERSION."  Ok ." ;

        $extentions = get_loaded_extensions() ; 

        $this->assertTrue( in_array  ('curl', $extentions ) ) ;
        echo $del."PHP Extention CURL Ok ." ;

        $this->assertTrue( in_array  ('gd', $extentions ) ) ;
        echo $del."PHP Extention Images Ok ." ;

        $this->assertTrue( in_array  ('ctype', $extentions ) ) ;
        echo $del."PHP Extention Ctype Ok ." ;

        $this->assertTrue( in_array  ('json', $extentions ) ) ;
        echo $del."PHP Extention JSON Ok ." ;

        $this->assertTrue( in_array  ('mbstring', $extentions ) ) ;
        echo $del."PHP Extention Mbstring Ok ." ;

        echo $del.$del."==========================  Premissnios Test  =========================== $del.$del " ;
        
        $this->assertTrue(true , file_exists( base_path().'.env' ) ) ;
        echo $del."File .env  Ok ." ;
        $this->assertTrue(true , is_writable( storage_path() ) ) ;
        echo $del."Folder Storage  Ok ." ;
        $this->assertTrue(true , is_writable( base_path().'/bootstrap/cache/' ) ) ;
        echo $del."Folder Cache  Ok ." ;
        $this->assertTrue(true , is_writable( public_path().'/uploads/' ) ) ;
        echo $del."Folder Uploads  Ok ." ;
        $this->assertTrue(true , is_writable( public_path().'/uploads/category/' ) ) ;
        echo $del."Folder Uploads/category  Ok ." ;
        $this->assertTrue(true , is_writable( public_path().'/uploads/category/thumbs/' ) ) ;
        echo $del."Folder Uploads/category/thumbs  Ok ." ;
        $this->assertTrue(true , is_writable( public_path().'/uploads/category/blur/' ) ) ;
        echo $del."Folder Uploads/category/blur  Ok ." ;
        $this->assertTrue(true , is_writable( public_path().'/vendor/' ) ) ;
        echo $del."Folder vendor  Ok ." ;
        $this->assertTrue(true , is_writable( public_path().'/node_modules/' ) ) ;
        echo $del."Folder node_modules  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/' ) ) ;
        echo $del."Folder views/notifications  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/admin/' ) ) ;
        echo $del."Folder views/notifications/admin  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/admin/email/' ) ) ;
        echo $del."Folder views/notifications/admin/email  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/admin/sms/' ) ) ;
        echo $del."Folder views/notifications/admin/sms  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/email/' ) ) ;
        echo $del."Folder views/notifications/email  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/sms/' ) ) ;
        echo $del."Folder views/notifications/sms  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/title/' ) ) ;
        echo $del."Folder views/notifications/title  Ok ." ;

        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/email/ar' ) ) ;
        echo $del."Folder views/notifications/email/ar  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/sms/ar' ) ) ;
        echo $del."Folder views/notifications/sms/ar  Ok ." ;
        $this->assertTrue(true , is_writable( resource_path().'/views/notifications/title/ar' ) ) ;
        echo $del."Folder views/notifications/title/ar  Ok ." ;

    }


    /*
     *   Default Database Data
     */
    public function testExample4()
    {
        $del = $this->del ;
        echo $del.$del.'==========================  Default Database Pages - Settings Data  ==========================='.$del.$del ;
        // Database Check Options Table for Pages

        $pages_set = (array) json_decode( $this->pages_settings ) ;

        foreach( $pages_set as $type => $page_set ){
             
            $c = \DB::table('options')->where('type' , $type)->whereIn('option_var' , $page_set )->count() ;

            $this->assertTrue(true , $c < count($page_set) ) ;
            echo $del."Database Options Table Type  `$type `  Ok ." ;

        }
    }

    
    public function testExample5()
    {
        $del = $this->del ;
        echo $del.$del.'==========================  Default Notifications - Database && files ==========================='.$del.$del ;
        // Database Check Options Table for Notifications
        $noti_set = (array) json_decode( $this->notifications_options ) ;
        foreach( $noti_set as $type => $not_set ){
            $c = \DB::table('options')->where('type' , $type)->whereIn('option_var' , $not_set )->count() ;
            $this->assertTrue(true , $c < count($not_set) ) ;
            echo $del."Database Notifications Type  ` $type `  Ok ." ;
        }

        foreach( $noti_set as $type => $not_set ){
            $c = \DB::table('options')->where('type' , $type)->whereIn('option_var' , $not_set )->count() ;
            
            // Lets Check if Every Notification Files Exists
            foreach( $not_set as $not_file ){
                
                $type_path = str_replace('_' , '/' , str_replace('notifications_' , '' , $type ) );

                $this->assertTrue(true , file_exists( resource_path().'/views/notifications/'.$type_path.'/'.$not_file.'.blade.php' ) ) ;
            
                echo $del."File Notifications ` $type_path/$not_file.blade.php  `  Ok ." ;
            }
        }

    }





}
