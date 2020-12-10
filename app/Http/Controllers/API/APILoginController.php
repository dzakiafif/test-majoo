<?php


namespace App\Http\Controllers\API;

use App\Helpers\ResponseBase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class APILoginController extends Controller
{
    public function store(Request $request)
    {

        try{

            $rules = [
                'username' => 'required|exists:users',
                'password' => 'required'
            ];

            $validator = Validator::make($request->all(),$rules);
            if($validator->fails())
                throw new \Exception($validator->messages()->first());
        
            if(!$token = Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                throw new \Exception("invalid credentials");
            }

            $response = [
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60 
                ]
            ];

            return ResponseBase::success($response);

        }catch(\Exception $e) {
            $code = $e->getCode() > 0 ? $e->getCode() : 400;
            return ResponseBase::error($code,$e->getMessage());
        }
    }
}