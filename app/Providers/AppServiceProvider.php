<?php

namespace App\Providers;

use App\Events\UserSaved;
use App\Listeners\SaveUserBackgroundInformation;
use App\Services\UserServiceInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Route::macro('softDeletes', function ($name, $controller) {
            // Route to list trashed items
            Route::get("{$name}/trashed", [$controller, 'trashed'])->name("{$name}.trashed");

            // Route to restore a soft deleted item
            Route::patch("{$name}/restore", [$controller, 'restore'])->name("{$name}.restore");

            // Route to permanently delete an item
            Route::delete("{$name}/delete", [$controller, 'forceDelete'])->name("{$name}.delete");
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // Register events
        Event::listen(UserSaved::class, SaveUserBackgroundInformation::class,);
    }
}
