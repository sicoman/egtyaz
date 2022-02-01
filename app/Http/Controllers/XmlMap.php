<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Units ;
use App\Models\Posts ;

class XmlMap extends Controller
{

    public function index($type = 'units' ,Request $request){
        
        if(isset($request->type{3}) && $request->type == 'posts'){
            $return = Posts::where('status' , 1)->selectRaw('id,updated_at,CONCAT_WS("/" , "blog" , id , REPLACE(title_en , " " , "-")) as url, REPLACE(title , " " , "-") as url_ar')->get();
        }else{
            $return = Units::where('status' , 1)->selectRaw('id,updated_at,CONCAT_WS("/" , "units" , id , REPLACE(title , " " , "-")) as url')->get();
        }

        return $return ;
    }


    public function xml($type = 'units' , Request $request){
        return response()->view('sitemap', ['urls' => $this->index( $type , $request ) ] )->withHeaders([
            'Content-Type' => 'text/xml'
        ]) ;
    }




}
