<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class HTML {

    /*
     *  返回html
     */
    public function handle($request, Closure $next) {
        View::addExtension("html", "php");
        return $next($request);
    }
}


