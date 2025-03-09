<?php

namespace App\User\Ports;

use App\User\Entities\User;

interface IUserRepository {
    public function create(User $user);
    public function findById(string $id);
    public function findByEmail(string $email);
    public function update(string $id, $data);
    public function delete(string $id);
}
