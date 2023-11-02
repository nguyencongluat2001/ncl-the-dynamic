<?php

namespace Modules\Api\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\Api\Helpers\Message;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class apiAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $url = $request->path();
        $arrUrl = explode('/', $url);
        $sizeArrUrl = sizeof($arrUrl);
        $function = $arrUrl[$sizeArrUrl - 1];
        // Ghi logs
        $logger = new Logger($function);
        $ipClient = $this->get_client_ip();
        $datas = $request->all();
        return $next($request);
    }

    public function get_client_ip() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}
