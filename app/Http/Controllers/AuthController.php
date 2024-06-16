<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function getToken(Request $request)
    {
        $clientKey = env('MPESA_CONSUMER_KEY'); // Fetch the client key from the .env file
        $clientSecret = env('MPESA_CONSUMER_SECRET'); // Fetch the client secret from the .env file
        $authUrl = env('MPESA_AUTH_URL'); // Fetch the auth URL from the .env file

        $credentials = base64_encode("$clientKey:$clientSecret");

        $response = Http::withHeaders([
            'Authorization' => "Basic $credentials",
        ])->get($authUrl);

        if ($response->successful()) {
            $responseData = $response->json();
            return response()->json(['access_token' => $responseData['access_token']], 200);
        } else {
            return response()->json(['error' => 'Failed to fetch access token'], $response->status());
        }
    }
}
