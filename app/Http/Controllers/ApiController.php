<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiModel;
use App\Models\OtpModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function user_signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_otp' => 'required',
            'email_otp' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        $data =array();
        $code = 422;
        if ($validator->fails()) 
        {
            $data['message'] = $validator->errors(); 
            $code = 422;  
        }
        else
        {
        $flight = ApiModel::create([
            'name' => $request->name,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
            'password'=>md5($request->password),
            'status'=>1,
        ]);
        $response =  $flight->save();
        
        $otp_data = OtpModel::create([
           
            'email_otp'=>$request->email_otp,
            'mobile_otp'=>$request->mobile_otp,
            'email'=>$request->email,
            'mobile'=>$request->mobile,
            'status'=>1,
        ]);
        $response = $otp_data->save();
       
        if($response):
            $data['message'] = "Data Saved Successfully! ";
            $data['status']=true;
          
            $code = 200;
        else:
            $data['message'] = "Something went wrong";
            $data['status']=true;
            $code = 500;   
        endif; 
    }
return response()->json($data,$code);
    }
        public function user_signin(Request $request)
        {
        $user_id=$request->user_id;
        DB::enableQueryLog();
        $user_data = DB::table('users')->where('email',$user_id)->orWhere('mobile',$user_id)->count();
        // $count = count_all($user_data);
       // dd($user_data);
   
        //  dd(DB::getQueryLog());
    
        }
}
