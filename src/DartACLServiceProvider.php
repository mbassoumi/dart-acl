<?php

namespace Dart\ACL;

use Dart\ACL\Guard\DartGuard;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;


class DartACLServiceProvider extends ServiceProvider {
    public function boot()
    {

    }

    public function register()
    {
        Auth::extend('dart-jwt', function ($app, $name, array $config) {
            return new DartGuard(Auth::createUserProvider($config['provider']), $app->request);
        });
    }
}
