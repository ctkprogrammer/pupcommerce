<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Validator;
use Auth;

use App\User;

class AuthController extends Controller
{
    // User Register
    public function register(Request $request) {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'role' => 'required|string|max:255',            
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:1|confirmed',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }
       
            $user = User::forceCreate([
                'user_name' => $request->username,
                'role' => $request->role,                
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(80),
              
            ]);
    
            if (!$user) {
                return response()->json(['status' => 500, 'errors' => 'Internal Server Error.'], 500);
            }
            
            $credentials = ['email' => $request->email, 'password' => $request->password];
            
            if (Auth::attempt($credentials, $request->remember)) {
                $data = ['email' => $user->email, 'role' => $user->role, 'api_token' => $user->api_token];
                return response()->json(['status' => 200, 'message' => 'Successfully registered.', 'data' => $data], 200);
            } else {
                return response()->json(['status' => 400, 'errors' => 'User does not exist.'], 400);
            }
            
        } else {
            return response()->json(['status' => 400, 'errors' => 'wrong method.'], 400);
        }
    }

    // more detail
    public function registerProfile(Request $request) {
        if ($request->isMethod('post')) {
            $header = $request->header('Authorization');
            $api_token = str_replace('Bearer ', '', $header);
            
            $user = User::where('api_token', '=', $api_token)->first();
            
            if (!$user) {
                return response()->json(['status' => 400, 'errors' => 'Invalid token.'], 400);
            }

            $validator = Validator::make($request->all(), [
                    'address' => 'required|string|max:255',
                    'state' => 'required|string|max:255',
                    'city' => 'required|string|max:255',
                    'gender' => 'required|string|max:255',
                    'phone_number' => 'string|max:255|unique:users',
              
                ]);
            
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }
            
            // $user->address = $request->address ? $request->address : '';
            $user->state = $request->state ? $request->state : '';
            $user->city = $request->city ? $request->city : '';
            // $user->gender = $request->gender ? $request->gender : '';
            $user->phone_number = $request->phone_number ? $request->phone_number : '';
          
            $user->save();

           
            return response()->json(['status' => 200, 'message' => 'Successfully added profile informations.', 'data' => $request], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'wrong method.'], 400);
        }
    }

    // user login
    public function login(Request $request) {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }
            
            $credentials = ['email' => $request->email, 'password' => $request->password];
            
            if (Auth::attempt($credentials, $request->remember)) {
                $data = User::with(['pups'])->find(Auth::user()->id);
                return response()->json(['status' => 200, 'message' => 'success', 'data' => $data], 200);
            } else {
                return response()->json(['status' => 400, 'errors' => 'User does not exist.'], 400);
            }
        } else {
            return response()->json(['status' => 400, 'errors' => 'wrong method.'], 400);
        }
    }

    // user logout
    public function logout(Request $request) {
        if ($request->isMethod('post')) {
            $header = $request->header('Authorization');
            $api_token = str_replace('Bearer ', '', $header);
            
            $user = User::where('api_token', '=', $api_token)->first();
            
            if (!$user) {
                return response()->json(['status' => 400, 'errors' => 'Invalid token.'], 400);
            }

            return response()->json(['status' => 200, 'message' => 'Successfully logout.'], 200);
            
        } else {
            return response()->json(['status' => 400, 'errors' => 'wrong method.'], 400);
        }
    }  
}
