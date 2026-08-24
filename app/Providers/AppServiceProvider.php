<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event; 
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\Course;
use App\Models\StudentCourse;
use App\Observers\CourseObserver;
use App\Observers\StudentCourseObserver;
use App\Listeners\LogSuccessfulLogin; 
use App\Listeners\LogSuccessfulLogout;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
{
    $this->app->bind(
        \App\Repositories\Interfaces\AssignmentRepositoryInterface::class,
        \App\Repositories\AssignmentRepository::class
    );

    $this->app->bind(
        \App\Repositories\Interfaces\AssignmentSubmissionRepositoryInterface::class,
        \App\Repositories\AssignmentSubmissionRepository::class
    );
}

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Course::observe(CourseObserver::class);
        StudentCourse::observe(StudentCourseObserver::class);

        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(Logout::class, LogSuccessfulLogout::class);
    }
}