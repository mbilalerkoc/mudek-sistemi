<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface extends BaseRepositoryInterface
{
    public function getCoursesByTeacher($teacher);
    public function getCourseDetailsForForm($ders_id);
}