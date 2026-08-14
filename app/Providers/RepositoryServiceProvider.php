<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\BaseRepository;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

use App\Repositories\Interfaces\AcademicTitleRepositoryInterface;
use App\Repositories\AcademicTitleRepository;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\CourseRepository;

use App\Repositories\Interfaces\UserCourseRepositoryInterface;
use App\Repositories\UserCourseRepository;

use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\StudentRepository;

use App\Repositories\Interfaces\StudentCourseRepositoryInterface;
use App\Repositories\StudentCourseRepository;

use App\Repositories\Interfaces\ExamRepositoryInterface;
use App\Repositories\ExamRepository;

use App\Repositories\Interfaces\StudentExamRepositoryInterface;
use App\Repositories\StudentExamRepository;

use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use App\Repositories\AssignmentRepository;

use App\Repositories\Interfaces\ExamAssignmentRepositoryInterface;
use App\Repositories\ExamAssignmentRepository;

use App\Repositories\Interfaces\AssignmentSubmissionRepositoryInterface;
use App\Repositories\AssignmentSubmissionRepository;

use App\Repositories\Interfaces\QuestionRepositoryInterface;
use App\Repositories\QuestionRepository;

use App\Repositories\Interfaces\AnswerRepositoryInterface;
use App\Repositories\AnswerRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AcademicTitleRepositoryInterface::class, AcademicTitleRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(UserCourseRepositoryInterface::class, UserCourseRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(StudentCourseRepositoryInterface::class, StudentCourseRepository::class);
        $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        $this->app->bind(StudentExamRepositoryInterface::class, StudentExamRepository::class);
        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);
        $this->app->bind(ExamAssignmentRepositoryInterface::class, ExamAssignmentRepository::class);
        $this->app->bind(AssignmentSubmissionRepositoryInterface::class, AssignmentSubmissionRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(AnswerRepositoryInterface::class, AnswerRepository::class);
    }

    public function boot(): void
    {
        //
    }
}