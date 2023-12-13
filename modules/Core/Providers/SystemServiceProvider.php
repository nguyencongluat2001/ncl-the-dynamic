<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Modules\System\Cms\Models\CategoriesModel;

class SystemServiceProvider extends ServiceProvider
{

    protected $prefix = '';
    protected $layout = '';
    protected $namespace = '';
    protected $modules = '';
    protected $middleware = '';

    protected $arrModules = '';
    protected $currentModule = '';
    protected $arrUnit = '';

    public function register()
    {
    }

    public function boot()
    {
        $layouts = config('moduleInitConfig.layouts');
        //Kiem tra xem url se thuoc layout nao
        $checkFrontEnd = true;
        foreach ($layouts as $key => $value) {
            if (Request::is($key) || Request::is($key . '/*')) {
                if ($value == 'System') {
                    $checkFrontEnd = false;
                    $this->bootSystem($value, $key);
                }
            }
        }
        if ($checkFrontEnd) $this->bootFrontend('Frontend');
    }

    public function bootFrontend($layout)
    {
        if (!isset($_SESSION)) session_start();
        $this->namespace = 'Modules\\' . $layout . '\Controllers';
        $this->modules = $layout;
        // $arrModules = CategoriesModel::select('*')->where('layout', 'LAYOUT_MENU_HEADER')->where('is_display_at_home', 1)->orderBy('order')->get();
        $arrModules = [];

        $this->arrModules = $arrModules;
        view()->composer('*', function ($view) {
            $view->with('menuItems', $this->arrModules);
            $view->with('module', $this->currentModule);
        });
        Route::group([
            'namespace'  => $this->namespace,
            'module'     => $this->modules,
            'middleware' => ['web'],
            // 'prefix'     => strtolower($this->prefix)
        ], function ($router) {
            $this->loadRoutesFrom(base_path() . '/modules/' . $this->modules . '/routes.php');
        });
        // Load views
        $this->loadViewsFrom(base_path() . '/modules/' . $this->modules . '/Views', ucfirst($this->modules));
        // Translations
        $this->loadTranslationsFrom(base_path() . '/modules/' . $this->modules . '/Lang', ucfirst($this->modules));
    }

    public function bootSystem($layout, $url)
    {
        session_start();
        // Kiem tra quyen dang nhap
        if (
            Request::is('system/login') || Request::is('system/login/*') ||
            Request::is('system/logout') || Request::is('system/logout/*')
        ) {
            // Load routes
            Route::group([
                'namespace'  => 'Modules\System\Login\Controllers',
                'module'     => 'login',
                'middleware' => 'web',
                'prefix'     => 'system/login'
            ], function ($router) {
                $this->loadRoutesFrom(base_path() . '/modules/System/Login/routes.php');
            });
            // Load views
            $this->loadViewsFrom(base_path() . '/modules/System/Login/Views', 'Login');
        } else {
            $this->namespace = 'Modules\\' . $layout . '\Controllers';
            $middleware       = ['web', 'CheckAdminLogin'];
            $this->middleware = $middleware;
            $arrModules       = config('moduleSystem');
            // chức năng quản trị thủ tục hành chính đơn vi
            if (isset($_SESSION['role']) && $_SESSION['role'] == 'ADMIN_OWNER') {
                unset($arrModules['recordtype']['child']['recordtype']);
                unset($arrModules['recordtype']['child']['recordtype_child']);
                unset($arrModules['recordtype']['child']['syscRecordtype']);
            }
            // chức năng quản trị thủ tục hành chính root
            if (isset($_SESSION['role']) && $_SESSION['role'] == 'ADMIN_SYSTEM') {
                unset($arrModules['recordtype']['child']['recordtypeUnit']);
            }
            // Get all Menu
            $this->arrModules = $arrModules;
            foreach ($arrModules as $urlModule => $arrModule) {
                $urlcheck = $url . '/' . $urlModule;
                if (Request::is($urlcheck) || Request::is($urlcheck . '/*')) {
                    $module              = $urlModule;
                    $this->currentModule = $module;
                    $segments            = Request::segments();
                    view()->composer('*', function ($view) use ($segments) {
                        $view->with('menuItems', $this->arrModules);
                        $view->with('module', $this->currentModule);
                        $view->with('childModule', $segments[2] ?? '');
                    });
                    $layout          = 'System';
                    $this->layout    = $layout;
                    $this->modules   = $module;
                    $this->prefix    = $module;
                    $this->namespace = 'Modules' . "\\" . $layout . "\\" . ucfirst($module) . '\Controllers';
                    // Load routes
                    Route::group([
                        'namespace'  => $this->namespace,
                        'middleware' => $this->middleware,
                        'module'     => $this->modules,
                        'prefix'     => $url . '/' . strtolower($this->prefix)
                    ], function ($router) {
                        $this->loadRoutesFrom(base_path() . '/modules/' . $this->layout . '/' . ucfirst($this->modules) . '/routes.php');
                    });
                    // Load views
                    $this->loadViewsFrom(base_path() . '/modules/' . $this->layout . '/' . $this->modules . '/Views', ucfirst($this->modules));
                    // Translations
                    $this->loadTranslationsFrom(base_path() . '/resources/lang', "System");
                }
            }
        }
    }
}
