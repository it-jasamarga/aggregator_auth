<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AuthApi
{    
    /**
     * handle
     *
     * @param  mixed $request
     * @param  mixed $next
     * @return void
     */
    public function handle($request, Closure $next)
    {
        // bypass.
        // return $next($request);

        $token = $request->bearerToken();

        if(!$token)
        {
            return response()->json([
                'status' => false,
                'message'=> 'Token not provided.'
            ], 401);
        }
        
        $ip   = Helper::getIp();

        try {
            // 2 hours
            $user = Cache::remember("verify_token_{$token}", 120 * 60, function () use ($token) {
                return User::where('token', $token)->first();
            });

            if($user == null)
            {

                return response()->json([
                    'status' => false,
                    'message'=> 'Invalid token.'
                ], 400);

            }
        } catch (Exception $e) {

            $errorId = strtoupper(Str::random(10));

            Log::error("[{$ip}] [{$errorId}] : {$e->getMessage()}");

            return response()->json([
                'status' => false,
                'message'=> 'Verifying token failed, exception triggered. Traceback ID ' . $errorId,
                // 'logs'   => $e->getMessage(),
            ], 500);

        }

        $request->request->add(['identity' => $user]);

        return $next($request);
    }
}
