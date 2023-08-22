<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class LineLoginController extends Controller
{
    public function action_index(Request $request){
        $client = new Client();
        try{
            // POST リクエストから idToken の値を取得
            $idToken = $request->input('idToken');
            $response = $client->post('https://api.line.me/oauth2/v2.1/verify', [
                'form_params' => [
                    'id_token' => $idToken,
                    'client_id' => '2000441362',
                ],
            ]);
            // レスポンスの取得
            $responseBody = $response->getBody()->getContents();
            //jsonデコード
            $data = json_decode($responseBody, true);
            return response()->json(['user_id' => $data['sub']]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorResponse = $e->getResponse();
            $errorContent = $errorResponse->getBody()->getContents();
            return response()->json(json_decode($errorContent, true), $errorResponse->getStatusCode());
        }
    }
}
