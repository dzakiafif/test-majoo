<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseBase;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class APIRegisterController extends Controller
{
    public function store(Request $request)
    {
        try{

            $rules = [
                'username' => 'required|unique:users',
                'password' => 'required',
                'fullname' => 'required'
            ];

            $validator = Validator::make($request->all(),$rules);
            if($validator->fails())
                throw new \Exception($validator->messages()->first());

            $user = new User();
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->nama_lengkap = $request->fullname;
            $user->save();

            $response = [
                'data' => $user
            ];

            return ResponseBase::success($response);

        }catch(\Exception $e) {
            $code = $e->getCode() > 0 ? $e->getCode() : 400;
            return ResponseBase::error($code,$e->getMessage());
        }
    }
}