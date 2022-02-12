<?php

namespace App\Services;

use App\Models\PreviousUserQuestions;
use App\Repositories\QuestionsRepository;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request ;

class QuestionsService extends BaseService
{
    public function __construct(QuestionsRepository $repo)
    {
        parent::__construct($repo) ;
    }

    public function getBySkill($skill_id, $paginate = 15){
        return $this->repo->with('Answers')->where(['skill_id'=> $skill_id])->paginate($paginate);
    }

    public function getBySkills($skills_ids , $count = 15 ,$user_id, $old = '', $rand = '' , $not_in = [] , $in = [] ){
        $data =  $this->repo->with('Answers')->with('inWishList')->with('attachment')->whereIn('skill_id' , $skills_ids )->limit($count);
        if( $rand == 1 ){
            $data->InRandomOrder() ;
        }

        if( !empty( $not_in )  ){
            $data->whereNotIN('id' , $not_in ) ;
        }

        if( !empty( $in )  ){
            $data->whereIn('id' , $in ) ;
        }

        if($old == 0){
            $userQuestions = DB::table('previous_user_questions')
            ->select('question_id')
            ->where('user_id', '=', $user_id);

            $data->whereNotIn('id', $userQuestions);
        }

        return $data->with('itrue')->get() ;
    }

    public function getBySkillsNew($skills , $count = 15 , $user_id , Request $request){

        $oldExams = PreviousUserQuestions::where('user_id' , $user_id )->pluck('question_id') ;

        $this->questions = $this->repo ;

        $random = $request->random ?? '' ; $old = $request->old ?? '' ; $repeat = $request->repeat ?? '' ;

        if( isset($skills) && !empty($skills) ) {
            $all_ids = [] ; $perSkill = floor( $count / count($skills) ) ; $moreThan = 0 ;
            if( $count <> ( $perSkill * count($skills) ) ){ $moreThan = $count - ($perSkill * count($skills)) ; }

            foreach( $skills as $kx => $skill ){

                $questions = $this->questions->where('status' , 1)->where('skill_id' , $skill) ;

                if( $random == 'random' ) {
                    $questions->inRandomOrder() ;
                }

                if( $repeat != false ) {
                    $questions->whereNotin('id' , $oldExams) ;
                }

                if( $old != false ) {
                    $questions->whereIn('id' , $oldExams)->orWhere('id' , '>' , 0) ;
                }

                if( $skill == end($skills) ){
                    $questions->limit( $perSkill + $moreThan ) ;
                }else{
                    $questions->limit( $perSkill ) ;
                }

                $skill_ids = $questions->pluck('id')->toArray() ;

                foreach($skill_ids as $skid){
                    $all_ids [] = $skid ;
                }

                unset($questions) ;

            }


        }

        return $this->questions->whereIn('id' , $all_ids)->with('attachment')->limit( $count )->orderBy('skill_id')->orderBy('attachment_id' , 'DESC')->with('itrue')->with('answers')->get() ; //->distinct();

    }

    public function addtoPreviousQuestions($question_id, $user_id){
         PreviousUserQuestions::create(['user_id' => $user_id, 'question_id' => $question_id]);
    }

}

?>
