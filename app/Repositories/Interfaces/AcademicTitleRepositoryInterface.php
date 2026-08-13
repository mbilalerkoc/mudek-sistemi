<?php

namespace App\Repositories\Interfaces;

interface AcademicTitleRepositoryInterface extends BaseRepositoryInterface
{
    public function findByTitle($title);
}