<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\SupabaseService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $supabase = app(SupabaseService::class);
            $labs = $supabase->getAll('labs');

            // Cast each item to object supaya bisa pakai $lab->name
            $labs = array_map(function ($lab) {
                return (object) $lab;
            }, $labs);

            $view->with('labs', $labs);
        });
    }
}
