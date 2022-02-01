<?php

namespace App\Http\Controllers;

use App\Laravue\Models\Role;
use App\Models\Exams;
use App\Courses ;
use App\Models\examResults ;
use App\Models\Payments;
use App\Questions;
use Illuminate\Http\Request;

use App\User ;

use DB ;

class DashboardController extends Controller {

    public function index(Request $request){
        $Auth = auth()->user() ;
        $roles = [] ;
        foreach($Auth->roles as $rol){
            $roles[] = $rol->name ; 
        }

        logy( auth()->user()->id , 'index' , '' , 'dashboard' , [] );

        return [] ;


    }

    public function index2(Request $request){

        $Auth = auth()->user() ;

        logy( auth()->user()->id , 'index' , '' , 'dashboard' , [] );

        return [] ;
    }

    public function stat(Request $request){
        return [
            'users' => $this->users($request) ,
            'questions' => $this->questions($request),
            'exams' => $this->exams($request),
            'payments' => $this->payments($request) ,
            'courses' => $this->courses($request)
        ] ;
    }

    public function users($request){

        $start = date('Y-m-d' , strtotime('-50 year')) ; $end = date('Y-m-d') ;

        if( isset($request->start[3]) && !isset($request->packages[0]) ){ $start = $request->start ;}
        if( isset($request->end[3]) && !isset($request->packages[0]) ){ $end = $request->end ;}

        $all  = User::selectRaw('count( users.id ) as c')
        ->where('created_at' , '>=' , $start.' 00:00:00')->where('created_at' , '<=' , $end.' 23:59:59');

        $role = User::selectRaw('count( users.id ) as c , model_has_roles.role_id , roles.name')
                        ->leftJoin('model_has_roles' , 'users.id' , 'model_id')
                        ->leftJoin('roles' , 'roles.id' , 'model_has_roles.role_id')
                        ->groupBy('role_id')
                        ->where('users.created_at' , '>=' , $start.' 00:00:00')->where('users.created_at' , '<=' , $end.' 23:59:59') ;

        $gender = User::selectRaw('count( users.id ) as c , gender')
                        ->groupBy('gender')
                        ->where('created_at' , '>=' , $start.' 00:00:00')->where('created_at' , '<=' , $end.' 23:59:59');

        $mar7la = User::selectRaw('count( users.id ) as c , stage_id , taxonomies.name as name')
                        ->leftJoin('taxonomies' , 'taxonomies.id' , 'stage_id')->groupBy('stage_id')
                        ->where('users.created_at' , '>=' , $start.' 00:00:00')->where('users.created_at' , '<=' , $end.' 23:59:59') ;
         

        if( isset($request->gender) && $request->gender != '' ){
                            $role->where('gender' , $request->gender) ;
                            $gender->where('gender' , $request->gender) ;
                            $mar7la->where('gender' , $request->gender) ;
        }

        if( isset($request->start[3]) ){ $start = $request->start ;}
        if( isset($request->end[3]) ){ $end = $request->end ;}

        if( isset($request->packages) && $request->packages != '' ){
            $role->whereHas(['payments' => function($q) use ($request){
              return  $q->where('type' , 'package')->where('paid' , 1)->where('package_id' , $request->package)
                ->whereBetween('created_at' , [$start.' 00:00:00' , $end.' 23:59:59' ] );
            }]) ;

            $gender->whereHas(['payments' => function($q) use ($request){
              return  $q->where('type' , 'package')->where('paid' , 1)->where('package_id' , $request->package)
                ->whereBetween('created_at' , [$start.' 00:00:00' , $end.' 23:59:59' ] );
            }]) ;

            $mar7la->whereHas(['payments' => function($q) use ($request){
               return $q->where('type' , 'package')->where('paid' , 1)->where('package_id' , $request->package)
                ->whereBetween('created_at' , [$start.' 00:00:00' , $end.' 23:59:59' ] );
            }]) ;

            $all->whereHas(['payments' => function($q) use ($request){
                return $q->where('type' , 'package')->where('paid' , 1)->where('package_id' , $request->package)
                 ->whereBetween('created_at' , [$start.' 00:00:00' , $end.' 23:59:59' ] );
             }]) ;
        }

        return [ 'all' => $all->get() , 'role' => $role->get() , 'gender' => $gender->get() , 'mar7la' => $mar7la->get()  ] ;
    }

