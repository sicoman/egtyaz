<?php
/**
 * File UserController.php
 *
 * @author Tuan Duong <bacduong@gmail.com>
 * @package Laravue
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Laravue\Acl;
use App\Laravue\JsonResponse;
use App\Laravue\Models\Permission;
use App\Laravue\Models\Role;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;
use Illuminate\Support\Facades\DB;
use Auth ;
/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    const ITEM_PER_PAGE = 15;

    /**
     * Display a listing of the user resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|ResourceCollection
     */
    public function index(Request $request)
    {
        logy( auth()->user()->id , 'index' , '' , 'user' , [] );
        $searchParams = $request->all();
        $userQuery = User::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $role = Arr::get($searchParams, 'role', '');
        $keyword = Arr::get($searchParams, 'keyword', '');


        $return = 'resource' ;

        if (!empty($role) && $role != 'user') {
            $userQuery->whereHas('roles', function($q) use ($role) { $q->where('name', $role); });
        }elseif($role == 'user'){
            $userQuery->whereDoesntHave('roles', function($q) use ($role) { $q->whereIn('name', config('app.adminRoles') ); });
            $return = 'list';
        }else{
            $userQuery->whereHas('roles', function($q) use ($role) { $q->whereIn('name', config('app.adminRoles') ); });
        }

        $userrole = auth::user()->roles ;
        $user_role = [] ;
        foreach( $userrole as $roly ){
            $user_role[] = $roly->name ;
        }
        

        if (!empty($keyword)) {
            $userQuery->where(DB::raw('concat_ws("", name , email , mobile )'), 'LIKE', '%' . $keyword . '%');
        }

        if( isset($request->verification) and !empty($request->verification) ){
            foreach( $request->verification as $ver ){
                $userQuery->whereNotNull($ver) ;
            }
        }

        if($return == 'resource'){
            return UserResource::collection($userQuery->paginate($limit));
        }

       return $userQuery->paginate($limit) ;
        
    }

    public function select_list( Request $request )
    {
        $users = User::orderBy('id' , 'DESC');
        $users->where(DB::raw('concat_ws( "" , name , id )')  , 'like' , '%'.$request->s.'%' ) ;
        if( isset($request['role']) ){
            $users-> whereHas("roles", function($q) use ($request) { $q->where("name", $request['role'] ); })->get() ;
        }

        $res = $users->select(['id' , 'name'] )->get() ;
        return $res ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( ! is_array( $request->roles ) ){
           $request->request->add(['roles' => [ $request->roles ] ]) ;
        }

        logy( auth()->user()->id , 'add' , '' , 'user' , [] );
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
                [
                    'password' => ['required', 'min:6'],
                    'confirmPassword' => 'same:password',
                ]
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $params = $request->all();


            $insert = [
                'name' => $params['name'],
                'email' => $params['email'],
                'password' => Hash::make($params['password']),
            ];

            if( isset($params['city']) ){
                $insert['city'] = $params['city'] ;
            }

            if( isset($params['mobile']) ){
                $insert['mobile'] = $params['mobile'] ;
            }

            if( isset($params['avatar']) ){
                $insert['avatar'] = $params['avatar'] ;
            }

            if( isset($params['status']) ){
                $insert['status'] = (int) $params['status'] ;
            }
            $user = User::create($insert);

            if( $params['roles'] ){ 
                foreach( $params['roles'] as $role ){
                    $role = Role::findByName($role);
                    $user->syncRoles($role);
                }
            }

            return new UserResource($user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function show($user)
    {
        logy( auth()->user()->id , 'view' , '' , 'user' , [] );
        $user = User::whereid($user)->with('roles')->first() ;
        return ['data' => $user];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        logy( auth()->user()->id , 'edit' , '' , 'user' , [] );

        $modifier = Auth::user();

        $user = User::findOrFail($request->id) ;

        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if ($user->isAdmin() && $modifier->id != $user->id ) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $inputs = $request->except(['password','password2','units']);

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $email = $request->get('email');
            $found = User::where('email', $email)->first();

            $pass  = trim($request->get('password')) ;
            $pass2 = trim($request->get('password2')) ;

            if ($found && $found->id !== $user->id) {
                return response()->json(['error' => 'Email has been taken'], 403);
            }elseif(isset($pass[3])){
                    if( !isset($pass[6]) ){
                        return response()->json(['error' => 'Password should be 6 letters'], 403);
                    }elseif( $pass != $pass2  ){
                        return response()->json(['error' => 'Passwords not match'], 403);
                    }else{
                        $user->password = Hash::make($pass) ;
                    }
            }

            unset($inputs['roles']) ;
            if( isset($inputs['area']) ){
                unset($inputs['area']) ;
            }
            foreach($inputs as $k=>$v){
                $user->{$k} = $v ;
            }
        
            $user->save() ;

            return new UserResource($user);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User    $user
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, User $user)
    {
        logy( auth()->user()->id , 'edit_premission' , '' , 'user' , [] );

        if ($user === null) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->isAdmin()) {
            return response()->json(['error' => 'Admin can not be modified'], 403);
        }

        $permissionIds = $request->get('permissions', []);
        $rolePermissionIds = array_map(
            function($permission) {
                return $permission['id'];
            },

            $user->getPermissionsViaRoles()->toArray()
        );

        $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
        $permissions = Permission::allowed()->whereIn('id', $newPermissionIds)->get();
        $user->syncPermissions($permissions);
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        logy( auth()->user()->id , 'delete' , '' , 'user' , [] );
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            response()->json(['error' => 'Ehhh! Can not delete admin user'], 403);
        }

        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0') ;
        $delete = $user->delete();
        DB::statement('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1') ;
        return response()->json( $delete , 204);
    }

    /**
     * Get permissions from role
     *
     * @param User $user
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function permissions(User $user)
    {
        try {
            return new JsonResponse([
                'user' => PermissionResource::collection($user->getDirectPermissions()),
                'role' => PermissionResource::collection($user->getPermissionsViaRoles()),
            ]);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'name' => 'required',
            'email' => $isNew ? 'required|email|unique:users' : 'required|email',
            'roles' => [
                '',
                'array'
            ],
        ];
    }

    public function active($id , Request $request)
    {
        logy( auth()->user()->id , 'active' , '' , 'user' , [] );
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            response()->json(['error' => 'Ehhh! Can not edit admin user'], 403);
        }

        try {
            $user->update([ 'status' => (int) $request->status ]) ;
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }


        return response()->json(null, 204);
    }

    public function verifiy($id , Request $request)
    {
        logy( auth()->user()->id , 'verifiy' , '' , 'user' , [] );
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            response()->json(['error' => 'Ehhh! Can not edit admin user'], 403);
        }

        if( !in_array( $request->field , ['email' , 'mobile' , 'photoid'] ) ){
            response()->json(['error' => 'Field Undefined'], 403);
        }

        $value = null ;
        if( $request->status == 'now' ){
            $value = date('Y-m-d h:i:s') ;
        }

        try {
            $user->update([ $request->field.'_verified_at' => $value ]) ;
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }


        return response()->json( $user );
    }


    public function area($id , Request $request)
    {
        logy( auth()->user()->id , 'adminarea' , '' , 'user' , [] );
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            response()->json(['error' => 'Ehhh! Can not edit admin user'], 403);
        }

        
            
            $delete = AdminArea::where('user_id' , $id )->delete();
            if( !empty($request->area) ){
                foreach($request->area as $k => $arra){
                    if( count($arra) > 0 ){
                        foreach($arra as $ar){
                            AdminArea::create([
                                'type' => $k ,
                                'user_id' => $id ,
                                'area_id' => $ar['id']
                            ]) ;
                        }
                    }
                }
            }

        


        return response()->json( $user );
    }

    public function users( Request $request){

        $tax = User::orderBy('id' , 'ASC')->select(['id','name']) ;
        if( isset($request->s) and $request->s == '*' ){
             
        }elseif( isset($request->s) and $request->s != '' ){
            $tax->whereRaw('CONCAT_WS(" " , id , name ) LIKE "%'.$request->s.'%" ') ;
        }else{
            return [] ;
        }
        $taxonomy = $tax->get() ;

        return response()->json( $taxonomy ) ;
    }

    
}
