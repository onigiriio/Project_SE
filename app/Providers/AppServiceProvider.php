<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // Book management gate
        Gate::define('manage-books', function ($user) {
            return $user->user_type === 'librarian';
        });

        // User management gate
        Gate::define('manage-users', function ($user) {
            return $user->user_type === 'librarian';
        });

        // Fine management gates
        Gate::define('view-all-fines', function ($user) {
            return $user->user_type === 'librarian';
        });

        Gate::define('create-fine', function ($user) {
            return $user->user_type === 'librarian';
        });

        Gate::define('update-fine', function ($user) {
            return $user->user_type === 'librarian';
        });

        Gate::define('delete-fine', function ($user) {
            return $user->user_type === 'librarian';
        });
    }
}
