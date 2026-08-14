<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUser($userId);
    public function getCoursesByTeacher($teacher);
    public function getCourseDetailsForForm($ders_id);
    public function allWithUsers();
}