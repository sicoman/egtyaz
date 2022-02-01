<?php
use Illuminate\Http\Request;
use Aloha\Twilio\TwilioInterface ;


function logy($user_id , $type = 'add' , $ref = '' , $model = '' , $data = '' ){
    if( $data == '' || $data == [] ){ $data = request()->all() ; }
    $ref = request()->server('HTTP_REFERER');
    return \App\Models\log::create([
        'user_id' => $user_id ,
        'log_type' => $type ,
        'referer' => $ref ,
        'data' => json_encode($data) ,
        'model' => $model
    ]) ;
    
}

function sms($mobile , $message) {
    $sid = env('TWILIO_SID' , 'ACa9dbde69a12a22fad0e4f5295d3e4638') ;
    $token = env('TWILIO_TOKEN' , '1807e99f73ea80a680b99127aa2d90c2') ;
    $from = env('TWILIO_FROM' , '+12565307340') ;
    try {
        $twilio = new Aloha\Twilio\Twilio( $sid , $token , $from );
        $result = $twilio->message($mobile, $message);
    }catch(Exception $e){
        return 0 ;
    }
    
    return 1 ;
}

function getDatesFromRange($start, $end, $format='Y-m-d') {
    return array_map(function($timestamp) use($format) {
        return date($format, $timestamp);
    },
    range(strtotime($start) + ($start < $end ? 4000 : 8000), strtotime($end) + ($start < $end ? 8000 : 4000), 86400));
}
/*
 *  Thumbnail For Images   
 */
function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];

    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;

        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;

        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;

        default:
            return false;
            break;
    }

    if( $max_width <= 0 || $max_height <= 0 ){
        return false;
    }

    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);

    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if($width_new > $width){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    }else{
        //cut point by width
        $w_point = (($width - $width_new) / 2);
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }

    $image($dst_img, $dst_dir, $quality);

    if($dst_img)imagedestroy($dst_img);
    if($src_img)imagedestroy($src_img);
}


function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);
    else{
        return false; 
    }

    imagejpeg($image, $destination, $quality);

    return $destination;
}

function getBestSize($source , $w = 300){
    $imgsize = getimagesize($source);
    $width   = $imgsize[0];
    $height  = $imgsize[1];

    if( $w > $width ){
        return false ;
    }

    // Calculate New Height
    $new_height = ( $height * $w ) / $width ;

    return [ $w , $new_height ] ;

}


if( !function_exists('embed_code') ){
    function embed_code($variable , $style ='style="width:100%;height:600px;"'){
         $return  = $variable  ;
         $youtube = isYoutube($variable , $style) ;
         $video   = isVideo($variable , $style) ;

         if( isset($youtube[10]) ){
            $variable = $youtube ;
         }elseif( isset($video[10]) ){
            $variable = $video ;
         }elseif( strrchr( $variable , '.') == '.pdf' ){
            $variable = '<div class="text-center"><br />
            <iframe src="http://docs.google.com/gview?url='.$variable.'&embedded=true" '.$style.' frameborder="0"></iframe>
            </div>' ;
         }else{
            $variable = '<p class="text-center"><br /><a target="_blank" href="'.$variable.'" class="btn btn-warning text-white" > تحميل </a></p>' ;
         }
        
        return $variable ;    
    }
    function isYoutube($string , $style) {
        $regex_pattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/"; $match;
            if(preg_match($regex_pattern, $string, $match)){
                return '<iframe '.$style.' src="https://www.youtube.com/embed/'.$match[4].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' ;
            }else{
                return false ;
            }
    }

    function isVideo($string , $style) {
        
        if( strrchr( strtolower($string) , '.' ) == '.mp4' ){
            return '<video  '.$style.' controls> 
            <source src="'.$string.'"  type="video/mp4">
          </video>' ;
        }

        return false ;
    }
}
function curlUrl( $url ) {
    $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
    $options = array(

        CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
        CURLOPT_POST           =>false,        //set to GET
        CURLOPT_USERAGENT      => $user_agent, //set user agent
        CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
        CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_SSL_VERIFYHOST => false ,
        CURLOPT_SSL_VERIFYPEER => false ,
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    return $content ;
}
function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
function xE($price , $currencyFrom){
    $url = 'https://query1.finance.yahoo.com/v8/finance/chart/USD'.$currencyFrom.'=X?indicators=close&includeTimestamps=false&includePrePost=false&corsDomain=finance.yahoo.com' ;

    $Ex = curlUrl($url) ;

    if( !isJson($Ex) ){
        return $price ;
    }

    $jsonObject = json_decode( $Ex ) ;

    $usd = $jsonObject->chart->result[0]->meta->regularMarketPrice ;

   return $price / $usd ;

}

if( !function_exists('embed_code') ){
    function embed_code($variable){
         $return  = $variable  ;
         $youtube = isYoutube($variable) ;
         $video   = isVideo($variable) ;

         if( isset($youtube[10]) ){
            $variable = $youtube ;
         }elseif( isset($video[10]) ){
            $variable = $video ;
         }elseif( strrchr( $variable , '.') == '.pdf' ){
            $variable = '<div class="text-center"><br />
            <iframe src="http://docs.google.com/gview?url='.$variable.'&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
            /div>' ;
         }else{
            $variable = '<p class="text-center"><br /><a target="_blank" href="'.$variable.'" class="btn btn-warning text-white" > تحميل </a></p>' ;
         }

         dd($variable) ;
        
        return $variable ;    
    }

    function isYoutube($string) {
        $regex_pattern = "/(youtube.com|youtu.be)\/(watch)?(\?v=)?(\S+)?/"; $match;
            if(preg_match($regex_pattern, $string, $match)){
                return '<iframe width="100%" height="400" src="https://www.youtube.com/embed/'.$match[4].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' ;
            }else{
                return false ;
            }
    }

    function isVideo($string) {
        
        if( strrchr( strtolower($string) , '.' ) == '.mp4' ){
            return '<video style="width:100%;height:400px;" controls> 
            <source src="'.$string.'" type="video/mp4">
          </video>' ;
        }

        return false ;
    }
}