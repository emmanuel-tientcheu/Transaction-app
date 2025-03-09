<?php

namespace App\Transaction\Ports;

use App\Transaction\Entities\Transaction;

interface ITransactionRepository {
    public function create(Transaction $transaction);
}
