<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function createUser(array $data)
    {
        // Şifreleme iş mantığı (Business Logic) serviste yapılır
        $data['password'] = bcrypt($data['password']);
        
        return $this->userRepository->create($data);
    }

    public function updateUser($id, array $data)
    {
        // Şifre girilmişse şifrele, girilmemişse diziden çıkar ki boş güncellenmesin
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }
}