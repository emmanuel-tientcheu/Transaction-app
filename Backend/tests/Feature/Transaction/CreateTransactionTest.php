<?php

namespace Tests\Feature\Transaction;

use App\Transaction\Adapters\InMemoryTransactionRepository;
use App\Transaction\Ports\ITransactionRepository;
use App\Transaction\UseCases\CreateTransaction;

use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    var ITransactionRepository $repository;
    var CreateTransaction $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createTestUser();

        $this->repository = new InMemoryTransactionRepository();
        $this->useCase = new CreateTransaction($this->repository, $this->userRepository, $this->idgenerator);

        $steven = [
            "id" => "id-2",
            "name" => "steven",
            'password' => 'password',
            'email' => 'steven@gmail.com',
            'amount' => 10000
        ];

        $this->createUser->execute($steven);

    }

    public function test_create_deposit_transaction(): void
    {
        $payload = [
            //'sender_id' => $this->userRepository->database[0]['id'],
            'type' => 'deposit',
            'amount' => 5000,
            'description' => 'test description',
        ];

        $this->useCase->execute($payload, $this->userRepository->database[0]);

        $this->assertEquals($this->userRepository->database[0]['amount'], 15000);
        $this->assertEquals($this->repository->database[0]['amount'], 5000);
        $this->assertEquals($this->repository->database[0]['sender_id'], $this->userRepository->database[0]['id']);
    }

    public function test_create_withdrawal_transaction(): void
    {
        $payload = [
            'type' => 'withdrawal',
            'amount' => 5000,
            'description' => 'test description',
        ];

        $this->useCase->execute($payload, $this->userRepository->database[0]);
        $this->assertEquals($this->userRepository->database[0]['amount'], 5000);
    }

    public function test_insufficient_balance_should_throw()
    {
        $payload = [
            'type' => 'withdrawal',
            'amount' => 50000,
            'description' => 'test description',
        ];

        try {
            $this->useCase->execute($payload, $this->userRepository->database[0]);

            $this->fail('Exception not thrown');
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getContent(), true);

            // Vérifie si le message d'erreur est celui attendu
            $this->assertEquals('Le solde de votre compte est insuffisant.', $responseData['error']);
        }

    }

    public function test_bad_type_transaction_should_throw()
    {
        $payload = [
            'type' => 'bad_type',
            'amount' => 500,
            'description' => 'test description',
        ];

        try {
            $this->useCase->execute($payload, $this->userRepository->database[0]);

            $this->fail('Exception not thrown');
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getContent(), true);

            // Vérifie si le message d'erreur est celui attendu
            $this->assertEquals('Impossible de faire cette oppération.', $responseData['error']);
        }

    }

    public function test_transfer_transaction_should_throw()
    {
        $payload = [
            'type' => 'transfer',
            'amount' => 500,
            'description' => 'test description',
            'receiver_id' => 'not_found'
        ];

        try {
            $this->useCase->execute($payload, $this->userRepository->database[0]);

            $this->fail('Exception not thrown');
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            $response = $e->getResponse();
            $responseData = json_decode($response->getContent(), true);

            // Vérifie si le message d'erreur est celui attendu
            $this->assertEquals('Cet utilisateur n\'existe pas.', $responseData['error']);
        }

    }

    public function test_transfer_transaction()
    {
        $payload = [
            'type' => 'transfer',
            'amount' => 5000,
            'description' => 'test description',
            'receiver_id' => 'id-2'
        ];

        $this->userRepository->database[1]['id'] = 'id-2';

        $this->useCase->execute($payload, $this->userRepository->database[0]);

        $this->assertEquals($this->userRepository->database[0]['amount'], 5000);
        $this->assertEquals($this->userRepository->database[1]['amount'], 15000);

    }


}
