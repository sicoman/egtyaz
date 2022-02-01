<?php 

namespace App\Services;

use App\Challenges;
use App\Notifications\UNotify;
use App\Repositories\ChallengeRepository;
use App\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Notification;

use DB ;
use App\Models\Payments ;

class PaytabsService extends BaseService{
    
        public function __construct()
        {

        }
    
        public function buyByPaytabs( $item ){
            return route('pt.form' , $item ) ;
        }
    
    }