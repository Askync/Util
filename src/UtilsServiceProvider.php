<?php

namespace Askync\Utils;
require_once __DIR__.'/helpers/functions.php';


use Askync\Utils\Facades\AskyncResourceWrapper;
use Askync\Utils\Facades\AskyncResponse;
use Askync\Utils\Middleware\Cors;
use Askync\Utils\Utils\ResourceWrapper;
use Illuminate\Support\ServiceProvider as Provider;
use Askync\Utils\Utils\Teleport;
use Askync\Utils\Utils\Response;

class UtilsServiceProvider extends Provider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->configure('cors');

        $this->app->bind(Teleport::class, function (){
            return new Teleport();
        });
        $this->app->bind(AskyncResponse::class, function (){
            return new Response();
        });
        $this->app->bind(AskyncResourceWrapper::class, function (){
            return new ResourceWrapper();
        });

        $this->app->routeMiddleware([
            'cors' => Cors::class,
        ]);

        $this->publishes([
            __DIR__ . '/config/cors.php' => config_path('cors.php')
        ], 'config');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
