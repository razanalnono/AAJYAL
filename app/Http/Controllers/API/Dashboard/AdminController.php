<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Models\Admin;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    //
    public function index()
    {
        return Admin::select('firstName', 'lastName', 'gender', 'avatar', 'email')->get();
    }

    public function store(Request $request, Admin $admin)
    {
        $request->validate(Admin::rules());
        
        $email = $request->email;
        $admin = Admin::where('email', $email)->first();

        $password = Random::generate('5');


        // $hashed = Hash::make($password);
        // $trainee->password = $hashed;


        $data = $request->except('avatar');
        $data['password'] = Hash::make($password);

        $data['avatar'] = $this->uploadImage($request);

        $admin = Admin::create($data);
        Mail::to($admin->email)->send(new Password($password));


        return response()->json([
            'message' => 'Admin Created',
            'admin'=>$admin,
        ]);
    }

    public function show($id)
    {
    }

    public function update(Request $request, Admin $admin)
    {
        $old_image = $admin->avatar;
        $password = Random::generate('5');
        $data['password'] = Hash::make($password);

        $data = $request->except('avatar');
        $new_image = $this->uploadImage($request);
        if ($new_image) {
            $data['avatar'] = $new_image;
        }

        $admin->update($data);
        Mail::to($admin->email)->send(new Password($password));

        if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
        }

        return response()->json([
            'message' => 'Updated Successfully'
        ]);
    }


    public function destroy(Admin $admin)
    {

        $admin->delete();
        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }



    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('avatar')) {
            return;
        }

        $file = $request->file('avatar'); // UploadedFile Object

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }
}