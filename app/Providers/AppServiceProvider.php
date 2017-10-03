<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        User::created(function ($user) {
            /**
             * TODO:
             * --> Setup unique username and a password for the new user
             * --> Email new user for their user login credentials
             */
            if(is_null($user->username)) {
                $user->username = generateUsername($user->first_name, $user->last_name);
                
                if(is_null($user->password))
                    $user->password = \Hash::make('welcome'.$user->first_name);
                $user->save();
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
