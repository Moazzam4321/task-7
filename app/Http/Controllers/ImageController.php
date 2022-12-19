<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Models\Client;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    public function upload_image(ImageRequest $request)
    {
        if (!empty($request->has('profile_photo'))) {
            $file =$request->file('profile_photo');
            $extension = $file->getClientOriginalExtension(); 
            return "ashjahaj";
            $filename = time().'.' . $extension;

            $file->move(public_path('uploads/'), $filename);

            $data['image']= 'public/uploads/'.$filename;
        }
    }
    public function destroy($id)
    {
        $data = Client::find($id);
        $image_path = public_path().'/'.$data->filename;
        unlink($image_path);
        $data->delete();
        // if(Storage::delete($data->filename)) {
        //     $data->delete();
        //  }
        return redirect('/avatars');
    }
    public function share_image()
    {
           
    }
}
