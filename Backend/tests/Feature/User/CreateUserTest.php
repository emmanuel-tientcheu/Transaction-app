<?php

namespace Tests\Feature\User;

use App\Core\Adapters\FixedIdGenerator;
use App\Core\Ports\IDgenerator;
use App\Core\Services\Hasher\Hasher;
use App\Core\Services\Hasher\IHasher;
use App\User\Adapters\InMemoryUserRepository;
use App\User\Entities\User;
use App\User\Ports\IUserRepository;
use App\User\UseCases\CreateUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;




class CreateUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     var IUserRepository $repository;
     var IDgenerator $idgenerator;
     var IHasher $hasher;
     var CreateUser $useCase;


     protected function setUp(): void
     {
        parent::setUp();

        $this->repository = new InMemoryUserRepository();
        $this->idgenerator = new FixedIdGenerator();
        $this->hasher = new Hasher();
        $this->useCase = new CreateUser($this->repository, $this->idgenerator, $this->hasher);

    }

    public function test_create_user(): void
    {


         $payload = [
            "name" => "johndoe",
            'password' => 'password',
            'email' => 'johndoe@gmail.com'
        ];


         $this->useCase->execute($payload);
         $this->assertEquals(count($this->repository->database), 1);
         $this->assertEquals('johndoe', $this->repository->database[0]['name']);
         $this->assertEquals('johndoe@gmail.com', $this->repository->database[0]['email']);
         $this->assertEquals("id-1", $this->repository->database[0]->id);
    }

    public function test_create_user_with_existing_email_throws_error(): void
    {
        $johndoe = [
            "name" => "johndoe",
            'password' => 'password',
            "email" => "johndoe@gmail.com"
        ];

        $this->useCase->execute($johndoe);

        $payload = [
            "name" => "charles",
            'password' => 'password',
            'email' => 'johndoe@gmail.com' // Email déjà existant
        ];


        try {
            $this->useCase->execute($payload);
            $this->fail('Exception not thrown');
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getContent(), true);

            // Vérifie si le message d'erreur est celui attendu
            $this->assertEquals('Un utilisateur avec cet email existe déjà.', $responseData['error']);
        }

    }

}
