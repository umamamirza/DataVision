<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; 

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Schema::defaultStringLength(191); 
    }
}
