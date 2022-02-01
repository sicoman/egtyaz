<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class fileController extends Controller {


    public function handle(Request $request){
        if( isset($request->return) and $request->return == 'object' ){
            return [ 'files' => ['file' =>  $this->photoUpload( 'file' , '.jpg' , $request ) ] ]  ;
        }
        return $this->photoUpload( 'file' , '.jpg' , $request ) ;
    }

    protected function photoUpload($input = 'photo' , $ext = '.jpg' , Request $request){

        #logy( auth()->user()->id , 'upload' , '' , 'file' , [] );

        if( $request->hasFile( $input ) ) {

            $imageName = strtolower( rand( 9999,999999 ).time() . '.' . request()->{$input}->getClientOriginalExtension() ) ;

            request()->{$input}->move( public_path('uploads/category') , $imageName );

            return url( '/uploads/category/'.$imageName ) ;

        }elseif( $request->has( $input.'_old' ) ) {
            return $request->{$input.'_old'} ;
        }elseif( $request->has( $input ) && filter_var( $request->{$input} , FILTER_VALIDATE_URL) ) {
            return $request->{$input} ;
        }elseif( $request->has($input.'_base64' ) ) {
            $file = $request->{$input.'_base64'} ;
            $base = str_replace( ';base64,' , '' , strrchr( $file , ';base64,' ) ) ;
            $imageName = rand( 9999,999999 ).time() . '.' . $ext ;
            file_put_contents( public_path('uploads/category').'/'. $imageName , base64_decode($base)  ) ;
            return url( '/uploads/category/'.$imageName ) ;
        }else{
            return null ;
        }

    }
}
