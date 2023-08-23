<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\User;

class UserController extends Controller
{
    public function action_index_post(Request $request){
        $user = User::all();
        try{
            // $user_idを返す
            $user_id = $request->user_id;

            return response()->json(['user_id' => $user_id]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorResponse = $e->getResponse();
            $errorContent = $errorResponse->getBody()->getContents();
            return response()->json(json_decode($errorContent, true), $errorResponse->getStatusCode());
        }
    }
    
    public function action_index_put(Request $request){
        
    }
}
