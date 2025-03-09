<?php

namespace Tests\Feature\User;

use App\User\Adapters\InMemoryUserRepository;
use App\User\Ports\IUserRepository;
use App\User\UseCases\UpdateUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    var UpdateUser $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestUser();
        $this->useCase = new UpdateUser($this->userRepository, $this->hasher);

        $steven = [
            "id" => "id-2",
            "name" => "johndoe",
            'password' => 'password',
            'email' => 'steven@gmail.com'
        ];

        $this->createUser->execute($steven);

    }

    public function test_update_user(): void
    {
        $payload = [
            'name' => 'new name',
            'password' => 'new password',
        ];

        $this->useCase->execute($this->userRepository->database[0]['id'], $this->userRepository->database[0], $payload);

        $this->assertEquals(count($this->userRepository->database), 2);
        $this->assertTrue($this->hasher->verifyPassword('new password', $this->userRepository->database[0]['password']));
        $this->assertEquals($this->userRepository->database[0]['name'], 'new name');
        $this->assertEquals($this->userRepository->database[0]['id'], 'id-1');

    }

    public function test_try_to_update_user_should_trow() : void
    {
        $payload = [
            'name' => 'new name',
        ];

        try {
            $this->useCase->execute('id-2', $this->userRepository->database[0], $payload);
            $this->fail('Exception not thrown');
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getContent(), true);

            // Vérifie si le message d'erreur est celui attendu
            $this->assertEquals('Vous n\'est pas hotorisé a mener cette action.', $responseData['error']);
        }
    }

}
