<?php

namespace App\Repositories;

use App\Models\UserCourse;
use App\Repositories\Interfaces\UserCourseRepositoryInterface;

class UserCourseRepository extends BaseRepository implements UserCourseRepositoryInterface
{
    public function __construct(UserCourse $model)
    {
        parent::__construct($model);
    }

    public function getByUser($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getByCourse($courseId)
    {
        return $this->model->where('course_id', $courseId)->get();
    }
}