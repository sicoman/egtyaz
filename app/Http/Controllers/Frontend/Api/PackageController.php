<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Frontend\FrontendController;
use App\Repositories\WishlistRepository;
use App\Services\PackageService;
use App\Services\PaymentService;
use App\Services\TaxonomyService;
use App\Services\UserService;
use App\Traits\SiteMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Packages ;
use App\Courses ;


class PackageController extends ApiController
{

    use SiteMeta;

    protected $pkgService;  
    protected $paymentService;  

    public function __construct(PackageService $pkgService, PaymentService $paymentService)
    {    
        parent::__construct();
        $this->setMeta('title', 'الاشتراكات');
        $this->registerSiteMeta();
        $this->pkgService = $pkgService;
        $this->paymentService = $paymentService;
    }

    public function indexBreadCrumb(){
        $this->addBreadCrumbLevel('الاشتراكات', Route('packages'));
    }

    public function index(Request $request){  
        if($request->has("package_id") && is_numeric($request->get("package_id"))){  
            try{
                $buyPackage = $this->paymentService->buyByPoint($request->get("package_id"), $this->getUser()->id);
                $request->session()->flash('toast-success', "نم شراء الباقة بنجاح شكرا لك");
                $request->session()->flash('toast-time', 6000);
                return redirect(route("profile"));
            }catch(Exception $exception){   
                $request->session()->flash('toast-error', $exception->getMessage());
                $request->session()->flash('toast-time', 6000);
            }

        }
        $availablePkgs = $this->pkgService->getAvailablePackages();   

        $PackageRoles = Packages::PackgeRoles() ;

        return $this->view('frontend.dashboard.packages', compact('availablePkgs' , "PackageRoles"));
 
    }

    public function beforeBuy($id){

        $package = $this->pkgService->find($id);   
        return $this->view('frontend.dashboard.buy-package-details', compact('package'));
    }

    public function buy($id, $processor){
        
        $request = request();
        
        if( isset($request->gateway) ){
            $processor = $request->gateway ;
        }

        $typ = $request['type'] ?? 'package' ;
        $msg = '' ; $redirect = '' ;
        try{
            switch($processor){
                case "paypal-old":
                    $this->paymentService->getPaypal()->init("USD", $id );
                    $buyPackage = $this->paymentService->buyByPaypal($id, $this->getUser()->id, $request['coupon'] , $typ );   
                    //Notify the user

                    if(!$buyPackage && $typ != 'course' ){
                        $request->session()->flash('toast-success', "عفوا لقد قمت بشراء هذه الباقة المجانية من قبل لا يمكن شرائها مرة أخرى");
                    }elseif(!$buyPackage && $typ == 'course' ){
                        $request->session()->flash('toast-success', "عفوا لقد قمت بشراء هذه الدورة من قبل لا يمكن شرائها مرة أخرى");
                    }else{
                        $request->session()->flash('toast-success', "يتم تحويلك الى الشراء الان ");
                    //Redirect to paypal
                        $request->session()->flash('redirect_url', $buyPackage['paypal_link']);
                        $request->session()->flash('redirect_after', 6000);
                    }
                     $request->session()->flash('toast-time', 6000);
                     
                    break;
                case "paypal" :
                    $buyPackage = $this->paymentService->buyByPaypalNew($id, $this->getUser()->id, $request['coupon'] , $typ );   
                    
                    //Notify the user
                    if(!$buyPackage && $typ != 'course' ){
                        $msg = "عفوا لقد قمت بشراء هذه الباقة المجانية من قبل لا يمكن شرائها مرة أخرى";
                    }elseif(!$buyPackage && $typ == 'course' ){
                        $msg = "عفوا لقد قمت بشراء هذه الدورة من قبل لا يمكن شرائها مرة أخرى";
                    }else{
                        $msg = "يتم تحويلك الى الشراء الان ";
                        $redirect = $buyPackage ;
                    }
                break;
                case "paytabs" :
                        $buyPackage = $this->paymentService->buyByPaytabs($id, $this->getUser()->id, $request['coupon'] , $typ );   
                            //Notify the user
                        if(!$buyPackage && $typ != 'course' ){
                            $msg = "عفوا لقد قمت بشراء هذه الباقة المجانية من قبل لا يمكن شرائها مرة أخرى";
                        }elseif(!$buyPackage && $typ == 'course' ){
                            $msg = "عفوا لقد قمت بشراء هذه الدورة من قبل لا يمكن شرائها مرة أخرى" ;
                        }else{
                            $msg = "يتم تحويلك الى الشراء الان ";
                            $redirect = $buyPackage;
                        }
                break;
                case "visa" :
                        $buyPackage = $this->paymentService->buyByVisa($id, $this->getUser()->id, $request['coupon'] , $typ );   
                            //Notify the user
                        if(!$buyPackage && $typ != 'course' ){
                            $msg = "عفوا لقد قمت بشراء هذه الباقة المجانية من قبل لا يمكن شرائها مرة أخرى";
                        }elseif(!$buyPackage && $typ == 'course' ){
                            $msg = "عفوا لقد قمت بشراء هذه الدورة من قبل لا يمكن شرائها مرة أخرى";
                        }else{
                            $msg = "يتم تحويلك الى الشراء الان ";
                            $redirect = $buyPackage ;
                        }
                    break;
                case "free":
                    $result = $this->paymentService->buyFree($id, $this->getUser()->id);  
                    if(!$result && $typ != 'course'){
                        $msg = "عفوا لقد قمت بشراء هذه الباقة المجانية من قبل لا يمكن شرائها مرة أخرى" ;
                    }elseif(!$result && $typ == 'course'){
                        $msg = "عفوا لقد قمت بشراء هذه الدورة المجانية من قبل لا يمكن شرائها مرة أخرى";
                    }else{
                        $msg =  "تم الشراء بنجاح شكرا لك";
                    }
                    break;    
            }
        }catch(Exception $exception){  
            $request->session()->flash('toast-error', $exception->getMessage());
            $request->session()->flash('toast-time', 6000);
        }

        return [ 'msg' => $msg , 'redirect' => $redirect ] ;
    }

    public function bought($id, $processor){

        $resArray = explode('-' , request()->invoice_id) ;
        $type = $resArray[2] ?? 'package' ;

        if( $type == 'course' ){
            $package = Courses::find($id);
        }else{
            $package = $this->pkgService->find($id);
        }
        
        if(!$package && $type == 'course'){
            return redirect(route("courses"));
        }elseif(!$package){
            return redirect(route("packages"));
        }
     
        $details = $this->paymentService->getPaypal()->doPayment($package, request('token'),  request('PayerID'));

    }

    public function view($template, $data = []) {
        return $data ;
    }

}