<?php

namespace Tests\Feature\Services;

use App\Core\Services\Hasher\Hasher;
use App\Core\Services\Hasher\IHasher;
use Tests\TestCase;

class HasherTest extends TestCase
{
    var IHasher $hasher;

    public function test_hash_password(): void {

        $hasher = new Hasher();

        $clearPassword = 'secret_password';
        $hasPassword = $hasher->hashPassword($clearPassword);


        $this->assertNotEquals($clearPassword, $hasPassword);

        $this->assertTrue($hasher->verifyPassword($clearPassword, $hasPassword));
    }
}
