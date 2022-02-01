<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'roles' => array_map(function ($role) {
                return $role['name'];
            }, $this->roles->toArray()),
            'permissions' => array_map(function ($permission) {
                return $permission['name'] ;
            }, $this->getAllPermissions()->toArray()) 
        ] ;

        if( $this->token != '' ){
            $data['token'] = $this->token ;
        }

        return $data ;
    }
}
