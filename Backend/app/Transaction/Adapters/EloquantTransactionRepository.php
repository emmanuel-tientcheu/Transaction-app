<?php

namespace App\Transaction\Adapters;

use App\Transaction\Entities\Transaction;
use App\Transaction\Ports\ITransactionRepository;

class EloquantTransactionRepository implements ITransactionRepository {

    public function create(Transaction $transaction)
    {
        $transaction->save();
        return $transaction;
    }
}
