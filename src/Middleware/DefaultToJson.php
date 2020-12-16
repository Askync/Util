<?php
namespace Askync\Utils\Middleware;

use Closure;

class DefaultToJson
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->add(['Accept' => 'application/json']);
        return $next($request);
    }
}

