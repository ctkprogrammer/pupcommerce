<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Auth;

use App\User;
use App\Pup;
use App\Shipment;

class ShipmentController extends Controller
{
    // Create a new shipment
    public function create (Request $request) {
        
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'price' => 'required',
                'discount' => 'required',
                'final_price' => 'required',
                'estimated_delivery_time' => 'required',
                'actual_delivery_time' => 'required',
                'delivery_city' => 'required',
                'delivery_state' => 'required',
                'delivery_address' => 'required',
                'delivery_zipcode' => 'required',
                'delivery_phone' => 'required',
                'user_id' => 'required',
                'buyer_id' => 'required',
                'pup_id' => 'required',       
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }

            $shipment = new Shipment();
        
            $shipment->price = $request->price;
            $shipment->discount = $request->discount;
            $shipment->final_price = $request->final_price;
            $shipment->estimated_delivery_time = $request->estimated_delivery_time;
            $shipment->actual_delivery_time = $request->actual_delivery_time;
            $shipment->delivery_city = $request->delivery_city;
            $shipment->delivery_state = $request->delivery_state;
            $shipment->delivery_address = $request->delivery_address;
            $shipment->delivery_zipcode = $request->delivery_zipcode;
            $shipment->delivery_phone = $request->delivery_phone;
            $shipment->user_id = $request->user_id;
            $shipment->buyer_id = $request->buyer_id;
            $shipment->pup_id = $request->pup_id;
          
         
            $shipment->save();

            return response()->json(['status' => 200, 'message' => 'Successfully posted.', 'data' => $shipment], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'invalid method.'], 400);
        }
        
    }

    public function seller_shipment (Request $request) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }
        $shipment_query = Shipment::with(['users','buyers','pups'])->where('shipments.user_id', '=', $user->id);
 
        $shipment = $shipment_query->get(['shipments.id AS shipment_id', 'shipments.*']);

        $message = count($shipment) ? 'success' : 'There is no posted shipment.';

        return response()->json(['status' => 200, 'message' => $message, 'data' => $shipment], 200);
    }
}
