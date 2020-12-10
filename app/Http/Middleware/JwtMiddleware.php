<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseBase;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {    
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $status     = 401;
                $message    = 'This token is invalid. Please Login';
                return ResponseBase::error($status,$message);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                try
                {
                  $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                  $user = JWTAuth::setToken($refreshed)->toUser();
                  $request->headers->set('Authorization','Bearer '.$refreshed);
                }catch (JWTException $e){
                    $code = 103;
                    $message = 'Token cannot be refreshed, please Login again';
                    return ResponseBase::error($status,$message);
                }
            }else{
                $code = 404;
                $message = 'Authorization Token not found';
                return ResponseBase::error($code,$message);
            }
        }
        return $next($request);
    }
}
