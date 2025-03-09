<?php

namespace App\Core\Services\Hasher;
use Illuminate\Support\Facades\Hash;

class Hasher implements IHasher {

    public function hashPassword(string $password): string {
        return Hash::make($password);
    }

    public function verifyPassword(string $password, string $hashedPassword): bool {
        return Hash::check($password, $hashedPassword);
    }
}
