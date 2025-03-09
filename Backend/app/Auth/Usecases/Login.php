<?php

namespace App\Auth\UseCases;

use App\Core\Services\Hasher\IHasher;
use App\User\Ports\IUserRepository;

class Login {
    private IUserRepository $repository;
    private IHasher $hasher;

    public function __construct(IUserRepository $repository, IHasher $hasher) {
        $this->repository = $repository;
        $this->hasher = $hasher;
    }

    public function execute($data) {
        $user = $this->repository->findByEmail($data['email']);

        if (!$user) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['error' => 'Cette utilisateur n\'existe pas.'], 404)
            );
        }

        if (!$this->hasher->verifyPassword($data['password'], $user->password)) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['error' => 'Mot de passe incorrecte.'], 401)
            );
        }

        return [
            'user' => $user,
            'token' => $user->createToken('INTERVIEW_APP')->plainTextToken,
        ];
    }
}
