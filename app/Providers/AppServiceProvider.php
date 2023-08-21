<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {
        Validator::extend('unique_follow_relation', function ($attribute, $value, $parameters, $validator) {
            list($id_follower, $id_followed) = $parameters;

            return !DB::table('follows')
                ->where('id_follower', $id_follower)
                ->where('id_followed', $id_followed)
                ->where('deleted_at', null)
                ->exists();
            });
    }
}
