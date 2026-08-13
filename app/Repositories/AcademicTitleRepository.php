<?php

namespace App\Repositories;

use App\Models\AcademicTitle;
use App\Repositories\Interfaces\AcademicTitleRepositoryInterface;

class AcademicTitleRepository extends BaseRepository implements AcademicTitleRepositoryInterface
{
    public function __construct(AcademicTitle $model)
    {
        parent::__construct($model);
    }

    public function findByTitle($title)
    {
        return $this->model->where('title', $title)->firstOrFail();
    }
}