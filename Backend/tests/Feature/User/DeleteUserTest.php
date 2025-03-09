<?php

namespace Tests\Feature\User;

use App\User\UseCases\DeleteUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    var DeleteUser $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestUser();
        $this->useCase = new DeleteUser($this->userRepository);
    }

    public function test_delete_user(): void
    {
        $this->useCase->execute($this->userRepository->database[0]['id'], $this->userRepository->database[0]);
        $this->assertEquals(count($this->userRepository->database), 0);

    }
}
