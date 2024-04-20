<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\Service;

class UserQueryService extends Service
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function firstByEmail($email)
    {
        return $this->model::where('email', $email)->first();
    }
}
