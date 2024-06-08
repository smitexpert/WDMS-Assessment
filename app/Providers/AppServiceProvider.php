<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function($value){
            return Response::make([
                'success' => true,
                'data' => $value,
            ]);
        });

        Response::macro('error', function($value, $data = []){
            return Response::make([
                'success' => false,
                'data' => [],
                'message' => $value
            ]);
        });
    }
}
