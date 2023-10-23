<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

class ModularProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modules = config('modular.modules');

        $path = config('modular.path');

        if($modules) {
            Route::group([
                    'prefix' => ''
                ], function () use ($modules, $path) {

                    foreach ($modules as $mod => $submodules) {
                        foreach ($submodules as $key => $sub) {
                            $relativePath = "/$mod/$sub";

                            Route::middleware('api')
                                ->group(function () use ($mod, $sub, $relativePath, $path) {
                                    $this->getWebRoutes($mod, $sub, $relativePath, $path);
                                });

                            Route::middleware('web')
                                ->group(function () use ($mod, $sub, $relativePath, $path) {
                                    $this->getApiRoutes($mod, $sub, $relativePath, $path);
                                });
                        }
                    }

                });

        }
        $this->app['view']->addNamespace('Login', base_path().'/resources/views/Login');
    }

    private function getWebRoutes(int|string $mod, mixed $sub, string $relativePath, mixed $path)
    {
        $routesPath = $path.$relativePath.'/Routes/web.php';

        if(file_exists($routesPath)) {
            if($mod != config("modular.groupWithoutPrefix")) {
                Route::group(
                    [

                    'prefix' => strtolower($mod),
                    'middleware' => $this->getMiddleware($mod)

            ],
                    function () use ($mod, $sub, $routesPath) {
                        Route::namespace("App\Modules\\$mod\\$sub\Controllers")->group($routesPath);
                    }
                );
            } else {

                Route::namespace("App\Modules\\$mod\\$sub\Controllers")->group($routesPath)->
                middleware($this->getMiddleware($mod)) ->
                group($routesPath);


            }
        }

    }

    private function getApiRoutes(int|string $mod, mixed $sub, string $relativePath, mixed $path)
    {
        $routesPath = $path.$relativePath.'/Routes/api.php';
        if(file_exists($routesPath)) {
            Route::group(
                [

                'prefix' => strtolower($mod),
                'middleware' => $this->getMiddleware($mod, 'api')

            ],
                function () use ($mod, $sub, $routesPath) {
                    Route::namespace("App\Modules\\$mod\\$sub\Controllers")->group($routesPath);
                }
            );
        }
    }

    private function getMiddleware(int|string $mod, $key = "web")
    {
        $middleware = [];

        $config = config('modular.groupMidleware');

        if(isset($config[$mod])) {
            if(array_key_exists($key, $config[$mod])) {
                $middleware = array_merge($middleware, $config[$mod] [$key]);
            }
        }
        return $middleware;

    }
}
