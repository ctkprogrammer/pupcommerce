<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\User;
use App\Shipment;
use App\Review;

class ReviewController extends Controller
{
    public function index (Request $request) {
        // $header = $request->header('Authorization');
        // $api_token = str_replace('Bearer ', '', $header);

        // $user = User::where('api_token', '=', $api_token)->first();
        // if(!$user) {
        //     return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        // }
        $review = Review::join('shipments', 'reviews.shipment_id', '=', 'shipments.id')
            ->join('buyers', 'shipments.buyer_id', '=', 'buyers.id')
            ->join('pups', 'pups.id', '=', 'shipments.pup_id')
            ->join('breeds', 'breeds.id', '=', 'pups.breed_id')
            ->get(['reviews.*', 'buyers.first_name', 'buyers.last_name', 'buyers.email AS buyer_email', 'shipments.delivery_city', 'shipments.delivery_state', 'breeds.breed_name']);     

        $message = count($review) ? 'success' : 'There is no posted review.';

        return response()->json(['status' => 200, 'message' => $message, 'data' => $review], 200);
    }

    public function seller_review (Request $request) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }
 
       $review = Review::join('shipments', 'reviews.shipment_id', '=', 'shipments.id')
       ->join('buyers', 'shipments.buyer_id', '=', 'buyers.id')
       ->join('pups', 'pups.id', '=', 'shipments.pup_id')
       ->join('breeds', 'breeds.id', '=', 'pups.breed_id')
       ->where('shipments.user_id', '=', $user->id)
       ->get(['reviews.*', 'buyers.first_name', 'buyers.last_name', 'buyers.email AS buyer_email', 'shipments.delivery_city', 'shipments.delivery_state', 'breeds.breed_name']);     

        $message = count($review) ? 'success' : 'There is no posted review.';

        return response()->json(['status' => 200, 'message' => $message, 'data' => $review], 200);
    }

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

            // $review = Shipment::with(['buyer', 'user', 'pup'])->where('shipment.*.email', $request->email)->get();

            $temp = Shipment::with(['buyer', 'pup'])
                ->join('buyers', 'shipments.buyer_id', '=', 'buyers.id')
                ->join('pups', 'shipments.pup_id', '=', 'pups.id')
                ->where('buyers.email', $request->email)
                ->where('pups.breed_id', '=', $request->breed_id)
                ->where('pups.pup_name', '=', $request->pup_name)
                ->get(['shipments.id AS shipment_id', 'shipments.*'])->first();

            if(!$temp){

                return response()->json(['status' => 200, 'message' => 'There is not related data', 'data' =>''], 200);

            } else {

                $review_check = Review::where('shipment_id', $temp->shipment_id)->get();

                if(count($review_check)){
                    return response()->json(['status' => 200, 'message' => 'Already exist', 'data' =>''], 200);

                }else{
                    $review = new Review();
                    $review->photo_url = $request->photo_url;
                    $review->pup_name = $request->pup_name;
                    $review->content = $request->content;
                    $review->shipment_id = $temp->shipment_id;
                
                    $review->save();
                }
                
            }
            
            return response()->json(['status' => 200, 'message' => 'Success', 'data' => $review], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'invalid method.'], 400);
        }
        
    }

    // Get a review detail
    public function details (Request $request, $id) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }

        if (!$id) {
            return response()->json(['status' => 400, 'errors' => 'invalid params.'], 400);
        }

        $review = Review::where('reviews.id', '=', $id)->get()->first();     

        $data['review'] = $review;

        $shipment = Shipment::with(['user', 'buyer', 'pup'])->get()->first();

        $data['shipment'] = $shipment;

        return response()->json(['status' => 200, 'message' => 'success', 'data' => $data], 200);
    }
}
