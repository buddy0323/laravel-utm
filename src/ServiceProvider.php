<?php

namespace Adzbuck\LaravelUTM;

use Adzbuck\LaravelUTM\ParameterTracker;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public function boot(): void
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

        Route::mixin(new RouteMethods);
        Request::mixin(new RequestMethods);

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

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-utm.php', 'laravel-utm');

        $this->app->singleton(ParameterTracker::class, function ($app) {
            return new ParameterTracker(
                $app->make(Request::class),
                config('laravel-utm.tracked_parameters'),
                config('laravel-utm.first_touch_store_key'),
                config('laravel-utm.last_touch_store_key'),
            );
        });

        $this->app->singleton('laravelUTM', fn () => new LaravelUTM);
        $this->app->booting(fn () => AliasLoader::getInstance()->alias('LaravelUTM', LaravelUTM::class));
    }
}
