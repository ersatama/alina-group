<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\Service;

class UserCommandService extends Service
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function attempt($credentials): string|null
    {
        if ($token = auth()->attempt($credentials)) {
            return $token;
        }
        return null;
    }
}
