<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\FrontendController;
use App\Services\TaxonomyService;
use App\Traits\SiteMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Options;
use App\Services\CommunityService;
use App\Services\NotificationsService;
use Illuminate\Support\Facades\Route;
use View;

class NotificationController extends FrontendController
{
    use SiteMeta;

    protected $options;
    protected $notificationsService;

    public function __construct(NotificationsService $notificationsService, Options $options)
    {

        parent::__construct();

        $this->setMeta('title', 'لوحة تحكم - الاشعارات');

        $this->registerSiteMeta();

        $this->notificationsService = $notificationsService;

        $this->options = $options;

        $default_settings   = $this->getSetting(['seo', 'social']);

        View::share('options', $default_settings);

        $this->addBreadCrumbLevel('الرئيسية', Route('cpanel'));

    }


    public function indexBreadCrumb()
    {
        $this->addBreadCrumbLevel('الاشعارات', Route('notifications'));
    }

    public function getSetting($type)
    {
        if (is_array($type)) {
            $returnOptions = [];
            $options = $this->options->whereIn('type', $type)->select(['type', 'option_value', 'option_var']);
            foreach ($options as $option) {
                $returnOptions[$option->type][$option->option_var] =  $option->option_value;
            }
            return  $returnOptions;
        }
        return  $this->options->where('type', $type)->pluck('option_value', 'option_var');
    }

    public function index(Request $request)
    {

        parent::shareUser();   

        $notificationsWithUsers = $this->notificationsService->getNotifcationsWithUser($this->getUser()->id);

        if($notificationsWithUsers){
            foreach($notificationsWithUsers as $notification){
                $notification->markAsRead();
            }
        }
 
        return $this->view('frontend.dashboard.notification-list', compact('notificationsWithUsers'));
    }

   
}
