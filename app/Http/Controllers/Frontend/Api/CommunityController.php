<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Frontend\FrontendController;
use App\Services\TaxonomyService;
use App\Traits\SiteMeta;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Options;
use App\Services\CommunityService;
use Illuminate\Support\Facades\Route;
use View;

class CommunityController extends FrontendController
{
    use SiteMeta;

    protected $options;
    protected $communityService;

    public function __construct(CommunityService $communityService, Options $options)
    {

        parent::__construct();

        $this->setMeta('title', 'لوحة تحكم -  المنتدى الاجتماعى');

        $this->registerSiteMeta();

        $this->communityService = $communityService;

        $this->options = $options;

        $default_settings   = $this->getSetting(['seo', 'social']);

        View::share('options', $default_settings);
    }


    public function indexBreadCrumb()
    {
        $this->addBreadCrumbLevel('المنتدى الاجتماعى', Route('community'));
    }

    public function newPostBreadCrumb()
    {
        $this->indexBreadCrumb();
        $this->addBreadCrumbLevel('اضافة موضوع جديد', '#');
    }

    public function categoryBreadCrumb()
    {
        $route = Route::current();
        $this->indexBreadCrumb();
        $this->addBreadCrumbLevel('{$category_name}', Route('community_category', ['category' => $route->parameter('category')]));
    }

    public function postBreadCrumb()
    {
        $route = Route::current();
        $this->categoryBreadCrumb();
        $this->addBreadCrumbLevel('{$post_name}', Route('community_post', ['category' => $route->parameter('category'), 'post' => $route->parameter('post')]));
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

        $communityCatListWithPosts = $this->communityService->listCategoriesWithPosts();

        return $this->view('frontend.dashboard.community.list', compact('communityCatListWithPosts'));
    }

    public function category(Request $request, $category)
    {

        parent::shareUser();

        $category = $this->communityService->getCategoryById($category);

        $posts = $this->communityService->getPosts($category->id);

        $category_name = $category->name;

        return $this->view('frontend.dashboard.community.category-list', compact('posts', 'category', 'category_name'));
    }


    public function post(Request $request, $category, $post_id)
    {

        parent::shareUser();

        $category = $this->communityService->getCategoryById($category);
 
        $post = $this->communityService->getPosts($category->id, $post_id)->first();
  
        $category_name = $category->name;

        $post_name = $post->title;

        $comments = $post->Comments()->paginate(15);

        return $this->view('frontend.dashboard.community.post', compact('post', 'category', 'category_name', 'post_name', 'comments'));
    }


    public function comment($id, Request $request)
    {

        $this->validate($request, [
            'comment'   => 'required'
        ]);

        $data = $request->all();

        $commentService = resolve("App\Services\CommentService");

        $comment = $commentService->add(['post_id' => $id, 'user_id' => $this->getUser()->id, 'comment' => $data['comment'], 'ip' => request()->ip(), 'status' => 1]);

        if ($comment) {
            return redirect()->back()->with('toast-success', 'تم ارسال التعليق بنجاح');
        } else {
            return redirect()->back()->with('toast-error', 'حدث خطا ما');
        }
    }


    public function newPost()
    {

        parent::shareUser();

        $categories = $this->communityService->getCategories();

        return $this->view('frontend.dashboard.community.newPost', compact('categories'));
    }


    public function addPost(Request $request)
    {

        $this->validate($request, [
            'description'   => 'required',
            'taxonomy_id'   => 'required',
            'title'         => 'required'
        ]);

        $data = $request->all();

        $postService = resolve("App\Services\PostsService");

        $post = $postService->add(['title' => $data['title'],'type' => 'forum', 'user_id' => $this->getUser()->id, 'description' => $data['description'], 'taxonomy_id' => $data['taxonomy_id'], 'status' => 1]);

        if ($post) {
            return redirect(route("community_post", ['post' => $post->id, 'category' => $data['taxonomy_id']]))->with('toast-success', 'تم ارسال الموضوع بنجاح');
        } else {
            return redirect()->back()->with('toast-error', 'حدث خطا ما');
        }
    }
}
