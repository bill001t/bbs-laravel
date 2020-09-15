<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Facade;
use App\Core\CoreBo;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('core', function(){
           return new CoreBo();
        });
    }
}


class Core extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'core';
    }
}