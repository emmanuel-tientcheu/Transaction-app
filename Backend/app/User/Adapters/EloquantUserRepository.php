<?php

namespace App\User\Adapters;

use App\User\Entities\User;
use App\User\Ports\IUserRepository;

class EloquantUserRepository implements IUserRepository {

    public function findById(string $id)
    {
        return User::where('id', $id)->first();
    }

    public function create(User $user)
    {
        $user->save();
        return $user;
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function update(string $id, $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data->all());
            return $user;
        }
        return null;
    }

    public function delete(string $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return true;
        }

        return false;
    }

 }
