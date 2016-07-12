<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogMiddleware {

    public function handle($request, Closure $next)
    {
    	// Log::info('app.request', ['request' => $request->all()]);
        return $next($request);
    }

    public function terminate($request, $response)
    {
        // Log::info('app.reponse', ['response' => $response]);
    }

}

?>