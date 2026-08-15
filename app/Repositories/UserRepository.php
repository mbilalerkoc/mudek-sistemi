<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->firstOrFail();
    }

    public function getByRole($role)
    {
        return $this->model->where('role', $role)->with('academicTitle')->get();
    }   

    public function getAllWithTitles()
    {
        return \App\Models\User::with('academicTitle')->get();
    }
}