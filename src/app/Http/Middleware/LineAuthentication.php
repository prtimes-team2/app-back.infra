<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class LineAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $client = new Client();
        try{
            // POST リクエストから idToken の値を取得
            $idToken = $request->input('idToken');
            
            if($idToken == "id_token"){
                // $idToken == "id_token"の時は常に通す（デバック用）
                // $user_idをリクエストに追加
                $request->merge(['user_id' => "123456789"]);
            }
            else{
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

                // $user_idをリクエストに追加
                $request->merge(['user_id' => $data['sub']]);
            }

            return $next($request);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $errorResponse = $e->getResponse();
            $errorContent = $errorResponse->getBody()->getContents();
            return response()->json(json_decode($errorContent, true), $errorResponse->getStatusCode());
        }
        
    }
}
