<?php

namespace Modules\Base\Providers;

use Modules\Core\Ncl\Http\Debug\ApiDebug;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Request;
use Illuminate\Support\Facades\View;
use Modules\System\Models\MenuModel;
use Modules\System\Listtype\Helpers\ListtypeHelper;
use Modules\Core\Ncl\Db\Connection;
use Illuminate\Support\Facades\Auth;

class SystemServiceProvider extends ServiceProvider
{

    public function register()
    {
    }

    public function boot()
    {
        if(!isset($_SESSION)){ 
            session_start(); 
        }else{
            session_destroy();
            session_start(); 
        }
    }
}
