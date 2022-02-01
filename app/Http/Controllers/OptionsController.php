<?php

namespace App\Http\Controllers;

use App\Models\Options;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        $list = Options::where('status' , 1) ;
        if( $request->type != '' ){
            $list->where('type' , $request->type) ;
        }

        $list = $list->get();

        foreach($list as $k=>$obj){
            $list[$k]['options'] = $obj->options(); 
        }


        logy( auth()->user()->id , 'index' , '' , 'settings' , [] );

        return $list ;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Options  $options
     * @return \Illuminate\Http\Response
     */
    public function show(Options $options)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Options  $options
     * @return \Illuminate\Http\Response
     */
    public function edit(Options $options)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Options  $options
     * @return \Illuminate\Http\Response
     */
    public function update($id , Request $request)
    {
        $option = Options::find($id) ;

        if( !isset($option->id) ){
            return response()->json(['error' => 'Email has been taken'], 403);
        }else{
             if( is_array($request->option_value) ){
                $request->option_value = json_encode($request->option_value) ;
             }
             $option->update(['option_value' => $request->option_value]) ;
             $option->save();

             // If Option Type In This Array
             $option_value = html_entity_decode( $option->option_value ) ;
             $save = '' ;
             if( $option->type == 'notifications_email' ) {
                $save = file_put_contents( base_path('resources/views/notifications/email/'.$option->option_var.'.blade.php') , $option_value ) ;
             }elseif( $option->type == 'notifications_email_ar' ) {
                $save = file_put_contents( base_path('resources/views/notifications/email/ar/'.$option->option_var.'.blade.php') , $option_value ) ;
            }elseif( $option->type == 'notifications_sms' ) {
                $save = file_put_contents( base_path('resources/views/notifications/sms/'.$option->option_var.'.blade.php') , $option_value ) ;
             }elseif( $option->type == 'notifications_sms_ar' ) {
                $save = file_put_contents( base_path('resources/views/notifications/sms/ar/'.$option->option_var.'.blade.php') , $option_value ) ;
            }elseif( $option->type == 'notifications_email_title' ) {
                $save = file_put_contents( base_path('resources/views/notifications/title/'.$option->option_var.'.blade.php') , $option_value ) ;
             }elseif( $option->type == 'notifications_email_ar_title' ) {
                $save = file_put_contents( base_path('resources/views/notifications/title/ar/'.$option->option_var.'.blade.php') , $option_value ) ;
            }

            if( $option->type == 'admin_notifications_email' ) {
                $save = file_put_contents( base_path('resources/views/notifications/admin/email/'.$option->option_var.'.blade.php') , $option_value ) ;
             }elseif( $option->type == 'admin_notifications_email_ar' ) {
                $save = file_put_contents( base_path('resources/views/notifications/admin/email/ar/'.$option->option_var.'.blade.php') , $option_value ) ;
            }elseif( $option->type == 'admin_notifications_sms' ) {
                $save = file_put_contents( base_path('resources/views/notifications/admin/sms/'.$option->option_var.'.blade.php') , $option_value ) ;
             }elseif( $option->type == 'admin_notifications_sms_ar' ) {
                $save = file_put_contents( base_path('resources/views/notifications/admin/sms/ar/'.$option->option_var.'.blade.php') , $option_value ) ;
            }elseif( $option->type == 'admin_notifications_email_title' ) {
                $save = file_put_contents( base_path('resources/views/notifications/admin/title/'.$option->option_var.'.blade.php') , $option_value ) ;
             }elseif( $option->type == 'admin_notifications_email_ar_title' ) {
                $save = file_put_contents( base_path('resources/views/notifications/admin/title/ar/'.$option->option_var.'.blade.php') , $option_value ) ;
            }

        }

        logy( auth()->user()->id , 'edit' , '' , 'settings' , [] );

        return $option  ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Options  $options
     * @return \Illuminate\Http\Response
     */
    public function destroy(Options $options)
    {
        //
    }
}
