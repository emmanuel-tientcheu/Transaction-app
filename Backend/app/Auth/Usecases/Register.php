<?php

namespace App\Auth\UseCases;

use App\User\UseCases\CreateUser;

class Register {
    private CreateUser $createUser;

    public function __construct(CreateUser $createUser) {
        $this->createUser = $createUser;
    }

    public function execute($data) {
        $user = $this->createUser->execute($data);
        $token = $user->createToken('INTERVIEW_APP')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
