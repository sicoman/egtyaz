<?php

namespace App\Http\Middleware;

use Closure;
use \Eventy;
use GraphQL;
use Illuminate\Foundation\AliasLoader;

class Authorized {

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
            //
    ];

    public function handle($request, Closure $next) {
        $header = $request->header('Authorization');
        $token  = explode(" ", $header); 
        return $next($request);
    }

}
