<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Concert;

class AppServiceProvider extends ServiceProvider
{
/**
* Register any application services.
*/
public function register(): void
{
//
}


/**
 * Bootstrap any application services.
 */
public function boot(): void
{
    // Share concerts data with all views
    View::composer('*', function ($view) {
        $view->with('concerts', Concert::all());
    });
}
}
