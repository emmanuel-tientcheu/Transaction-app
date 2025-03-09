<?php

namespace App\Transaction\UseCases;

use App\Core\Ports\IDgenerator;
use App\Transaction\Entities\Transaction;
use App\Transaction\Ports\ITransactionRepository;
use App\User\Entities\User;
use App\User\Ports\IUserRepository;

class CreateTransaction {

    private ITransactionRepository $repository;
    private IUserRepository $userRepository;
    private IDgenerator $idgenerator;

    public function __construct(ITransactionRepository $repository, IUserRepository $userRepository, IDgenerator $idgenerator)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->idgenerator = $idgenerator;

    }

    var $transaction_type = ['transfer', 'deposit', 'withdrawal'];

    public function execute($data, User $user) {

        $id = $this->idgenerator->generate();

        if(!in_array($data['type'], $this->transaction_type)) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json(['error' => 'Impossible de faire cette oppération.'], 404)
            );
        }

        $transaction = new Transaction([
            'id' => $id,
            'sender_id' => $user->id,
            'type' => $data['type'],
            'description' => $data['description'] ?? '',
            'transferred_at' => now()->toDateString(),
        ]);


        if($data['type'] == 'transfer' || $data['type'] == 'withdrawal') {
            if($data['amount'] > $user->amount) {
                throw new \Illuminate\Http\Exceptions\HttpResponseException(
                    response()->json(['error' => 'Le solde de votre compte est insuffisant.'], 401)
                );
            }
        }

        if($data['type'] == 'transfer') {
            $receiveUser = $this->userRepository->findById($data['receiver_id']);
            if(!$receiveUser) {
                throw new \Illuminate\Http\Exceptions\HttpResponseException(
                    response()->json(['error' => 'Cet utilisateur n\'existe pas.'], 404)
                );
            }


            $transaction['receiver_id'] = $data['receiver_id'];
            $transaction['amount'] = $data['amount'];


            $user->amount = $user->amount - $data['amount'];
            $user->update();

            $receiveUser->amount = $receiveUser->amount + $data['amount'];
            $receiveUser->update();


            $result = $this->repository->create($transaction);
            return $result;

        } elseif($data['type'] == 'withdrawal') {

            $transaction['amount'] = $data['amount'];
            $user->amount = $user->amount - $data['amount'];
            $user->update();


            $result = $this->repository->create($transaction);
            return $result;

        } else {
            $transaction['amount'] = $data['amount'];

            $user->amount = $user->amount + $data['amount'];
            $user->update();
            $result = $this->repository->create($transaction);

            return $result;
        }


    }
}
