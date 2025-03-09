<?php

namespace Tests\Feature\Auth;

use App\Auth\UseCases\Login;
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

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    var IUserRepository $repository;
    var IHasher $hasher;
    var Login $useCase;

    var IDgenerator $idgenerator;
    var CreateUser $createUser;


    var $johndoe = [
        "name" => "johndoe",
        'password' => 'password',
        'email' => 'johndoe@gmail.com'
    ];

    protected function setUp(): void
     {
         parent::setUp();

         $this->repository = new InMemoryUserRepository();
         $this->hasher = new Hasher();
         $this->idgenerator = new FixedIdGenerator();

         $this->createUser = new CreateUser($this->repository, $this->idgenerator, $this->hasher);
         $this->useCase = new Login($this->repository, $this->hasher);

         $this->createUser->execute($this->johndoe);
     }

    public function test_login_user(): void
    {

        $payload = [
            'password' => 'password',
            'email' => 'johndoe@gmail.com'
        ];

        $result = $this->useCase->execute($payload);
        $this->assertEquals('johndoe', $result['user']['name']);
        $this->assertNotNull($result['token']);

    }

    public function test_login_user_with_bad_password_should_throw()
    {
        $payload = [
            'password' => 'bad password',
            'email' => 'johndoe@gmail.com'
        ];

        try {
            $this->useCase->execute($payload);
            $this->fail('Exception not thrown');
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getContent(), true);

            // Vérifie si le message d'erreur est celui attendu
            $this->assertEquals('Mot de passe incorrecte.', $responseData['error']);
        }
    }

    public function test_login_user_not_exist()
    {
        $payload = [
            'password' => 'password',
            'email' => 'badmail@gmail.com'
        ];

        try {
            $this->useCase->execute($payload);
            $this->fail('Exception not thrown');
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getContent(), true);

            // Vérifie si le message d'erreur est celui attendu
            $this->assertEquals('Cette utilisateur n\'existe pas.', $responseData['error']);
        }

    }
}
