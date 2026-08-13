<?php

namespace App\Repositories\Interfaces;

interface UserCourseRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUser($userId);
    public function getByCourse($courseId);
}