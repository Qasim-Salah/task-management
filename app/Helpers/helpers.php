<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


if (!function_exists('destroyToken')) {
    function destroyToken($q)
    {
        $tokens = $q->tokens;
        foreach ($tokens as $token) {
            $token->delete();
        }
    }
}

if (!function_exists('createToken')) {
    function createToken()
    {
        $user = Auth::guard('api')->user();
        destroyToken($user);
        $tokenResult = $user->createToken('appToken');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addMonths(12);
        $token->save();
        return $tokenResult->accessToken;
    }

}


