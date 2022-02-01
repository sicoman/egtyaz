<?php

namespace App\Repositories;

use App\Repositories\BaseRepository ;

use Illuminate\Container\Container as Application;

use App\Models\Exams ;

use App\Challengers ;
use App\Models\examSubjects;
use App\Questions ;
use Exception;
use stdClass;

/**
 * Interface ChallengeRepositoryRepository.
 *
 * @package namespace App\Repositories;
 */
class ChallengeRepository extends BaseRepository
{

    public function __construct(Application $app , Exams $exams , Challengers $challengers , Questions $questions)
    {
        parent::__construct($app); 

        $this->exams = $exams ;
        $this->challengers = $challengers ;
        $this->questions = $questions ;
    }

    public function model(){
        return ('App\\Challenges');  
    }

    public function CreateChallenge($challenger , $subjects , $level , $Qcount , $time ){
            // Lets Create Exam
            $exam = $this->exams->create([
                'type' => 'challenge' ,
                'student_id' => $challenger ,
                'level_id' => $level ,
                'questions_count' => $Qcount , 
                'available_time' => $time
            ]) ;

            if( isset($exam->id) && $exam->id > 0 ) {
                // Lets Create Exam Subjects
                foreach( $subjects as $subject ){
                    $exam->Subjects()->create([
                        'subject_id' => $subject
                    ]);
                }
               
                $challenge = $this->model->create([
                    'exam_id' => $exam->id ,
                    'user_id' => $challenger 
                ]);

                return  $challenge ;

            }

            return false ;
            
    }

    public function AddChallengers($challengeObject , $challenger , $status = 0 ){
        return $challengeObject->Challengers()->create(
            [
                'user_id' => $challenger  ,
                'status' => $status
            ]
        ) ;
    }

}
