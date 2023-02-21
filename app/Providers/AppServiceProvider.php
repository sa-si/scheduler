<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        // \DB::listen(function($q){

        //     // SQL文
        //     dump($q->sql);

        //     // パラメータ
        //     dump($q->bindings);

        //     // 実行にかかった時間
        //     dump($q->time);

        // });
    }
}
