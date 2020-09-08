<?php
namespace Askync\Utils\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Cors
{
    public function handle($request, Closure $next)
    {
        $allHeaders = app()->request->headers->all();
        $allHeaders = array_merge([
            'Content-Type',
            'Authorization',
            'X-Requested-With',
            'X-User-Lang',
        ], $allHeaders);

        $headers = [
            'Access-Control-Allow-Origin'      => implode(', ', config('cors.allowed_origins')),
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PATCH, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => (1== config('cors.allow_all_header')) ? true : implode(', ', array_merge( config('cors.allowed_headers'), $allHeaders ))
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
