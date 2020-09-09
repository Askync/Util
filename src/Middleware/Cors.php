<?php
namespace Askync\Utils\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Cors
{
    public function handle($request, Closure $next)
    {


        $allHeaders = $request->headers->all();
        $allHeaders = array_merge([
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'X-User-Lang',
        ], $allHeaders);

        $headers = [
            'Access-Control-Allow-Origin'      => implode(', ', config('cors.allowed_origins')),
            'Access-Control-Allow-Methods'     => 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
//            'Access-Control-Allow-Headers'     => (1== config('cors.allow_all_header')) ? '*' : implode(', ', array_merge( config('cors.allowed_headers'), $allHeaders ))
            'Access-Control-Allow-Headers'     => $request->header('Access-Control-Request-Headers'),
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
