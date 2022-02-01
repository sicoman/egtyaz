<?php

namespace App\Services;

use App\Courses ;

use App\Repositories\PaymentRepository;
use App\Utils\PackagePropertiesUtil;
use Srmklive\PayPal\Facades\PayPal ;
use Carbon\Carbon;
use Exception;



class PaymentService extends BaseService{

    protected $userService;
    protected $packageService;
    protected $pointsService;
    protected $paypalService;
    protected $visaService;
    protected $couponService;
    protected $courses ;
    protected $payPalNewService ;
    protected $paytabsService ;

    public function __construct(PaymentRepository $paymentRepository, UserService $userService, PackageService $packageService, Courses $courses, PointsService $pointsService, PaypalService $paypalService , BankMisrService $visaService , PaypalFormService $payPalNewService , PaytabsService $paytabsService , CouponService $couponService)
    {
        parent::__construct($paymentRepository) ;
        $this->userService = $userService;
        $this->packageService = $packageService;
        $this->pointsService = $pointsService;
        $this->paypalService = $paypalService;
        $this->visaService   = $visaService;
        $this->couponService = $couponService;
        $this->courses = $courses;
        $this->payPalNewService = $payPalNewService ;
        $this->paytabsService = $paytabsService ;
    }

    public function createPayment($package_id, $user_id, $cost, $fee, $total, $is_paid = false, $gateway = "points" , $type = 'package'){

        $paymentData = [];
        $paymentData['gateway'] = $gateway;
        $paymentData['type'] = $type ?? 'package';
        $paymentData['package_id'] = $package_id;
        $paymentData['user_id'] = $user_id;
        $paymentData['cost'] = $cost;
        $paymentData['fee'] = $fee;
        $paymentData['total'] = $total;
        $paymentData['is_paid'] = $is_paid;
        $paymentData['status'] = $is_paid;

        $payment = $this->add($paymentData);
        return $payment;
    }

    public function getPaypal(){
        return $this->paypalService;
    }

    public function getVisa(){
        return $this->visaService;
    }

    public function buyFree($package_id, $user_id , $type = 'package'){
        if($this->userHasFreePackages($user_id)){
            return false;
        }
        $payment = $this->createPayment($package_id, $user_id, 0, 0, 0, true, "free" , $type );
        return $payment;
    }
  
    public function buyByPaypal($package_id, $user_id, $coupon = false , $type = 'package'){

        $item = '' ;

        if( $type == 'course' ){
            extract($this->checkUserAndCourse($package_id, $user_id)) ;
            $item = $course ;
        }else{
            extract($this->checkUserAndPackage($package_id, $user_id)) ;
            $item = $package ;
        }
        

        if($coupon){
           $couponObj = $this->couponService->isCoupon($coupon);
           $item->price = $this->couponService->resolveCoupon($couponObj, $item->price);
           if($item->price == 0){
               return $this->buyFree($package_id, $user_id , $type);
           }
        }

        return $this->paypalService->buyByPaypal($item , $type);

    }

    public function buyByPaypalNew($package_id, $user_id, $coupon = false , $type = 'package'){

        $item = '' ;

        if( $type == 'course' ){
            extract($this->checkUserAndCourse($package_id, $user_id)) ;
            $item = $course ;
        }else{
            extract($this->checkUserAndPackage($package_id, $user_id)) ;
            $item = $package ;
        }

        if($coupon){
           $couponObj = $this->couponService->isCoupon($coupon);
           $item->price = $this->couponService->resolveCoupon($couponObj, $item->new_price);
           
        }else{
            $item->price = $item->new_price;
        }

        if($item->price == 0){
            return $this->buyFree($package_id, $user_id , $type);
        }

        $paymentId = $this->createPayment( $package_id, $user_id , $item->price , 0 , $item->price , false , 'paypal' , $type ) ;

        return $this->payPalNewService->buyNow( $paymentId );

    }

    public function buyByVisa($package_id, $user_id, $coupon = false , $type = 'package'){

        $item = '' ;

        if( $type == 'course' ){
            extract($this->checkUserAndCourse($package_id, $user_id)) ;
            $item = $course ;
        }else{
            extract($this->checkUserAndPackage($package_id, $user_id)) ;
            $item = $package ;
        }

        if($coupon){
           $couponObj = $this->couponService->isCoupon($coupon);
           $item->price = $this->couponService->resolveCoupon($couponObj, $item->new_price);
           if($item->price == 0){
               return $this->buyFree($package_id, $user_id , $type);
           }
        }else{
            $item->price = $item->new_price ;
        }

        $paymentId = $this->createPayment( $package_id, $user_id , $item->price , 0 , $item->price , false , 'visa' , $type ) ;

        return $this->visaService->buyByVisa( $paymentId );

    }

