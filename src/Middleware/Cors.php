<?php
namespace Askync\Utils\Middleware;

use Closure;
class Cors
{
    public function handle($request, Closure $next)
    {


        $allowHeaders = [
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'X-User-Lang',
        ];

        $headers = [
            'Access-Control-Allow-Origin'      => implode(', ', config('cors.allowed_origins')),
            'Access-Control-Allow-Methods'     => 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => implode(', ', array_merge( config('cors.allowed_headers'), $allowHeaders ))
        ];


        $response = $next($request);

        if ($request->isMethod('OPTIONS')) {
            $response = response()->json('{"method":"OPTIONS"}', 200);
        }

        foreach($headers as $key => $value)
        {
            $response->header($key, $value);
        }

        return $response;
    }
}
