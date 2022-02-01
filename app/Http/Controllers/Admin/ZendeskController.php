<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Zendesk;

class ZendeskController extends Controller
{
    public function __construct(){
        $data = [
            'api_key' => 'jQj9HPDPEFJqkYHXsZzKq5xaI4fCHEXxyfBGa39Z' ,
            'domain'  => 'https://momaiz.zendesk.com' , 
            'username' => 'momaiz.net@gmail.com'
        ] ;
    }

    public function index(Request $request){
        $per_page = $request->limit ?? 10 ;
        $page     = $request->page ?? 1 ;
        $tickets = Zendesk::tickets()->findAll(['per_page' => $per_page , 'page' => $page ]);

        // 
        $tickets = (array) $tickets ;
        $pager['total'] = $tickets['count'] ;
        $pager['per_page'] = (int)$per_page ;
        $pager['current_page'] = $page ;

        $return = [
            'data' => $tickets['tickets'] ,
            'total' => $tickets['count'] ,
            'per_page' => (int)$per_page ,
            'current_page' => $page
        ];

        return response()->json(  $return ) ; 
    }
    
    public function show($id , Request $request){
        $ticket = Zendesk::tickets()->find($id);
        $per_page = $request->limit ?? 10 ;
        $page     = $request->page ?? 1 ;
        $comments = Zendesk::tickets($id)->comments()->findAll(['per_page' => $per_page , 'page' => $page ]) ;
        return response()->json( ['ticket' => $ticket , 'comments' => $comments ] ) ;
    }

    public function update($id , Request $request){

        $data = [
            'status' => $request->status ?? 'open' ,
        ];

        if( isset($request['priority']) ){
            $data['priority'] = $request['priority'] ?? 'high' ;
        }

        if( isset($request['comment']) ){
            $data['comment']['body'] = $request->comment ;
            if( isset($request['attachments']) && !empty($request['attachments']) ){
                $data['comment']['uploads'] = [] ;
                foreach( $request['attachments'] as $file ){
                   
                    $url = $file['response'] ;
                    $type = get_headers($url, 1)["Content-Type"] ;
                    $name = str_replace(['/' , '.'],'', strrchr( strrchr($url , '/') , '.' )) ;

                    $url_file = public_path().strrchr( $url , '/uploads' ) ;
 
                    $attachment = Zendesk::attachments()->upload([
                        'file' => $url_file ,
                        'type' => $type ,
                        'name' => $name 
                    ]);
                    $data['comment']['uploads'][] = $attachment->upload->token ;
                }
                  
            }
        }

        $ticket = Zendesk::tickets()->update($id , $data );
        return 1 ; // response()->json( $ticket ) ;
    }


    public function active($id , Request $request){
        $this->update( $id , $request ) ;
        return 1 ;
    }

    public function destroy($id , Request $request){
        $ticket = Zendesk::tickets()->delete($id);
        return response()->json( ['res' => 1] ) ;
    }

}