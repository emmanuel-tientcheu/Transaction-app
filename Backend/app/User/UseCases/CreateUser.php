<?php

namespace App\User\UseCases;

use App\Core\Ports\IDgenerator;
use App\Core\Services\Hasher\IHasher;
use App\User\Entities\User;
use App\User\Ports\IUserRepository;

class CreateUser {

    private IUserRepository $repository;
    private IDgenerator $idgenerator;
    private IHasher $hasher;

    public  function __construct(IUserRepository $repository, IDgenerator $idgenerator, IHasher $hasher) {
        $this->repository = $repository;
        $this->idgenerator = $idgenerator;
        $this->hasher = $hasher;
    }

    public function execute($data) {

        $existingUser = $this->repository->findByEmail($data['email']);

        if ($existingUser) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['error' => 'Un utilisateur avec cet email existe déjà.'], 401)
            );
        }

        $id = $this->idgenerator->generate();

        $user = new User([
            'id' => $id,
            'name' => $data['name'],
            'password' => $this->hasher->hashPassword($data['password']),
            'email' => $data['email'],
            'amount' => 10000
        ]);

        $result = $this->repository->create($user);
        return $result;
    }
}
