<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (!app()->runningInConsole() || app()->runningUnitTests()) {
            \Illuminate\Support\Facades\View::composer('*', function ($view) {
                if (\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                    $settings = \App\Models\SiteSetting::pluck('value', 'key')->all();
                    $view->with('settings', $settings);
                }
                if (\Illuminate\Support\Facades\Schema::hasTable('nav_menus')) {
                    $navMenus = \App\Models\NavMenu::where('is_active', true)->orderBy('order', 'asc')->get();
                    $view->with('navMenus', $navMenus);
                }
            });
        }
    }
}
