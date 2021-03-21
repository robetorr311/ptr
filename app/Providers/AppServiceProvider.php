<?php

namespace App\Providers;

use App\Contracts\FileUploadService\ImageServiceInterface;
use App\Contracts\PaymentContracts\PaymentInterface;
use App\Contracts\TransportContracts\TransportServiceInterface;
use App\Contracts\UserContracts\CreateUserContract;
use App\Contracts\UserContracts\GetUserServiceInterface;
use App\Contracts\UserContracts\UpdateUserServiceInterface;
use App\Services\FileUploadServices\ImageService;
use App\Services\PaymentServices\StripeService;
use App\Services\TransportServices\TransportService;
use App\Services\UserServices\CreateUserService;
use App\Services\UserServices\GetUserService;
use App\Services\UserServices\UpdateUserService;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        Schema::defaultStringLength(191);

        if (App::environment(['production', 'staging']))
        {
            $url->forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            GetUserServiceInterface::class,
            GetUserService::class
        );
        $this->app->bind(
            UpdateUserServiceInterface::class,
            UpdateUserService::class
        );
        $this->app->bind(
            TransportServiceInterface::class,
            TransportService::class
        );
        $this->app->bind(
            ImageServiceInterface::class,
            ImageService::class
        );
        $this->app->bind(
            CreateUserContract::class,
            CreateUserService::class
        );
        $this->app->bind(
            PaymentInterface::class,
            StripeService::class
        );
    }
}
