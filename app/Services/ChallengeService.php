<?php

namespace App\Services;

use App\Challenges;
use App\Notifications\UNotify;
use App\Repositories\ChallengeRepository;
use App\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Notification;

use DB ;

class ChallengeService extends BaseService{

    public function __construct(ChallengeRepository $repo)
    {
        parent::__construct($repo);
    }

    public function addChallenge($user_id, $subjects, $skill, $competitors, $questions_number, $time = 3600){
        $challenge =  $this->repo->CreateChallenge($user_id , $subjects , $skill , $questions_number , $time);
        foreach($competitors as $competitor){
          $this->repo->AddChallengers($challenge, $competitor);
          $this->notifyCompetitor(User::find($competitor), $challenge);
        }
        return $challenge;
    }

    protected function notifyCompetitor(Authenticatable $user, Challenges $challenge){
        Notification::send( $user , new UNotify( ['challenge_id' => $challenge->id] , 'new_challenge' , 'ar') ) ;
    }

    //Done && not yet
    public function getChallengeList(Authenticatable $user){
        // Here's fix to get My Challenge ans User Challengeres with me
        $challenges = $this->repo->with(['Challengers.user','User','Exam.Results'])->where(['user_id' => $user->id])->OrWhereHas('challengers' , function($query) use ($user) {
            $query->where('user_id' , $user->id ) ;
        })->paginate(100) ;
        
        $challengeList = [];
     
        foreach($challenges as $challenge){
            $challenge->status = "لم يتم التحدي الى الآن";
             if($challenge->Exam->Results->count()){
                 if(in_array($user->id, $challenge->Exam->Results->pluck('user_id')->toArray())){
                    $challenge->status = "تم التحدى";
                    $userResult = array_values($challenge->Exam->Results->filter(function($result) use($user){
                        return $result['user_id'] == $user->id;
                    })->toArray());
              
                    $challenge->user->results = count($userResult) ? $userResult[0] : [];
                 }
             }

             $challengeArray =  $challenge->toArray();
             $challengerId = $challengeArray['challengers'][0]['user_id'];
 
             $challengerResult = array_filter($challengeArray['exam']['results'], function($result) use($challengerId){
                return $result['user_id'] == $challengerId;
             });
             $challengeArray['challengers'][0]['results'] = isset( $challengerResult[0]) ?  $challengerResult[0] : [];
 
             //$challengeArray['challengers']
             array_push($challengeList, $challengeArray);
        }
  
        return $challengeList;
    }

}



?>