<?php

namespace App\User\Adapters;

use App\User\Entities\User;
use App\User\Ports\IUserRepository;

class InMemoryUserRepository implements IUserRepository {

    var $database = [];

    public function findById(string $id)
    {
        foreach ($this->database as $user) {
            if ($user->id === $id) {
                return $user;
            }
        }

        return null;
    }

    public function create(User $user)
    {
        array_push($this->database, $user);
        return $user;
    }

    public function findByEmail(string $email)
    {
        foreach ($this->database as $user) {
            if ($user->email === $email) {
                return $user;
            }
        }

        return null;
    }

    public function update(string $id, $data)
    {
        foreach ($this->database as &$user) {
            if ($user->id === $id) {
                foreach ($data as $key => $value) {
                    if (array_key_exists($key, $user->getAttributes())) {
                        $user->$key = $value;
                    }
                }
                return $user;
            }
        }
        return null;
    }

    public function delete(string $id) {
        foreach ($this->database as $index => $user) {
            if ($user->id === $id) {
                unset($this->database[$index]);
                return true;
            }
        }

        return false;
    }
}
