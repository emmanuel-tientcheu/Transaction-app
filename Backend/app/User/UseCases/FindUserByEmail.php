<?php

namespace App\User\UseCases;

use App\User\Ports\IUserRepository;

class FindUserByEmail {

    private IUserRepository $repository;

    public function __construct(IUserRepository $repository) {
         $this->repository = $repository;
    }

    public function execut($email) {
       return $this->repository->findByEmail($email);
    }
}
