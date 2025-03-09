<?php

namespace App\User\UseCases;

use App\Core\Services\Hasher\IHasher;
use App\User\Entities\User;
use App\User\Ports\IUserRepository;

class UpdateUser {

    private IUserRepository $repository;
    private IHasher $hasher;


    public function __construct(IUserRepository $repository, IHasher $hasher) {
        $this->repository = $repository;
        $this->hasher = $hasher;
    }

    public function execute(string $id, User $user, $data) {

        if($user->id != $id) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['error' => 'Vous n\'est pas hotorisé a mener cette action.'], 401)
            );
        }
        
        if(isset($data['password'])) $data['password'] = $this->hasher->hashPassword($data['password']);

        $user = $this->repository->update($id, $data);

        if (!$user) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['error' => 'Cet utilisateur n\'existe pas.'], 404)
            );
        }

        return $user;
    }

}
