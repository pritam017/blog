<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
class SettingsController extends Controller
{
    public function index(){
        return view('admin.settings');
    }
    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'image' => 'required|image',
            'about' => 'required',
        ]);
        $image = $request->file('image');
        $slug = Str::slug($request->name);
        $user = User::findOrFail(Auth::id());
        if(isset($image)){
            $img = $slug . '.' . time() . '.'. $image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('profile')){
                Storage::disk('public')->makeDirectory('profile');
            }
            if(Storage::disk('public')->exists('profile/'. $user->image)){
                Storage::disk('public')->delete('profile/'. $user->image);
            }
            $users = Image::make($image)->resize(500,500)->save('png','jpg','jpeg');
            Storage::disk('public')->put('profile/'.$img,$users);
            $user->image = $img;
        }else{
            $img = $user->image;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->image  = $img;
        $user->about = $request->about;
        $user->save();

Toastr::success('Profile Updated');
return redirect()->back();

        }

        public function updatePassword(Request $request){
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|confirmed',


            ]);
            $hassPassword = Auth::user()->password;
            if(Hash::check($request->old_password, $hassPassword)){
                if(!Hash::check($request->password, $hassPassword)){
                    $user = User::find(Auth::id());
                    $user->password = Hash::make($request->password);
                    $user->save();
                    Toastr::success('Password Sucessfully Changed');

                    Auth::logout();
                    return redirect()->back();

                }else{
                    Toastr::error('Old Password And New Password are same');
                    return redirect()->back();
                }
            }else{
                Toastr::error('Current Password Not Match');
                return redirect()->back();
            }
        }
    }
