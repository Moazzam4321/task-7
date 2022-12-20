<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Jobs\EmailSending;
use App\Models\ClientVerify;
use App\Models\Image;
use App\Models\image_user;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            $filename = time().'.' . $extension;
            $path=$file->move(public_path('uploads/'), $filename);
            $image=Image::Create([
                'name' => $data['name'],
                'date' => Carbon::now(),
                'time' => time(),
                'extension' => $extension,
                'path' => $path,
                'status' => $request->status
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
        $token = $request->header('Authorization');
         $id=$request->query('id');
         $link="http://localhost:8000/api/share/.$id.$token";
         return response()->json($link);
    }
     // Shareable Link Of Image with whom picture belongs
    public function ask_email(Request $request)
    {
         $link=$request->fullUrlWithQuery(['pass' => '787978']);
         $subject="Email Verification";
         EmailSending::dispatch($link,$request,$subject);
         return response()->json("ok");
    }
    public function verify_image(Request $request)
    {
        $email=$request->query('email');
        $token = $request->header('Authorization');
        $user=ClientVerify::where('remember_me',$token)->first()->Client::where('eamil',$email);
        if($user){
         $id=$request->query('id');
         $data= Image::where('id',$id)->first;
         $user->image()->attach($id); 
         return response()->json($data);
        }else{
               $message = "User not authenticated for this image";
        }
    }
    public function search()
    {
          
    }
}
