<?php

namespace App\Http\Controllers\Edu;

use App\Questions;
use App\Answers ;

use App\Models\examQuestions ;

use Illuminate\Http\Request;

use  App\Http\Controllers\Controller ;
use App\Models\Exams;
use DB ;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request )
    {
        $limit = $request->limit ?? 10 ;

        logy( auth()->user()->id , 'index' , '' , 'questions' , [] );

        $result = Questions::orderBy('id' , 'DESC') ;

        if( isset($request->s) && $request->s != '' ){
            if( is_numeric($request->s) ){
                $result->where("id" , $request->s ) ;
            }else{
                $result->where("title" , "LIKE" , "%".$request->s."%" ) ;
            }
        }

        if( isset($request->status)  ){
            $result->where( 'status' , (int) $request->status ) ;
        }

        if( isset($request->category)  ){
            $result->where( 'category_id' , (int) $request->category ) ;
        }

        if( isset($request->subject)  ){
            $result->where( 'subject_id' , (int) $request->subject ) ;
        }

        if( isset($request->skill)  ){
            $result->where( 'skill_id' , (int) $request->skill ) ;
        }

        if( isset($request->level)  ){
            $result->where( 'level_id' , (int) $request->level ) ;
        }

        if( isset($request->id)  ){
            $result->where( 'id' , (int) $request->id ) ;
        }

        $result = $result->with('category')->with('level')->with('skill')->with('subject')->with('answers')->paginate($limit) ;

        foreach( $result as $k => $item ){
            // $item->title = html_entity_decode(strip_tags( $item->title )) ;
            $result[$k] = $item ;
        }


        return response()->json( $result ) ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        if( $request->id > 0 ){
            return $this->update( $request , $request->id ) ;
        }

        $validate = $request->validate([
          //  'name' => 'required|unique:packages|max:255',
        ]);

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;

        $inputs = $request->only( 'title' , 'category_id' ,'subject_id' , 'skill_id' , 'level_id' , 'description', 'attachment_id' , 'status') ;

        logy( auth()->user()->id , 'add' , '' , 'questions' , [] );

        $inputs['status'] = (int)$inputs['status'] ;

        $create = Questions::create($inputs) ;

        if( $create->id > 0 && !empty( $request->answers ) ) {
            foreach( $request->answers as $answer ){
                if( is_array($answer) ){ $answer = (object) $answer ; }
                Answers::create([
                    'question_id' => $create->id ,
                    'is_true'     => (int) $answer->is_true ,
                    'text'        => $answer->text  ,
                    'status'      => $answer->status  ?? 1 
                ]) ;
            }

            if( isset($request->exam_id) && !empty($request->exam_id) ) {
                $exams = 'INSERT IGNORE INTO exams_questions VALUES ' ;
                foreach( $request->exam_id as $ex ){
                    $exams .= '( null , '.(int)$ex.' , '.(int)$create->id.' ) ,';
                }
                $exams = rtrim( $exams , ',' ) ;
                DB::statement( $exams ) ;
            }
        }

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

        Exams::Clean() ;

        return response()->json($create) ;
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
        
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        $validate = $request->validate([
            'title' => 'required|max:1000',
        ]);

        $package = Questions::findOrFail($id) ;

        logy( auth()->user()->id , 'edit' , '' , 'questions ', [] );

        $inputs = $request->only('title' , 'category_id' ,'subject_id' , 'skill_id' , 'level_id' , 'description', 'attachment_id' , 'status') ;

        $inputs['status'] = (int)$inputs['status'] ;

        foreach($inputs as $field => $value) {
            $package->{$field} = $value ;
        }

        $package->save() ;

        if( !empty( $request->answers ) ) {
            Answers::where('question_id' , $id)->delete() ;
            foreach( $request->answers as $answer ){
                if( is_array($answer) ){ $answer = (object) $answer ; }
                Answers::create([
                    'question_id' => $id ,
                    'is_true'     => (int) $answer->is_true ,
                    'text'        => $answer->text ,
                    'status'      => $answer->status   
                ]) ;
            }
        }

        if( isset($request->exam_id) && !empty($request->exam_id) ) {
            $exams = 'INSERT IGNORE INTO exams_questions VALUES ' ;
            foreach( $request->exam_id as $ex ){
                $exams .= '( null , '.(int)$ex.' , '.(int)$id.' ) ,' ;
            }
            $exams = rtrim( $exams , ',' ) ;
            DB::statement( $exams ) ;

        }

        Exams::Clean() ;

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;

        return response()->json($package) ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $package = Questions::find($id) ;

        if($package){
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
            $package = $package->delete() ;
            DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
            logy( auth()->user()->id , 'delete' , '' , 'questions' , [] );
        }else{
            $package = false;
        }
        return response()->json(['res' => $package]) ;
    }

    public function active($id , Request $request)
    {
        $package = Questions::find($id) ;
        if($package){
            $package = $package->update([ 'status' => (int) $request->status ]) ;
            logy( auth()->user()->id , 'status' , '' , 'packages' , [] );
        }else{
            $package = false;
        }
        return response()->json(['res' => $package]) ;
    }

    public function select(Request $request)
    {
        DB::enableQueryLog();

        $post = $request->json()->all() ;

        $questions = Questions::where('status' , 1) ;

        if( isset($post['s'][2]) ){
            $questions->where('title' , 'like' , '%'.$post['s'].'%') ; 
        }

        if( isset($post['level_id']) && $post['level_id'] != '' ){
            $questions->where('level_id', $post['level_id']) ; 
        }

        if( isset($post['subjects']) && !empty($post['subjects']) ){
            $questions->whereIn('subject_id', $post['subjects']) ; 
        }

         $list = $questions->select(['id' , 'title'])->get();

         foreach( $list as $k => $item ){
            $item->title =  mb_substr( trim(html_entity_decode( strip_tags( $item->title ))) , 0 , 40 , 'utf-8') ;
            $list[$k] = $item ;
         }

         // dd( DB::getQueryLog() ) ;

         return $list ;

        
    }

    public function bulk( $action  , Request $request ){
         
        $ret = true ;

        if( $action == 'delete' ) {
           $ret =  Questions::whereIn('id' , $request->questions)->delete() ;
        }else{
            if( !empty( $request->exams ) && !empty( $request->questions ) ) {
                 examQuestions::whereIn('exam_id' , $request->exams )->whereIn('question_id' , $request->questions)->delete() ;
                 foreach( $request->exams as $exam ) {
                      foreach( $request->questions as $q ){
                            examQuestions::create([
                                'exam_id' => $exam ,
                                'question_id' => $q
                            ]) ;
                      }
                 }
            }
            $ret = true ;
        }

        return response()->json(['res' => $ret ]) ;
    }

}