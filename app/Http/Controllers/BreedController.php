<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Breed;
use App\User;

class BreedController extends Controller
{

    public function index (Request $request) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }

        $breed_query = Breed::join('users', 'users.id', '=', 'breeds.user_id');
                        

        $breeds = $breed_query->get(['breeds.id AS breed_id', 'breeds.*', 'users.*']);

        $message = count($breeds) ? 'success' : 'There is no posted breed.';

        return response()->json(['status' => 200, 'message' => $message, 'data' => $breeds], 200);
    }
    // Create a new Breed
    public function create (Request $request) {
        $header = $request->header('Authorization');
        $api_token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', '=', $api_token)->first();
        if(!$user) {
            return response()->json(['status' => 400, 'errors' => 'invalid token.'], 400);
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'breed_name' => 'required|unique:breeds',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }

            $breed = new Breed();
            $breed->breed_name = $request->breed_name;
            $breed->user_id = $user->id;
           
            $breed->save();

            return response()->json(['status' => 200, 'message' => 'Successfully posted.', 'data' => $breed], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'invalid method.'], 400);
        }
    }

    // Get a breed detail
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

        $breed = Breed::with(['users'])->find($id);     

        return response()->json(['status' => 200, 'message' => 'success', 'data' => $breed], 200);
    }

    // Delete breed
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

        $breed = Breed::find($id);
        $breed->delete();

        return response()->json(['status' => 200, 'message' => 'Successfully deleted.', 'data' => ''], 200);
    }

    // Update a breed
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
                'breed_name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
            }

            $breed = Breed::find($id);
            $breed->user_id = $user->id;
            $breed->breed_name = $request->breed_name;
          
            $breed->save();

            return response()->json(['status' => 200, 'message' => 'Successfully updated.', 'data' => $breed], 200);
        } else {
            return response()->json(['status' => 400, 'errors' => 'invalid method.'], 400);
        }
    }
}
