<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetRequest;
use App\Http\Requests\SignUpRequest;
use App\Jobs\EmailSending;
use App\Models\Client;
use App\Models\ClientVerify;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientController extends Controller
{
              // SignUp Method
   public function signUp(SignUpRequest $request)
  {  
    $exist=$this->unique_email($request);
    if($exist)
    {
      $data=$request-> validated();
      if ($data['profile_photo']) 
      {
         //  $path = Storage::disk('local')->put($request->file('photo')->getClientOriginalName(),$request->file('photo')->get());
        $path = $request->File("profile_photo")->store('Images');
      }
      $password=Hash::make($data['password'], [  'rounds' => 12, ]);
      $Client =Client:: create([
                              'name'=>$data['name'],
                             'email'=>$data['email'],
                             'password'=>$password,
                             'age'=>$data['age'],
                             'profile_photo'=>$path,
                             'created_on'=>date("Y-m-d H:i:s")
                           ]);
        $this->send_mail($Client,$request);
        return response()->json([
             "message"=>"You are registered successfully. Now go to ur gmail for account verification"
             ]);
        }
    }
           // Email Verfifed Method
 public function emailVerify($token)
  { 
    $verifyUser = UserVerify::where('token', $token)->first()->client;
    if(!is_null($verifyUser) )
    {
      if($verifyUser->email_verified_at=="") 
      {
         $verifyUser->email_verified_at = "true";
         $verifyUser->save();
            $message = "Your e-mail is verified. You can now login.";
      } 
      else 
      {
        $message = "Your e-mail is already verified. You can now login.";       
       }
      return response()->json(['message'=>$message]);
    }
  }
                // Login Method
    public function postLogin(LoginRequest $request)
    { 
      $req=$request->validated();
      $user=Client::where('email',$request->email)->first();
      if($user->email_verified_at !="")
      {
          $message='Email not verified. First go to ur gmail for verify link then login';
      }
      else
      {
        $pass=$user->password;
        $password=hash::check($req['password'],$pass);
        if($password)
        {
          $token = Str::random(10); 
          $get=ClientVerify::create([
               'user_id' => $user->id, 
               'remember_me' => $token
           ]); 
           $message=$user.$get;   
        }
        else{
             $message= "invalid password";
        }
        return response()->json(["message"=>$message]);
      }
    }
               // Forgot Password Method
     public function forgotPassword(ForgotRequest $request)
     {
        $data=$request->validated();
        $User=Client::where('email',$data['email'])->first();
        if($User)
        {            
          $token = Str::random(10); 
          $link="http://localhost:8000/api/reset/$token";
          UserVerify::create([
                    'user_id' => $User->id, 
                      'token' => $token
                   ]);
          $subject="Reset Password Link";
          EmailSending::dispatch($link,$data,$subject);
        }else{
             return response()->json("User Not Found");
        }
     }
        // Password Reset Method
     public function passwordReset(ResetRequest $request)
     {
      $data = $request->header('Authorization');
       if($data)
        {
           $User = UserVerify::where('token', $data)->first()->client;
            if($User)
            {
              $User->remember_me->delete();
             $User->password=Hash::make($data['password'], [
                    'rounds' => 12,
                   ]);
              $User->save();
            }
          $message = 'Password reset successfully.Now go to login';
           return response()->json($message);
       }
     }
         // Unique email function
     function unique_email(SignUpRequest $request)
     {
        $data = $request->validated();
          $user= Client::where('email',$data['email']);
          if($user)
          {
              return true;
          }
      }
         // Send mail function
      function send_mail($Client,$request)
      {
             
            $token = Str::random(10);
            $email=$request->email;
                 $link="http://localhost:8000/api/account/verify/$token";
      
                 UserVerify::create([
                   'user_id' => $Client->id, 
                   'token' => $token
                 ]);
                 $subject="Email Verification";
                  EmailSending::dispatch($link,$email,$subject);
      }
}

