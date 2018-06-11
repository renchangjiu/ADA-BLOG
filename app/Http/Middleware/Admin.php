<?php

namespace App\Http\Middleware;

use App\Http\Models\Result;
use Closure;
use Illuminate\Support\Facades\Redis;

class Admin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($request->hasHeader("token")) {
            $token = $request->header("token");
            if (Redis::exists("TOKEN:$token")) {
                // $admin = Redis::get("TOKEN:$token");
                Redis::expire("TOKEN:$token", 60 * 60 * 24);
                return $next($request);
            } else {
                return response()->json(Result::failed(null, "Need to SignIn", 401));
            }
        } else {
            return response()->json(Result::failed(null, "need header:token", 401));
        }
        // return $next($request);
    }


}