    public function buyByPaytabs($package_id, $user_id, $coupon = false , $type = 'package'){

        $item = '' ;

        if( $type == 'course' ){
            extract($this->checkUserAndCourse($package_id, $user_id)) ;
            $item = $course ;
        }else{
            extract($this->checkUserAndPackage($package_id, $user_id)) ;
            $item = $package ;
        }

        if($coupon){
           $couponObj = $this->couponService->isCoupon($coupon);
           $item->price = $this->couponService->resolveCoupon($couponObj, $item->new_price);
        }else{
            $item->price = $item->new_price ;
        }

        if($item->price == 0){
            return $this->buyFree($package_id, $user_id , $type);
        }

        $paymentId = $this->createPayment( $package_id, $user_id , $item->price , 0 , $item->price , false , 'paytabs' , $type ) ;

        return $this->paytabsService->buyByPaytabs( $paymentId );

    }

    public function checkUserAndPackage($package_id, $user_id){

        $user = $this->userService->find($user_id);
        if(!$user){
            throw new Exception("مستخدم غير صحيح");
        }
 
        $package = $this->packageService->find($package_id);
        if(!$package){
         throw new Exception("باقة غير صحيحة");
        }
        
        return compact('user', 'package');

    }

    public function checkUserAndCourse($course_id, $user_id){

        $user = $this->userService->find($user_id);
        if(!$user){
            throw new Exception("مستخدم غير صحيح");
        }
 
        $course = $this->courses->find($course_id);
        if(!$course){
         throw new Exception("دورة غير صحيحة");
        }
        
        return compact('user', 'course');

    }

    public function buyByPoint($package_id, $user_id){

        extract($this->checkUserAndPackage($package_id, $user_id)) ;

       if($package->points == 0){
        throw new Exception("هذه الباقة غير متاحة للشراء من خلال النقاط");
       }

       if($package->points > $user->points){
           throw new Exception("لا يوجد لديك عدد نقاط مناسبة لشراء الباقة");
       }

       $payment = $this->createPayment($package_id, $user_id, $package->points, 0, $package->points, true , 'package');

       if($payment){
        $this->pointsService->subtract($user, $package->points);
       }

       return $payment;

    }

    public function userHasFreePackages($user_id){
       $pkgs = $this->repo->where(['user_id' => $user_id, 'is_paid' => true, 'gateway' => 'free'])->count();
       return $pkgs;
    }

    public function getUserPkgs($user_id){

       $pkgs = $this->repo->with(["Package"])->where(['user_id' => $user_id, 'is_paid' => true])->get();
       $payments = [];
       if( count($pkgs) < 1 ){
           return $payments ;
       }
       $pkgs->each(function($pkg) use(&$payments){   

          array_push($payments, [
              "startTime" => $pkg->created_at->format("Y-m-d"),
              "endTime" => Carbon::parse($pkg->created_at)->addDays($pkg->Package->period)->format("Y-m-d"),
              "price" => $pkg->Package->points > 0 ? $pkg->Package->points : $pkg->Package->price,
              "byPoints" => $pkg->Package->points > 0 ? true : false,
              "package" => $pkg->Package->toArray()
          ]);
    
       });
       return $payments;
    }

    public function pkgHasAccess($user_id, $action){
       $res = ['res' => false, 'message' => ''];
       $hasAccess = false; 
       $payment = $this->repo->orderBy('id','desc')->where(['status' => true, 'is_paid' => true, 'user_id' => $user_id])->with(['Package'])->latest()->first();
       if($payment && $payment->package){
            $dateNow = now() ;
            if( 
                $payment->created_at <= $dateNow
                &&
                $dateNow < Carbon::parse($payment->updated_at)->addDay($payment->package->period)
            ){
               $defaultProps = PackagePropertiesUtil::defaultProps();
               $packageRoles = array_merge($defaultProps, json_decode($payment->package->roles, true));   
               if(isset($packageRoles[$action])){
                   $res['res'] = true;
               }else{
                   $res['res']     = false;
                   $res['message'] = 'expired';
               }
            }else{
                $res['res']     = false;
                $res['message'] = 'expired';
            }
       }
       
       return $res;
    }

}
