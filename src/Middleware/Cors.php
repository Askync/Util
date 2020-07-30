<?php
namespace Askync\Utils\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Cors
{
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => implode(', ', config('cors.allowed_origins')),
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PATCH, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => implode(', ', array_merge( config('cors.allowed_headers'), [
                'Content-Type',
                'Authorization',
                'X-Requested-With',
                'X-User-Lang',
            ] ))
        ];

        if ($request->isMethod('OPTIONS'))
        {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
        {
            $response->header($key, $value);
        }

        return $response;
    }
}
