<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    public function store(Request $request)
    {
        // $user = new User([
        //   'email' => $request->get('email'),
        //   'password' => $request->get('password')
        // ]);
        // $user->save();


        return response()->json('User Added Successfully.');
    }
}
