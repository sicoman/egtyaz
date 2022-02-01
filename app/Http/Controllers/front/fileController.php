<?php

namespace App\Http\Controllers\front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class fileController extends Controller {


    public function handle(Request $request){
        
            return [ 'success' =>  true ,'error' => '', 'url' =>  $this->photoUpload( 'file' , '.jpg' , $request ) ]  ;
        
    }

    protected function photoUpload($input = 'photo' , $ext = '.jpg' , Request $request){

        if( $request->hasFile( $input ) ) {
            $ext = request()->{$input}->getClientOriginalExtension() ;
            $ext = strtolower($ext) ;
            if( !in_array( $ext , ['jpg','jpeg','png','gif','docx','doc' , 'pdf' , 'mp4'] ) ) {
                return null;
            }
        
            $imageName = strtolower( rand( 9999,999999 ).time() . '.' . $ext ) ;

            $image_Path = public_path('uploads/category/') ;

            request()->{$input}->move( $image_Path , $imageName );
            
            $image_Path = $image_Path.$imageName ;

            $this->handleImage( $image_Path ) ;

            if( isset($request->prefix) && $request->prefix == 'false' ) {
                return '/uploads/category/'.$imageName ;
            }

            return url( '/uploads/category/'.$imageName ) ;

        }else{
            return null ;
        }

    }

    public function handleImage($image_Path , $maxWidth = 300 , $blur_width = 75 , $quilty = 80){
        
        $ext = str_replace( '.', '' , strtolower(strrchr($image_Path , '.')) ) ;

        if( in_array( $ext , ['jpg','jpeg','png','gif'] ) ) {
            // Compress image to quality 80 [override uploaded image]
            compress(
                 $image_Path ,
                 $image_Path ,
                 $quilty
            ) ;

            // Get Best Size to Resize
            $new_sizes = getBestSize( $image_Path , $maxWidth  ) ;

            $thumb_path = str_replace('/category' , '/category/thumbs' , $image_Path) ;

            if( is_array($new_sizes) ) {
                // Resize Image to main Block Height and Width
                resize_crop_image(
                    $new_sizes[0] , $new_sizes[1] ,  $image_Path , $thumb_path
                ) ;
            }else{
                // Copy Image to Thumbnail Path
                copy( $image_Path , $thumb_path ) ;
            }

            // Lets Make Blue Copy for lazy Loading
            $blur_path = str_replace('/category' , '/category/blur' , $image_Path) ;
            
            $blur_sizes = getBestSize( $image_Path , $blur_width ) ;

            resize_crop_image(
                 $blur_sizes[0] , $blur_sizes[1] ,  $image_Path , $blur_path
            ) ;

        }

        return true ;
    }

    public function handleUploaded(){
        $path = public_path('uploads/category/') ;

        $list = scandir($path) ;

        $images = [] ;
        foreach($list as $file){
             $ext = strtolower( strrchr( $file , '.' ) ) ;
             if( in_array( $ext , ['.jpg' , '.jpeg' , '.png' , '.gif'] ) ){

                if( !file_exists( $path.'/thumbs/'.$file ) ){
                    $images[] = $file ;
                    try{
                        $this->handleImage( $path.$file ) ;
                    }catch(Exception $e){
                        dd($file); 
                    }
                    
                }
             }
        }



        return $images ;
    }
}
