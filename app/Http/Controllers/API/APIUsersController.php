<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseBase;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Image;
use Storage;

class APIUsersController extends Controller
{

    public function me()
    {
        $user = Auth::user();

        $response = [
            'data' => $user
        ];

        return ResponseBase::success($response);
    }

    public function update(Request $request)
    {
        try{

            $rules = [
                'fullname' => 'required'
            ];
    
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails())
                throw new \Exception($validator->messages()->first());

            $me = Auth::user();
            if(!$me)
                throw new \Exception('the data maybe has been deleted or you dont login first');
                
            $user = User::find($me->id);
            if(!$user)
                throw new \Exception("user not found");

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

    public function delete()
    {
        try{

            $user = User::find(Auth::user()->id);
            if(!$user)
                throw new \Exception("user not found");

            $user->delete();

            $response = [
                'message' => "the data has been successfully deleted"
            ];

            return ResponseBase::success($response);

        }catch(\Exception $e) {
            $code = $e->getCode() > 0 ? $e->getCode() : 400;
            return ResponseBase::error($code,$e->getMessage());
        }
    }

    public function updatePhoto(Request $request)
    {
        try {

            $rules = [
                'photo' => 'required|mimes:jpeg,png,jpg|max:1024'
            ];
    
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails())
                throw new \Exception($validator->messages()->first());
    
            $me = Auth::user();
            if(!$me)
                throw new \Exception('the data maybe has been deleted before upload photo or you dont login first');

            $user = user::find($me->id);
            if(!$user)
                throw new \Exception("user with id: {$me->id} not found");

            $file = $request->file('photo');

            $filePath = date("Y/m/d/");
            $fileName = md5($user->fullname) . '-' . rand(100,900) . '.' . $file->getClientOriginalExtension();

            $img = Image::make($file->getRealPath());
            $img->stream();

            $uploadImage = Storage::disk('public')->put($filePath . $fileName, $img, 'public');
            if (!$uploadImage) 
                throw new \Exception('failed to save photo');

            $user->foto = $filePath . $fileName;
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