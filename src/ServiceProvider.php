<?php

namespace Adzbuck\LaravelUTM;

use Adzbuck\LaravelUTM\DecorateURL;
use Adzbuck\LaravelUTM\ParameterTracker;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot()
    {
        Blade::directive('trackedUrl', function (string $expression) {
            return "<?php echo \Adzbuck\LaravelUTM\DecorateURL::decorateUrl({$expression}); ?>";
        });
        Blade::directive('trackedUrlFromFirstTouch', function (string $expression) {
            return "<?php echo \Adzbuck\LaravelUTM\DecorateURL::decorateUrlFromFirstTouch({$expression}); ?>";
        });
        Blade::directive('trackedUrlFromLastTouch', function (string $expression) {
            return "<?php echo \Adzbuck\LaravelUTM\DecorateURL::decorateUrlFromLastTouch({$expression}); ?>";
        });
        Blade::directive('trackedUrlFromCurrent', function (string $expression) {
            return "<?php echo \Adzbuck\LaravelUTM\DecorateURL::decorateUrlFromCurrent({$expression}); ?>";
        });

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes(
            [
                __DIR__.'/../config/laravel-utm.php' => config_path('laravel-utm.php'),
            ],
            'config'
        );
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-utm.php', 'laravel-utm');

        $this->app->singleton(ParameterTracker::class, function ($app) {
            return new ParameterTracker(
                $app->make(Request::class),
                $app->make(Session::class),
                config('laravel-utm.tracked_parameters'),
                config('laravel-utm.first_touch_session_key'),
                config('laravel-utm.last_touch_session_key'),
            );
        });
    }
}