    public function questions($request){
        $all      = Questions::selectRaw('count(id) as c')->get() ;

        $status   = Questions::groupBy('status')->selectRaw('count(id) as c , status')->get() ;


        $category = Questions::groupBy('category_id')->selectRaw('count(questions.id) as c , category_id , name')
                    ->leftJoin('taxonomies' , 'taxonomies.id' , 'category_id')->get() ;

        $subject  = Questions::groupBy('subject_id')->selectRaw('count(questions.id) as c , subject_id , name')
                    ->leftJoin('taxonomies' , 'taxonomies.id' , 'subject_id')->get() ;

        $skill   = Questions::groupBy('skill_id')->selectRaw('count(questions.id) as c , skill_id , name')
                    ->leftJoin('taxonomies' , 'taxonomies.id' , 'skill_id')->get() ;

        return ['all' => $all ,'status' => $status , 'category' => $category , 'subject' => $subject , 'skill' => $skill ] ;
    }

    public function exams($request){
        $all    = Exams::selectRaw('count(id) as c')->get() ;
        $type   = Exams::groupBy('type')->selectRaw('count(id) as c , type')->get() ;

        $results = Exams::join('exams_results' ,'exams.id' , 'exam_id')
                    ->selectRaw('count(exams.id) as c , type')
                    ->groupBy('type')->get();
        
        $resultsPer = Exams::join('exams_results' ,'exams.id' , 'exam_id')
                    ->selectRaw('count(exams.id) as c , IF( percent >= 50, "Success", "Fail") as result')
                    ->groupBy('result')->get();

        return [ 'all' => $all ,'type' => $type , 'examResults' => $results , 'resultPercents' => $resultsPer ] ;
    }

    public function courses($request){
        $all    = Courses::selectRaw('count(id) as c')->get() ;

        $status    = Courses::selectRaw('count(id) as c , status, IF( status = 1, "Active", "Disabled") as status_name')->groupBy('status')->get() ;

        $category = Courses::groupBy('taxonomy_id')->selectRaw('count(courses.id) as c , taxonomy_id , name')
                    ->leftJoin('taxonomies' , 'taxonomies.id' , 'taxonomy_id')->get() ;

        return [ 'all' => $all ,'category' => $category , 'status' => $status ] ;
    }

    public function payments($request){
        $all       = Payments::selectRaw('count(id) as c') ;
        $type      = Payments::groupBy('type')->selectRaw('count(id) as c , type') ;
        $gateway   = Payments::groupBy('gateway')->selectRaw('count(id) as c , gateway') ;

        if( isset($request->paid) && $request->paid != '' ){
            $all->where('is_paid' , $request->paid) ;
            $type->where('is_paid' , $request->paid) ;
            $gateway->where('is_paid' , $request->paid) ;
        }

        if( isset($request->type) && $request->type != '' ){
            $all->where('type' , $request->type) ;
            $type->where('type' , $request->type) ;
            $gateway->where('type' , $request->type) ;
        }

        if( isset($request->gateway) && $request->gateway != '' ){
            $all->where('gateway' , $request->gateway) ;
            $type->where('gateway' , $request->gateway) ;
            $gateway->where('gateway' , $request->gateway) ;
        }

        $start = date('Y-m-d' , strtotime('-50 year')) ; $end = date('Y-m-d') ;

        if( isset($request->start[3]) ){ $start = $request->start ;}
        if( isset($request->end[3]) ){ $end = $request->end ;}
        $all->whereBetween('created_at' , [$start.' 00:00:00' , $end.' 23:59:59' ] ); ;
        $type->whereBetween('created_at' , [$start.' 00:00:00' , $end.' 23:59:59' ] ); ;
        $gateway->whereBetween('created_at' , [$start.' 00:00:00' , $end.' 23:59:59' ] ); ;
        
        return ['all' => $all->get() ,'type' => $type->get() ,'gateway' => $gateway->get() ] ;
    }





}
