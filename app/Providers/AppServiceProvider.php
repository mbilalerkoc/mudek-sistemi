<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event; 
use Illuminate\Auth\Events\Login;
use App\Models\Course;
use App\Observers\CourseObserver;
use App\Listeners\LogSuccessfulLogin; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Course::observe(CourseObserver::class);

        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(Logout::class, LogSuccessfulLogout::class);

        Event::listen(
            Logout::class,
            LogSuccessfulLogout::class
        );
    }
}