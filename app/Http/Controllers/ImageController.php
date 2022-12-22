<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Jobs\EmailSending;
use App\Models\Client;
use App\Models\ClientVerify;
use App\Models\Image;
use App\Models\image_user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
       // Upload Image Method
    public function upload_image(ImageRequest $request)
    {
        $data=$request->validated();
        $token = $request->header('Authorization');
        $user=ClientVerify::where('remember_me',$token)->first()->client;
        if (!empty($request->has('profile_photo'))) {
            $file =$request->file('profile_photo');
            $extension = $file->getClientOriginalExtension(); 
            // $filename = time().'.' . $extension;
            $path = $request->File("profile_photo")->store('Images');
            if($request->has('status')==""){
                   $status="hidden";
            }else{
                $status=$request->status;
            }
            $image=Image::Create([
                'name' => $data['name'],
                'date' => Carbon::now(),
                'time' => time(),
                'extension' => $extension,
                'path' => $path,
                'status' => $status
            ]);
             $user->image()->attach($image->id);
             return response()->json($image);
        }
    }
       //  Remove Image
    public function destroy(Request $request)
    {
        $image = Image::find($request->id);
        // $image_path = public_path().'/'.$image->path;
        // unlink($image_path);
        $token = $request->header('Authorization');
        $user=ClientVerify::where('remember_me',$token)->first()->Client;
        $user->image()->detach($image);
        $image->delete();
    }
      // Shareable Link Of Image
    public function link_view(Request $request)
    {
        $id=$request->id;
         $link="http://localhost:8000/api/share/.$id";
         return response()->json($link);
    }
     // Shareable Link Of Image with whom picture belongs
    public function ask_email(Request $request)
    {
        $auth_user=Client::where('email',$request->query('email'));
        if($auth_user)
        {
         $link=$request->fullUrlWithQuery(['pass' => '787978']);
         $subject="Email Verification";
         EmailSending::dispatch($link,$request,$subject);
         $messasge="ok";
        }else{
            $message="Your are not authenticated user";
        }
        return response()->json(["message"=>$message]);
    }
    public function verify_image(Request $request)
    {
        $email=$request->query('email');
        $token = $request->header('Authorization');
        $user=ClientVerify::where('remember_me',$token)->first()->client;
        $User=$user->where('email',$email);
        if($User){
         $id=$request->query('id');
         $data= Image::where('id',$id)->first;
         $user->image()->attach($id); 
         return response()->json($data);
        }else{
               $message = "User not authenticated for this image";
        }
    }
    public function search(Request $request)
    {
        $token = $request->header('Authorization');
        $user=ClientVerify::where('remember_me',$token)->first()->client;
        $image = $user->image();
        $images=$this->image($request,$image);
        if($images)
        { 
            return response()->json(["message"=>$images]); 
            foreach($image as $user){
                         echo $user ;
                     }} 
        else{
         return response()->json(["message"=>"You are not authenticated user for search this photo"]); 
         }    
    }
       // Subfunction For exectue search query
    public function image($request,$image)
    {
        if ($request->input('name'))
        {
            $image= $image->where('name',$request->name)->orderby('time')->get();
        }
        else if ($request->input('extension'))
        {

           $image= $image->where('extension',$request->extension)->orderby('time')->get();
        }
        else if ($request->input('status'))
        {
           $image= $image->where('status',$request->status)->orderby('time')->get();
        }
        else if ($request->input('date'))
        {
           $image= $image->where('date',$request->date)->orderby('time')->get();
        }
        else if ($request->input('time'))
        {
           $image= $image->where('time',$request->time)->orderby('time')->get();
        }
        return $image;
    }
}
