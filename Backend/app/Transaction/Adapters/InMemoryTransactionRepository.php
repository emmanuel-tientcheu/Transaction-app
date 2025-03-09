<?php

namespace App\Transaction\Adapters;

use App\Transaction\Entities\Transaction;
use App\Transaction\Ports\ITransactionRepository;

class InMemoryTransactionRepository implements ITransactionRepository {

    var $database = [];

    public function create(Transaction $transaction) {
        array_push($this->database, $transaction);
        return $transaction;
    }
}
