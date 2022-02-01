<?php

namespace App\Services;

use App\Repositories\NotificationsRepository;

class NotificationsService extends BaseService
{
    public function __construct(NotificationsRepository $repo)
    {
        parent::__construct($repo) ;
    }

    public function getNotifcationsWithUser($uid, $paginate = 15){
        return $this->repo->where(['notifiable_id' => $uid])->with('user')->paginate($paginate);
    }

}

?>