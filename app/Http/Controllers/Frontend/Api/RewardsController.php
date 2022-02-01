<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Frontend\FrontendController;
use App\Repositories\FrontEndRepository;
use App\Repositories\WishlistRepository;
use App\Services\PackageService;
use App\Services\PaymentService;
use App\Services\TaxonomyService;
use App\Services\UserService;
use App\Traits\SiteMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardsController extends ApiController
{

    use SiteMeta;

    protected $pkgService;  
    protected $paymentService;  
    protected $frontEndRepository;

    public function __construct(PackageService $pkgService, PaymentService $paymentService, FrontEndRepository $frontEndRepository)
    {    
        parent::__construct();
        $this->setMeta('title', 'المكافئات');
        $this->registerSiteMeta();
        $this->pkgService = $pkgService;
        $this->paymentService = $paymentService;
        $this->frontEndRepository = $frontEndRepository;
        $this->addBreadCrumbLevel('الرئيسية', Route('cpanel'));

    }

    public function indexBreadCrumb(){
        $this->addBreadCrumbLevel('المكافئات', Route('rewards'));
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
        $referalUrl = route("register")."?referer={$this->getUser()->code}"; 
        $myPoints = $this->getUser()->points;
        $availablePkgs = $this->pkgService->getAvailablePkgsForPoints($myPoints); 
        $rewardData = $this->frontEndRepository->rewardsData();   
        return $this->view('frontend.dashboard.rewards', compact('referalUrl', 'myPoints', 'availablePkgs', 'rewardData'));
 
    }

}