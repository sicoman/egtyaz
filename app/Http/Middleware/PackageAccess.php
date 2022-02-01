<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;

class PackageAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $gate = null)
    {       
        $paymentService = resolve("App\Services\PaymentService");
        $middlewares = $request->route()->computedMiddleware;   
        $gates = [
            'bank'        => 'bank',
            'ask_teacher'  => 'ask_teacher',
            'exam'        => ['free_exam','mock_exam'],
            'videos'      => 'videos',
            'foundation'  => 'foundation',
            'challenges'  => 'challenges',
            'comunity'    => 'comunity' ,
            'courses'     => 'courses'
        ];

        $gateVals = Arr::flatten(array_values($gates));

        $examMap = [
            'exam' => 'free_exam',
            'mock' => 'mock_exam',
            'free' => 'free_exam'
        ];

        if(in_array("App\Http\Middleware\PackageAccess:exam", $middlewares)){  
            $action = $request->route()->parameter('type');    
            if($action == null){
                $action = "exam";
            }     
            $mappedExamType = $examMap[$action];   
            $gateIndex = array_search($mappedExamType, $gates['exam']);  
            $gate = $gates['exam'][$gateIndex]; 
        }

        
        
        if(in_array($gate, $gateVals)){  
            $index = array_search($gate, $gateVals);  
            $hasAccess = $paymentService->pkgHasAccess(Auth::user()->id, $gateVals[$index]);  
            if(!$hasAccess['res']){ 
                if($hasAccess['message'] == "expired"){
                    if (Request::is('api*')){
                        return response()->json(['access' => false, 'reason' => 'subscription_expired']);
                    }
                    return redirect(route("cpanel"))->with('toast-success', "عفوا انتهت فتره اشتراكك بالمنصة يرجى تجديد العضويه للاستمتاع بجميع الخدمات المقدمه لدى منصة اجتياز التعليمية" );
                }else{
                    if (Request::is('api*')){
                        return response()->json(['access' => false, 'reason' => 'no_subscription']);
                    }
                    return redirect(route("packages"));
                }
            }
        }else{
            /*
            if (Request::is('api*')){
                return response()->json(['access' => false, 'reason' => 'no_subscription']);
            }
            return redirect(route("packages"));
            */
        }
        
        return $next($request);
    }
}
