<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;



class AccountController extends Controller
{
    public function index()
    {
        return view('account', ['user' => Auth::user()]);
    }

    public function updateProfilePicture(Request $request)
    {
        if($request->hasFile('image')){
            $filename = $request->image->getClientOriginalName();
            $request->image->storeAs('images',$filename,'public');
            $image = Image::make(public_path('storage/images/'.$filename));
            $image->fit(300);
            $image->save();
            $user = Auth::user();
            $user->avatar = "/storage/images/".$filename;
            $user->update();
        }
        return redirect()->back();
    }

}
