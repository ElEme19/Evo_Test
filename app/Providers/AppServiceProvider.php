<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


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
   public function boot()
{
    // Si hay un usuaria logueade, ajusta el locale
    if ($user = Auth::guard('usuarios')->user()) {
        App::setLocale($user->locale);
    }
    // ... cualquier otro código que tengas
}

}
