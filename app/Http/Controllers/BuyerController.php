<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\Buyer;
use App\User;

class BuyerController extends Controller
{
    public function show (Request $request) {

        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }

        $buyers = Buyer::All();   

        $message = count($buyers) ? 'success' : 'There is no customer';

        return response()->json(['status' => 200, 'message' => $message, 'data' => $buyers], 200);
    }

    // Create a new buyer
    public function create (Request $request) {
  
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone_number' => 'required',
                'customer_id' => 'required',
                'address' => 'required',
                'zipcode' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }

            $buyer = new Buyer();
        
            $buyer->first_name = $request->first_name;
            $buyer->last_name = $request->last_name;
            $buyer->email = $request->email;
            $buyer->phone_number = $request->phone_number;
            $buyer->customer_id = $request->customer_id;
            $buyer->address = $request->address;
            $buyer->zipcode = $request->zipcode;
            $buyer->city = $request->city;
            $buyer->state = $request->state;
            $buyer->country = $request->country;
            $buyer->save();

            return response()->json(['status' => 200, 'message' => 'Successfully posted.', 'data' => $buyer], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'invalid method.'], 400);
        }
        
    }
    // Get a buyer detail
    public function details (Request $request, $id) {      

        $buyer = Buyer::find($id);      

        return response()->json(['status' => 200, 'message' => 'success', 'data' => $buyer], 200);
    }

    // Delete a job
    public function delete (Request $request, $id) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }

        if (!$id) {
            return response()->json(['status' => 400, 'errors' => 'invalid params.'], 400);
        }

        $buyer = Buyer::find($id);   
        $buyer->delete();

        return response()->json(['status' => 200, 'message' => 'Successfully deleted.', 'data' => ''], 200);
    }
    
}
