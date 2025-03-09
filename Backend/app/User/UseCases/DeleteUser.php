<?php

namespace App\User\UseCases;

use App\User\Entities\User;
use App\User\Ports\IUserRepository;

class DeleteUser {
    private IUserRepository $repository;

    public function __construct(IUserRepository $repository) {
        $this->repository = $repository;

    }

    public function execute(string $id, User $user) {

        if($user->id != $id) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['error' => 'Vous n\'est pas hotorisé a mener cette action.'], 401)
            );
        }

        $result = $this->repository->delete($id);

        if(!$result) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['error' => 'Cet utilisateur n\'existe pas.'], 404)
            );
        }

    }
}
