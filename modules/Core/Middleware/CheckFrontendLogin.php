<?php

namespace Modules\Core\Middleware;

use Closure;

class CheckFrontendLogin
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
        if (!empty($_SESSION) && !empty($_SESSION["hoithicchc"]["id"])) {
            return $next($request);
        } else {
            return redirect('dang-nhap');
        }
    }
}
