<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\CourseRepository;
use App\Repositories\Interfaces\StudentExamRepositoryInterface;
use App\Repositories\StudentExamRepository;
use App\Repositories\Interfaces\StudentCourseRepositoryInterface;
use App\Repositories\StudentCourseRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(StudentExamRepositoryInterface::class, StudentExamRepository::class);
        $this->app->bind(StudentCourseRepositoryInterface::class, StudentCourseRepository::class);
    }

    public function boot(): void
    {
        //
    }
}