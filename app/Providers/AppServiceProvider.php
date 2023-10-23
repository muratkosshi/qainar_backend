<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
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
        Validator::extend('required_if_one_filled', function ($attribute, $value, $parameters, $validator) {
            $email = $validator->getData()['email'];
            $phone = $validator->getData()['phone'];

            return !empty($email) || !empty($phone);
        });
    }
}
