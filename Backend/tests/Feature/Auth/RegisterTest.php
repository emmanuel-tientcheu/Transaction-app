<?php

namespace Tests\Feature\Auth;

use App\Auth\UseCases\Register;
use App\Core\Adapters\FixedIdGenerator;
use App\Core\Ports\IDgenerator;
use App\Core\Services\Hasher\Hasher;
use App\Core\Services\Hasher\IHasher;
use App\User\Adapters\InMemoryUserRepository;
use App\User\Ports\IUserRepository;
use App\User\UseCases\CreateUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    var IUserRepository $repository;
    var IDgenerator $idgenerator;
    var IHasher $hasher;
    var CreateUser $createUser;
    var Register $useCase;

     protected function setUp(): void
     {
         parent::setUp();

         $this->repository = new InMemoryUserRepository();
         $this->idgenerator = new FixedIdGenerator();
         $this->hasher = new Hasher();
         $this->createUser = new CreateUser($this->repository, $this->idgenerator, $this->hasher);
         $this->useCase = new Register($this->createUser);
     }

    public function test_register_user(): void
    {
        $payload = [
            "name" => "johndoe",
            'password' => 'password',
            'email' => 'johndoe@gmail.com'
        ];

        $result = $this->useCase->execute($payload);

        $this->assertEquals('johndoe', $result['user']['name']);
        $this->assertNotNull($result['token']);
    }
}
