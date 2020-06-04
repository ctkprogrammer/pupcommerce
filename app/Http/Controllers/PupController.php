<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Auth;

use App\User;
use App\Pup;
use App\Breed;

class PupController extends Controller
{
    public function index (Request $request) {
        // $header = $request->header('Authorization');
        // $api_token = str_replace('Bearer ', '', $header);

        // $user = User::where('api_token', '=', $api_token)->first();
        // if(!$user) {
        //     return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        // }
        $pups_query = Pup::join('breeds', 'breeds.id', '=', 'pups.breed_id')
       ->with(['users','breeds']);
 
        $pups = $pups_query->get(['pups.id AS pup_id', 'pups.*', 'breeds.breed_name AS breed_name']);

        $message = count($pups) ? 'success' : 'There is no posted pup.';

        return response()->json(['status' => 200, 'message' => $message, 'data' => $pups], 200);
    }

    public function seller_pups (Request $request) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }
        $pups_query = Pup::join('breeds', 'breeds.id', '=', 'pups.breed_id')
       ->with(['users','breeds'])->where('users.id', '=', $user->id);
 
        $pups = $pups_query->get(['pups.id AS pup_id', 'pups.*', 'breeds.breed_name AS breed_name']);

        $message = count($pups) ? 'success' : 'There is no posted pup.';

        return response()->json(['status' => 200, 'message' => $message, 'data' => $pups], 200);
    }

    // Create a new pup
    public function create (Request $request) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'pup_name' => 'required',
                'breed_id' => 'required',
                'price' => 'required',
                'photo_url' => 'required',
                'video_url' => 'required',
                'birth' => 'required',
                'gender' => 'required',
                'current_weight' => 'required',
                'adult_weight' => 'required',
                'neopar_vaccine' => 'required',
                'drumune_max' => 'required',
                'pyrantel_deworm' => 'required',
                'vet_inspection' => 'required',       
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }

            $pup = new Pup();
            $pup->user_id = $user->id;
            $pup->pup_name = $request->pup_name;
            $pup->breed_id = $request->breed_id;
            $pup->price = $request->price;
            $pup->photo_url = $request->photo_url;
            $pup->video_url = $request->video_url;
            $pup->birth = $request->birth;
            $pup->gender = $request->gender;
            $pup->current_weight = $request->current_weight;
            $pup->adult_weight = $request->adult_weight;
            $pup->neopar_vaccine = $request->neopar_vaccine;
            $pup->drumune_max = $request->drumune_max;
            $pup->pyrantel_deworm = $request->pyrantel_deworm;
            $pup->vet_inspection = $request->vet_inspection;
            $pup->shipment_id = 1;
            $pup->save();

            return response()->json(['status' => 200, 'message' => 'Successfully posted.', 'data' => $pup], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'invalid method.'], 400);
        }
        
    }

    // Update a pup
    public function update (Request $request, $id) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }

        if (!$id) {
            return response()->json(['status' => 400, 'errors' => 'invalid params.'], 400);
        }

        if ($request->isMethod('put')) {
            $validator = Validator::make($request->all(), [
                'pup_name' => 'required',
                'breed_id' => 'required',
                'price' => 'required',
                'photo_url' => 'required',
                'video_url' => 'required',
                'birth' => 'required',
                'gender' => 'required',
                'current_weight' => 'required',
                'adult_weight' => 'required',
                'neopar_vaccine' => 'required',
                'drumune_max' => 'required',
                'pyrantel_deworm' => 'required',
                'vet_inspection' => 'required',       
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }

            $pup = Pup::find($id);
            $pup->user_id = $user->id;
            $pup->pup_name = $request->pup_name;
            $pup->breed_id = $request->breed_id;
            $pup->price = $request->price;
            $pup->photo_url = $request->photo_url;
            $pup->video_url = $request->video_url;
            $pup->birth = $request->birth;
            $pup->gender = $request->gender;
            $pup->current_weight = $request->current_weight;
            $pup->adult_weight = $request->adult_weight;
            $pup->neopar_vaccine = $request->neopar_vaccine;
            $pup->drumune_max = $request->drumune_max;
            $pup->pyrantel_deworm = $request->pyrantel_deworm;
            $pup->status = $request->vet_inspection;
            $pup->shipment_id = 1;
            $pup->save();

            return response()->json(['status' => 200, 'message' => 'Successfully updated.', 'data' => $pup], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'invalid method.'], 400);
        }
    }

    // Get a pup detail
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

        $pup = Pup::with(['users', 'breeds'])->find($id);      

        return response()->json(['status' => 200, 'message' => 'success', 'data' => $pup], 200);
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

        $pup = Pup::find($id);
        $pup->delete();

        return response()->json(['status' => 200, 'message' => 'Successfully deleted.', 'data' => ''], 200);
    }
}
