<?php

namespace Askync\Utils;
require_once __DIR__.'/helpers/functions.php';


use Askync\Utils\Facades\AskyncResourceWrapper;
use Askync\Utils\Facades\AskyncResponse;
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
        $this->app->bind(Teleport::class, function (){
            return new Teleport();
        });
        $this->app->bind(AskyncResponse::class, function (){
            return new Response();
        });
        $this->app->bind(AskyncResourceWrapper::class, function (){
            return new ResourceWrapper();
        });
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
