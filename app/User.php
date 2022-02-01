<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles ;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordInterface;
use Illuminate\Auth\Passwords\CanResetPassword;

use App\Notifications\UNotify ;
use App\Notifications\AdminNotify ;

use Illuminate\Support\Str;

use DB ;

class User extends Authenticatable implements JWTSubject , MustVerifyEmail, CanResetPasswordInterface
{
    use Notifiable  , HasRoles, CanResetPassword;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    public $timestamps = true;

    protected $fillable = array('name', 'email', 'password', 'mobile', 'avatar', 'remember_token', 'email_verified_at', 'mobile_verified_at' ,'description' , 'avatar' , 'status', 'stage_id','gender' , 'referer' );

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

    protected $guard_name = 'web';


    /*
     *  JWT Update
     */
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
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

    /*
     *  User Model Relations
     */

    public function WishList()
    {
        return $this->hasMany('App\Models\WishList');
    }

    public function Flags()
    {
        return $this->hasMany('App\Models\Flags', 'flagged_id');
    }

    public function Search()
    {
        return $this->hasMany('App\Models\Search', 'user_id');
    }

    public function Posts()
    {
        return $this->hasMany('App\Models\Posts', 'user_id');
    }

    public function Payments()
    {
        return $this->hasMany('App\Models\Payments', 'user_id');
    }

    public function Badge()
    {
        return $this->hasMany('App\Models\Badge', 'user_id');
    }

    public function Points(){
        return $this->hasMany('App\Models\Point', 'user_id');
    }


    protected static function boot() {

        parent::boot();

        //Stop for now baby !!!
         static::created(function($model) {
             //$model->sendNotification($model , 'user_welcome') ;
             $model->code = Str::random(10) ;
             $model->save() ;
         });

        // static::updated(function($model) {
        //     $model->sendNotification($model , '') ;
        // });

   }

    public function sendNotification($model , $action = 'user_welcome'){


        $changes = $model->getChanges() ;
        
        
        if($action != '' ){
            
        }elseif( isset($changes['email_verified_at'])){
            $action = 'user_verifed_email' ;
        }elseif( isset($changes['mobile_verified_at'])  && isset($model->mobile_verified_at[5]) ){
            $action = 'user_verifed_mobile' ;
        }

        $request = request() ;

        if( !$action ){
            return '';
        }

        $user = $model ;
        // Lets Get All Data
        $data = [
            'user' => $user->toArray()
        ];
 
        \Notification::send( $model , new UNotify( $data , $action , $data['user']['lang'] ?? '' ) ) ;
   }


   public function getAvatarAttribute($avatar){
        if( !isset($avatar[5]) ){
             if( $this->gender == 'female' ) {
                return '/avatars/avatar-female.jpg' ;
             }else{
                return '/avatars/avatar-male.jpg' ;
             }
        }else{
            return $avatar ;
        }
   }

}
