<?php

namespace App\Services;

use App\Repositories\ExamsAnswersRepository;
use App\Repositories\ExamsRepository;


class ExamsService extends BaseService{

    protected $examAnswersRepo;

    public function __construct(ExamsRepository $repo, ExamsAnswersRepository $examAnswersRepo)
    {
        parent::__construct($repo) ;
        $this->examAnswersRepo = $examAnswersRepo;
    }

    public function getExams($user_id){
       $exams = $this->repo->with('Results')->where(['student_id' => $user_id])->paginate(5);
       foreach( $exams as $key => $exam ){
            $exam->title = mb_substr( html_entity_decode( strip_tags( $exam->title )) , 0 , 50 , 'utf-8' ) ;
       }
       return $exams;
    }

    public function getExamAnswers($user_id, $exam_id, $paginate = 15){
    
        $examDetails = $this->examAnswersRepo->with(['Answer','Question'])
        ->where(['student_id' => $user_id, 'exam_id' => $exam_id])
        ->paginate($paginate);

        $examWithResults = $this->repo->with(['Results' => function($query) use($user_id){  
           return $query->where('user_id', $user_id);
        }])->where(['id' => $exam_id, 'student_id' => $user_id])->first();
 
        return ['examDetails' => $examDetails, 'examWithResults' => $examWithResults];
    }

  


}



?>