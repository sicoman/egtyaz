<?php

namespace App\Http\Controllers;

use App\Models\examAnswers;
use App\Models\Exams;
use App\Questions;
use Illuminate\Http\Request;
use App\Challenges ;

use DB ;

class ExamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = 10 ; if( $request->limit > 0 ){ $limit = $request->limit ; }

        $exams = Exams::with('user:id,name')->where('type' , $request->type ?? 'free' );

        if( isset($request->user_id) && (int)$request->user_id > 0 ){ $exams->where('student_id' , (int)$request->user_id ) ; }

        if( isset($request->level_id) && (int)$request->level_id > 0 ){ $exams->where('level_id' , (int)$request->level_id ) ; }

        if( isset($request->created) ){  $exams->whereBetween('created_at' , $request->date ) ; }

        if( isset($request->subjects) ){ 
            $exams->whereHas('Subjects' , function($query) use ($request) {
                $query->whereIn( 'subject_id' , $request->subjects ) ;
            } ) ;
        }

        if( isset($request->has_results) ){ 
            $exams->Has('Results') ;
        }

        $list = $exams->with('Results')->with('Subjects.subject:id,name')->with('Level:id,name')->orderBy('id' , 'DESC')->paginate( $limit );

        logy( auth()->user()->id, 'index' , '' , 'exams' , [] );
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
               
        $id = $request->id ; $isEdit = 0 ;
        if( $id > 0 ) {
             $isEdit = 1 ;

             // Lets Delete All Exam Subjects - Questions
             Exams::find($id)->subjects()->delete() ;
             Exams::find($id)->skills()->delete() ;
             if( !empty($request->questions) ){
                Exams::find($id)->questions()->whereNotIn('id' , $request->questions)->delete() ;
             }else{
                Exams::find($id)->questions()->delete() ; 
             }

             Exams::find($id)->update([
                'title' => $request->title ?? '' ,
                'type' => $request->type ?? 'free' ,
                'student_id' => $request->student_id ?? null ,
                'questions_count' => $request->questions_count ,
                'available_time' => $request->available_time ,
                'level_id'  => $request->level_id ,
                'parent'  => $request->parent ?? '' 
             ]) ;
             $exam = Exams::find($id) ;

            
        }else{
            $exam = new Exams ;
             $exam = $exam->fill([
                'title' => $request->title ?? '' ,
                'type' => $request->type ?? 'free' ,
                'student_id' => $request->student_id ?? null ,
                'questions_count' => $request->questions_count ,
                'available_time' => $request->available_time ,
                'level_id'  => $request->level_id  ,
                'parent'  => $request->parent ?? '' 
             ]) ;
             $exam->save() ;
             $id = $exam->id ;
        }


        // Lets Attach Exam Questions - Subjects
        foreach( $request->subjects as $subject ){
            if( $subject == 0 ){ continue ; }
            $exam->subjects()->create([
                'subject_id' => $subject 
            ]) ;
        }

        foreach( $request->skills as $skill ){
            if( $skill == 0 ){ continue ; }
            $exam->skills()->create([
                'skill_id' => $skill 
            ]) ;
        }

        

        if( !empty($request->questions) ){
            foreach( $request->questions as $question ){
                if( $question == 0 ){ continue ; }
                DB::table('exams_questions')->insertOrIgnore([
                    'exam_id'   => $exam->id ,
                    'question_id' => $question 
                ]) ;
            }
        }else{
            //  $this->createExamQuestions( $exam , $request->subjects ) ;
        }

        if( $request->type == 'challenge' ){
            if( $isEdit == 0 ){
                // Lets Create Challenge
                $challenge = Challenges::create([
                    'exam_id' => $exam->id  ,
                    'user_id' => $exam->student_id  
                ]);
                foreach($request->challengers as $challenger){
                    DB::table('challengers')->insertOrIgnore([
                        'challenge_id' => $challenge->id ,
                        'user_id' => $challenger ,
                        'status'  => 0  
                    ]);
                }
            }else{
                // Delete From Challengers Where Not in list Challengers
                if( $exam->Challenge ){
                    $exam->Challenge->Challengers()->whereNotIn( 'id' , $request->challengers )->delete() ;
                }

                foreach($request->challengers as $challenger){

                    DB::table('challengers')->insertOrIgnore([
                        'challenge_id' => $exam->challenge->id ,
                        'user_id' => $challenger ,
                        'status'  => 0  
                    ]);

                }
            }

            if( $request->copy != '' && $request->copy > 0 ){
                $copy = Exams::find($request->copy) ;
                if( isset($copy->id) ){
                    $CopyQuestions = $copy->questions()->get() ;
                    foreach( $CopyQuestions as $qu ){
                         $exam->questions()->create([
                             'question_id' => $qu->question_id 
                         ]);
                    }
                }
            }
        }


        
        return $id ;
    }

    protected function createExamQuestions( $exam , $subjects ){
          // Get Random Questions From Questions Table
          $questions = Questions::where('status' , 1) ;
          if( isset($exam->level_id) && $exam->level_id > 0 ) {
            $questions->where('level_id' , $exam->level_id) ;
          }
          if( isset($subjects) && !empty($subjects) ) {
            $questions->whereIn('subject_id' , $subjects ) ;
          }

          $questionsList = $questions->limit( $exam->question_count )->pluck('id') ;

          if( !empty($questionsList) ){
                foreach( $questionsList as $question ){
                    if( $question == 0 ){ continue ; }
                    $exam->questions()->create([
                        'question_id' => $question 
                    ]) ;
                }
          }

          return $exam ;
          
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exams  $exams
     * @return \Illuminate\Http\Response
     */
    public function getExam($id = 0 , Exams $exams){
        return $exams->where('id' , $id)->with('questions.question')->with('subjects.subject')->with('skills.skill')->with('challenge.challengers')->with('user:id,name')->first() ;
    }

    public function show($id, Exams $exams , Request $request , ExamAnswers $answer)
    {
 
        if( isset($request->is_add_edit) ) {
             return $this->getExam( $id , $exams ) ;
        }

        $exam = $exams->where('id' , $id)->with('user:id,name')->with('Results.student:id,name')->with('Subjects.subject:id,name')->with('Skills.skill:id,name')->with('level:id,name')->first();
    
        $answers = [] ;

        if( !isset($request->student) || $request->student == 0 ){
            $student_id = $exam->student_id ;
        }else{
            $student_id = (int) $request->student ;
        }

        $answers = $answer->where('exam_id' , $id)->where('student_id' , $student_id)->with('question:id,title')->with('answer:id,text')->get() ;
          
        return ['exam' => $exam , 'answers' => $answers];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exams  $exams
     * @return \Illuminate\Http\Response
     */
    public function edit(Exams $exams)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exams  $exams
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exams $exams)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exams  $exams
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam = Exams::findOrFail($id);
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        $delete = $exam->delete();
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
        logy( auth()->user()->id , 'delete' , '' , 'booking' , [] );
        return response()->json( $delete , 204);
    }

    public function active($id , Request $request)
    {
        $exam = Exams::findOrFail($id);

        $arr = [ 'status' => (int) $request->status ] ;
        
        $exam->update($arr) ;
        
        logy( auth()->user()->id , 'active' , '' , 'exams' , [] );
        return response()->json(null, 204);
    }

    public function select($type = 'mock')
    {
        return Exams::where('type' , $type )->select( [ 'title' , 'id' ])->orderBY('id' , 'DESC')->get() ;
    }
}
