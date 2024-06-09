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
        Response::macro('success', function($value, $status = 200){
            return Response::make([
                'success' => true,
                'data' => $value,
            ], $status);
        });

        Response::macro('error', function($value, $data = [], $status = 401){
            return Response::make([
                'success' => false,
                'data' => $data,
                'message' => $value
            ], $status);
        });
    }
}
