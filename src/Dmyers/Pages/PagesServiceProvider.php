<?php namespace Dmyers\Pages;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class PagesServiceProvider extends ServiceProvider
{    
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/../../helpers.php';
        
        if (\Config::get('pages.routes', true)) {
            $this->app->router->group(['namespace' => 'Dmyers\Pages'], function($router) {
                include __DIR__.'/../../routes.php';
            });
        }
        
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/pages.php' => config_path('pages.php'),
            ]);
        }
    }
    
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('pages', function($app) {
            return new Pages;
        });
    }
}
