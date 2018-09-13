<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Access\Gate;

class AdminAuth
{
    /**
     * @var Gate
     */
    protected $gate;

    /**
     * AdminAuth constructor.
     * @param Gate $gate
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user()) {
            throw new AuthenticationException(
                'Unauthenticated.', [], route('login')
            );
        }

        $this->gate->authorize('admin');
        return $next($request);
    }
}
