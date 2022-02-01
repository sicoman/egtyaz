<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use \App\GraphQL\Type\MessagesMutationType;
use \App\GraphQL\Type\MessagesQueryType;
USE \EzuruCustom\Core\Traits\FlatParametersToObjs;

use Auth ;
use App\Models\Messages ;
use App\Models\MessageText ;

class MessagesController extends MessagesQueryType
{
    use FlatParametersToObjs;
    /**  
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(){
        
    }

    public function index(Request $request)
    {
       $request->request->add(['rest' => true]) ;     
       $params = array_keys((new \App\GraphQL\Type\MessagesType())->fields()); 
       $list = (new MessagesQueryType())->resolveGetAllField($root = true, $this->convert($request->toArray())->packParam($params, 'Messages')->mergeParams());
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
        
        $params = array_keys((new \App\GraphQL\Type\MessagesType())->fields()); 
        $messages = array_keys((new \App\GraphQL\Type\MessageTextType())->fields()); 
        return (new MessagesMutationType())->resolveCreateField($root = true, $this->convert($request->toArray())->packParam($params, 'Messages',['user_id'])->packParam($messages, 'Item')->mergeParams());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id , Request $request)
    {
        $user = Auth::user()->id ;
        
        $message = Messages::where('id' , $id)->whereRaw( $user.' IN ( user_id , owner_id ) ' )->with('owner')->with('guest')->first() ;

        // Chat List
        $chat = [] ;
        if( $message->id ){
            $chat = MessageText::where('message_id' , $id)->orderBy('created_at' , 'DESC')->paginate($request->limit ?? 10) ;

            if( isset( $request->page ) && $request->page > 0 ){
                // Lets Read All Messages Unreaded
                MessageText::where('message_id' , $id )->where('user_id' , '!=' , $user )->where('readed' , '0000-00-00 00:00:00')->update([
                    'readed' => date('Y-m-d h:i:s') 
                ]);
            }

        }

        return [ 'message' => $message , 'chat' => $chat ] ;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $params = array_keys((new \App\GraphQL\Type\MessagesType())->fields()); 
        return (new MessagesMutationType())->resolveCreateField($root = true, $this->convert($request->toArray())->packParam($params, 'Messages')->mergeParams());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
