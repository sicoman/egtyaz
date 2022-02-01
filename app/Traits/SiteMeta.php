<?php

namespace App\Traits;

use Illuminate\Support\Facades\View;

trait SiteMeta{

    protected $title, $keywords, $description ; 

    public $BASE_TITLE = 'اجتياز';

    public function getTitle(){
       return $this->title ;
    }

    public function setMeta($key, $value){
        $this->{$key} = $value ;
    }

    public function meta(){
        $this->BASE_TITLE = env('APP_NAME' , 'اجتياز') ;
        return [
            'title'       => $this->BASE_TITLE .' | ' . $this->title,
            'keywords'    => $this->keywords,
            'description' => $this->description
        ];
    }

    public function registerSiteMeta(){
        return View::share('meta', $this->meta());
    }



}