<?php

namespace App\Traits;

trait Curly {

    public function fetch($method = 'GET' , $link , $post = [] , $headers = [] ) {

        $link = str_replace('//' , '/' , $link) ;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL , $link );

        $method = strtolower($method) ;

        if( $method == 'post' ) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);  //Post Fields
        }elseif ($method != 'post' && $method != 'get' ) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method) );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /*
        $headers = [
            'X-Apple-Tz: 0',
            'X-Apple-Store-Front: 143444,12',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,/*;q=0.8',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: en-US,en;q=0.5',
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Host: www.example.com',
            'Referer: http://www.example.com/index.php', //Your referrer address
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
            'X-MicrosoftAjax: Delta=true'
        ];
        */

        if( !empty($headers) ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $server_output = curl_exec ($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $error = curl_error($ch);

        curl_close ($ch);

        return [ $httpcode , $server_output , $link , $method , $error ] ;

    }

}
?>