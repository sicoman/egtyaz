<?php

namespace App\Repositories;

class WishlistRepository extends BaseRepository{

    public function model()
    {
       return ('App\\Models\\WishList');
    }

    public function getWishList($user_id, $paginate = 15, $type="question", $questionDetails = []){
        $query =  $this->where(['user_id' => $user_id, 'type' => $type])->whereHas("Question" , function($query) use($questionDetails){
            if(!empty($questionDetails)){  
              $query->where($questionDetails);
            }
        });
        return $query->paginate($paginate);
    }

    public function addOrRemoveFromWishList($user_id, $key_id, $type = "question"){
       
        $inWishList = $this->where(['user_id' => $user_id, 'key_id' => $key_id, 'type' => $type])->first();
        $action = '';
        if(is_null($inWishList)){
            //Add
            $create = $this->create(['user_id' => $user_id, 'key_id' => $key_id, 'type' => $type]);
            $message= "تمت اضافة السؤال الى مفضلتك";
            $action  = 'add';
        }else{
            //Delete
            $delete = $inWishList->delete();
            $action  = 'delete';
            $message= "تمت ازالة السؤال الى مفضلتك";
        }

        return ['action' => $action, 'message' => $message];
        
    }

    public function deleteByIds($ids = []){  
        $delete = $this->whereIn('id', $ids)->delete();
        return $delete;
    }

}