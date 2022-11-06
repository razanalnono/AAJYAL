<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Password;
use App\Models\Admin;
use App\Models\Trainee;
use App\Models\Trainer;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Nette\Utils\Random;

class AccessTokensController extends Controller
{
    //
    public function login(Request $request, $type)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|max:5',
            //'agent' => 'string'
        ]);

        $email = $request->email;
        $password = $request->password;

        if ($type == 'trainee') {
            $user = Trainee::where('email', $email)->first();
        } elseif ($type == 'trainer') {
            $user = Trainer::where('email', $email)->first();
        } else {
            $user = Admin::where('email', $email)->first();
        }

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->userAgent())->plainTextToken;
            return response()->json([
                'message' => 'Authenticated',
                'token' => $token,
                'user' => $user,
            ]);
        }
        return Response::json([
            'message' => 'Invalid Login Credentials'
        ], 401);
    }


    public function changePassword(Request $request, $type)
    {

        $password =   $request->password;
        $email = $request->email;

        if ($type == 'trainee') {
            $user = Trainee::where('email', $email)->first();
        } elseif ($type == 'trainer') {
            $user = Trainer::where('email', $email)->first();
        } else {
            $user = Admin::where('email', $email)->first();
        }

        if ($user && (Hash::check($request->old_password, $user->password))) {
            $token = $user->createToken($request->userAgent())->plainTextToken;
            $user->update([

                'password' => Hash::make($request->password),

            ]);
            return Response::json([
                'message' => 'Authenticated',
                'token' => $token,
            ], 201);
        }


        return Response::json([
            'message' => 'Invalid Login Credentials'
        ], 401);
    }

    // public function verify(Request $request){

    // $acode=$request->activation_code;
    // if($user=User::where('activation_code',$acode)->first()){
    // if($user->status==1){
    //   $token = $user->createToken('user' . $user->id)->plainTextToken;
    //   return Response::json([
    //                'token'=>$token->plainTextToken,
    //             'user'=>$user,
    //            ]);

    // }else{
    // return Response::json([
    //     'message'=>'Not verified'
    // ]);
    // }


    // }


    // }


    public function verify(Request $request)
    {
        $email = $request->email;
        $acode = $request->activation_code;

        if ($admin = Admin::where('email', $email)->where('activation_code', $acode)->first()) {
            $admin->status == 1;
            $new_token = $admin->createToken('verify' . $admin->id)->plainTextToken;
            $admin->token = $new_token;
            $admin->save();
            return Response::json([
                //     'token' => $new_token->plainTextToken,
                'admin' => $admin,
                'meassage' => 'Verified',
            ]);
        } elseif ($trainee = Trainee::where('email', $email)->where('activation_code', $acode)->first()) {
            $trainee->status == 1;
            $new_token = $trainee->createToken('verify' . $trainee->id)->plainTextToken;
            $trainee->token = $new_token;
            $trainee->save();
            return Response::json([
                //     'token' => $new_token->plainTextToken,
                'trainee' => $trainee,
                'meassage' => 'Verified',
            ]);
        } elseif ($trainer = Admin::where('email', $email)->where('activation_code', $acode)->first()) {
            $trainer->status == 1;
            $new_token = $trainer->createToken('verify' . $trainer->id)->plainTextToken;
            $trainer->token = $new_token;
            $trainer->save();
            return Response::json([
                //     'token' => $new_token->plainTextToken,
                'trainer' => $trainer,
                'meassage' => 'Verified',
            ]);
        } else {
            return Response::json([
                'message' => 'Not verified'
            ]);
        }
    }
}