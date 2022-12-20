<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Models\Client;
use App\Models\ClientVerify;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Controller
{
         // Profile Update Method 
    public function profile_update(UpdateRequest $request)
    { 
        if($request->email)
        {
            $message = "Email cannot be updated";
        } 
        else{
             $token=$request->header('Authorization');
             $user=ClientVerify::where('remember_me',$token)->first()->Client;
           if ($request->hasFile("profile_photo")) 
            {
              $image= $user->profile_photo;
              if(Storage::exists($image)){
                Storage::delete($image);
                $path = $request->File("profile_photo")->store('Images');
                $user->profile_photo= $path;
               }else{
                $path = $request->File("profile_photo")->store('Images');
                $user->profile_photo= $path;
               }
            }
             else{
                $user->name= $request->name;
                $user->password=Hash::make($request->password);
                 $user->age= $request->age;
                 $user->save();
            }
        }
    }
      //  Logout Method
    public function logout($token)
    {
        $user=ClientVerify::where('remember_me',$token)->first()->client;
        if($user)
        {
           $user->remember_me->delete;
           Session::flush();
           return response()->json(["message"=>"Logout Successful"]);
       }
    }
      // List Images Method
    public function show_image(Request $request,$token)
    {
        $data=$request->query('token');
        if($data==""){
           $re=Image::where('status','Public')->get();
           foreach ($re as $user) {
            echo $user->path;
           } 
        }else{
            $users = Client::with('image')
                        ->where('status','Public')
                        ->where('status','Private')
                        ->where('status','Hidden')->get();
        foreach ($users->image as $user) {
             echo $user->image_user->image_id;
            }                   
        }}

}
