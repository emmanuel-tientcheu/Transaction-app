<?php

namespace App\Core\Services\Hasher;

interface IHasher {
    public function hashPassword(string $password): string;
    public function verifyPassword(string $password, string $hashedPassword): bool;
}
