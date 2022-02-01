<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UNotify ;
use App\Notifications\AdminNotify ;

use DB ;

use Illuminate\Support\Str;

/**
 * Class User
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Role[] $roles
 *
 * @method static User create(array $user)
 * @package App
 */
class User extends Authenticatable implements JWTSubject , MustVerifyEmail
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('name', 'email', 'password', 'mobile', 'avatar', 'remember_token', 'email_verified_at', 'mobile_verified_at' ,'description' , 'avatar' , 'status','lang' , 'referer' );


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Set permissions guard to API by default
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * @inheritdoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritdoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendEmailVerificationNotification()
    { 
        $this->notify(new VerifyEmail); // my notification
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        foreach ($this->roles  as $role) {
            if ($role->isAdmin()) {
                return true;
            }
        }

        return false;
    }

    public function sendPasswordResetNotification($token){ 
        $data['user'] = $this ;
        $data['token'] = $token ; 
        $data['link']  = env('VUE', 'https://ezuru.net/').'password/reset/'.$token.'?email='.$this->email;
        Notification::send( $this , new UNotify($data  , 'user_forget_password' , $this->lang ) ) ;
       // $this->notify(new \App\Notifications\MailResetPasswordNotification($token) );
    }

/*
     *  User Model Relations
     */

    public function UserBooking()
    {
        return $this->hasMany('App\Models\Booking');
    }

    public function OwnerBooking()
    {
        return $this->hasMany('App\Models\Booking', 'owner_id');
    }

    public function Units()
    {
        return $this->hasMany('App\Models\Units');
    }

    public function WishList()
    {
        return $this->hasMany('App\Models\WishList');
    }

    public function Flags()
    {
        return $this->hasMany('App\Models\Flags', 'flagged_id');
    }

    public function Accounts()
    {
        return $this->hasMany('App\Models\Accounts', 'user_id');
    }

    public function Search()
    {
        return $this->hasMany('App\Models\Search', 'user_id');
    }

    public function Posts()
    {
        return $this->hasMany('App\Models\Posts', 'user_id');
    }

    public function Reviews()
    {
        return $this->hasMany('App\Models\Reviews', 'reviewer_id');
    }

    public function ReviewsOn()
    {
        return $this->hasMany('App\Models\Reviews', 'user_id');
    }

    public function Payments()
    {
        return $this->hasMany('App\Models\Payments', 'user_id');
    }

    public function Sales()
    {
        return $this->hasMany('App\Models\Payments', 'owner_id');
    }

    public function Badge()
    {
        return $this->hasMany('App\Models\Badge', 'user_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\Taxonomy', 'city');
    }
    
    public function getRateAttribute(){
        return [
            'score' => $this->rate_score ,
            'c' => $this->rate_count
        ] ;
    }

    public function Area()
    {
        return $this->hasMany('App\Models\AdminArea', 'user_id');
    }

    public function addby()
    {
        return $this->belongsTo('App\User', 'add_by') ;
    }


    protected static function boot() {

        parent::boot();

        static::created(function($model) {
            //$model->sendNotification($model , 'user_welcome') ;
            $model->code = Str::random(10) ;
            $model->save() ;
        });

        static::updated(function($model) {
           // $model->sendNotification($model , '') ;
        });

   }

    protected function sendNotification($model , $action = 'user_welcome'){


        $changes = $model->getChanges() ;


        if( isset($changes['email_verified_at']) && strlen($model->email_verified_at) !== 0 ){
            $action = 'user_verifed_email' ;
        }elseif( isset($changes['mobile_verified_at'])  && strlen($model->mobile_verified_at)  !== 0  ){
            $action = 'user_verifed_email' ;
        }elseif( isset($changes['photoid_verified_at'])  && strlen($model->photoid_verified_at)  !== 0  ){
            $action = 'user_verifed_photoid' ;
        }elseif( isset($changes['photoid'])  && isset($model->photoid[5]) ){
            $action = 'user_add_photoid' ;
        }

        $request = request() ;

    
        if( !$action ){
            return '';
        }

        

        // Lets Get All Data
        $data = [
            'user' => $model->toArray()
        ];
        
        if( $action == 'user_verifed_photoid' ){
            \Notification::send( $model , new UNotify( $data , $action , $data['user']['lang'] ?? '' ) ) ;
            sleep(1) ;
        }    
        $sent = [] ;

        // Send Notification To Admin Users && Area - Country - Government Manager
        // Admin Users
        $admins = DB::select(DB::raw("select * from users where exists (select * from roles inner join model_has_roles on roles.id = model_has_roles.role_id where users.id = model_has_roles.model_id and name = 'admin' )") ) ;

        
        foreach( $admins as $admin ){
            $data['admin'] = $admin;
            \Notification::send( $admin , new AdminNotify( $data , $action , 'en' ) ) ;
            sleep(1) ;
        }

   }





}
