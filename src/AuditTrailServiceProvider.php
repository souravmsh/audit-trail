<?php

namespace Souravmsh\AuditTrail;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Souravmsh\AuditTrail\Commands\InstallCommand;
use Souravmsh\AuditTrail\View\Components\Widget;

class AuditTrailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot()
    {

        /**
        * load the migrations, views, and routes
        */
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'audit-trail');
        Route::get('vendor/audit-trail/{file}', function ($file) {
            $path = __DIR__ . '/../public/' . $file;
            if (!file_exists($path)) {
                abort(404);
            }
            return response(file_get_contents($path), 200)
                ->header('Content-Type', 'text/css');
        });
        Blade::component("audit-trail-widget", Widget::class);

        /**
         * publish the config, migrations, views, and assets
         */
        $this->publishes([
            __DIR__ . "/../config/audit-trail.php" => config_path("audit-trail.php")
        ], "audit-trail-config");

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ], "audit-trail-migrations");
            
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path("views/vendor/souravmsh/audit-trail")
        ], "audit-trail-views");

        $this->publishes([
            __DIR__ . '/../public' => public_path("vendor/audit-trail")
        ], "audit-trail-assets");
     
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }

        AboutCommand::add("Audit Trail", fn () => ['Version' => '1.0.0']);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/audit-trail.php',
            'audit-trail'
        );
    }
}
