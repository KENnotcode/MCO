<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Violation;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::useBootstrap();

        View::composer(['adminLayout', 'admin'], function ($view) {
            $userCount = User::where('is_admin', false)->count();
            $violationCount = Violation::count();
            $view->with('userCount', $userCount)->with('violationCount', $violationCount);
        });
    }
}