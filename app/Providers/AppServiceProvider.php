<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Batas waktu semua request HTTP keluar (API peminjaman, Midtrans, dll.)
        // agar halaman tidak menggantung bila layanan luar tidak bisa dihubungi.
        Http::globalOptions(['connect_timeout' => 3, 'timeout' => 10]);
    }
}
