<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\ApiController;
use Closure;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        $verify = ApiController::checkToken($token);
        if($verify !== 'success') return response(json_encode($verify), 401);
        return $next($request);
    }
}
