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
        $checkFrontEnd = true;
        foreach ($layouts as $key => $value) {
            if (Request::is($key) || Request::is($key . '/*')) {
                if ($value == 'System') {
                    $checkFrontEnd = false;
                    $this->bootSystem('Frontend');
                }
            }
        }
        $this->bootSystem('Frontend');
        if ($checkFrontEnd) $this->bootFrontend('Frontend');
    }

    public function bootFrontend($layout)
    {
        if (!isset($_SESSION)) session_start();
        $this->namespace = 'Modules\\' . $layout . '\Controllers';
        $this->modules = $layout;
        $arrModules = [];
        // $arrModules = CategoriesModel::select('*')->where('layout', 'LAYOUT_MENU_HEADER')->where('is_display_at_home', 1)->orderBy('order')->get();
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

    public function bootSystem($layout)
    {
            // session_start();
            if (!isset($_SESSION)) session_start();
            $this->namespace = 'Modules\\' . $layout . '\Controllers\Dashboard';
            $this->modules = $layout;
            $arrModules = [];
            // $arrModules = CategoriesModel::select('*')->where('layout', 'LAYOUT_MENU_HEADER')->where('is_display_at_home', 1)->orderBy('order')->get();
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
                $this->loadRoutesFrom(base_path() . '/modules/' . $this->modules . '/routesAdmin.php');
            });
            // Load views
            $this->loadViewsFrom(base_path() . '/modules/' . $this->modules . '/Views', ucfirst($this->modules));
            // Translations
            $this->loadTranslationsFrom(base_path() . '/modules/' . $this->modules . '/Lang', ucfirst($this->modules));
        }
}
