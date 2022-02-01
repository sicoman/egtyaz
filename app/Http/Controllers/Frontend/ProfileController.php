<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\FrontendController;
use App\Repositories\WishlistRepository;
use App\Services\PaymentService;
use App\Services\TaxonomyService;
use App\Services\UserService;
use App\Traits\SiteMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends FrontendController
{

    use SiteMeta;

    protected $userService;
    protected $wishListRepo;
    protected $taxonomyService;
    protected $paymentService;

    public function __construct(UserService $userService, WishlistRepository $wishListRepo, TaxonomyService $taxonomyService, PaymentService $paymentService)
    {    
        parent::__construct();
        $this->setMeta('title', 'الملف الشخصى');
        $this->registerSiteMeta();
        $this->userService = $userService;
        $this->wishListRepo = $wishListRepo;
        $this->taxonomyService = $taxonomyService;
        $this->paymentService = $paymentService;
        
        $this->addBreadCrumbLevel('الرئيسية', Route('cpanel'));

    }

    public function indexBreadCrumb(){
        $this->addBreadCrumbLevel('الملف الشخصى', Route('profile'));
    }

    public function updateAvatar(Request $request)
    {   
        $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = $this->getUser();

        $avatarName = $user->id.'_avatar'.time().'.'.request()->avatar->getClientOriginalExtension();

        $request->avatar->storeAs('avatars', $avatarName);

        $user->avatar = "/storage/avatars/".$avatarName;
        $user->save();
    }

    public function index(Request $request){

        if($request->has('action') && $request->get('action') == "update"){

           try{  
             $user = $this->userService->update($this->getUser()->id, $request->all());
             $request->session()->flash('toast-success', "تم تحديث بياناتك بنجاح شكرا لك");
             $request->session()->flash('toast-time', 6000);
             Auth::setUser($user);
           }
           catch(Exception $exception){
            $request->session()->flash('toast-error', "حدث خطأ أثناء حفظ البيانات من فضلك حاول مرة أخرى");
            $request->session()->flash('toast-time', 6000);
           }
        }

        $myPackages = $this->paymentService->getUserPkgs($this->getUser()->id);

        return $this->view('frontend.dashboard.profile', compact("myPackages"));
 
    }

    public function searchUser(Request $request){

        $users = $this->userService->searchUsers($request->get('query'));
        $list = [];
        foreach($users as $user){
          array_push($list, [
              'id'    => $user->id,
              'label' => $user->name,
              'value' => $user->name
          ]);
        }

        return response()->json($list);
    }

    public function addOrRemoveInWishlist(Request $request){
        $key_id = $request->get('key_id');
        $result = $this->wishListRepo->addOrRemoveFromWishList($this->getUser()->id,$key_id);
        return response()->json($result);    
    }

    public function wishlistBreadCrumb(){
        $this->addBreadCrumbLevel('المفضلة', Route('wishlist'));
    }

    public function wishlist(Request $request){
        $search = [];
        if($request->has("skill")){
            $search["skill_id"] = $request->get("skill");
        }
        if($request->has("subject")){
            $search["subject_id"] = $request->get("subject");
        }
        $wishlist = $this->wishListRepo->getWishList($this->getUser()->id,15,"question",$search);
        $subjects = $this->taxonomyService->getSubjects();
        $skills   = $this->taxonomyService->getSkills();
        return $this->view('frontend.dashboard.my-wishlist', ['wishlist' => $wishlist, 'skills' => $skills, 'subjects' => $subjects]);
    }

    public function deleteQuestionsFromWishlist(Request $request){

        $ids = $request->get('ids'); 
        $delete = $this->wishListRepo->deleteByIds($ids);
        return response()->json(['result' => $delete]);

    }


}