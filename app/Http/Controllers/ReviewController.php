<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\User;
use App\Shipment;

class ReviewController extends Controller
{
    // Create a new review
    public function create (Request $request) {
        

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'pup_name' => 'required',
                'breed_id' => 'required',
                'email' => 'required',
                'photo_url' => 'required',
                'phone_number' => 'required',
                'content' => 'required',
               
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }

            $review = Shipment::with(['buyers', 'users']);

            // $review = new Review();
            // $review->photo_url = $user->id;
            // $review->pup_name = $request->review_name;
            // $review->content = $request->breed_id;
           
            // $review->save();

            return response()->json(['status' => 200, 'message' => 'Successfully posted.', 'data' => $review], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'invalid method.'], 400);
        }
        
    }
}
