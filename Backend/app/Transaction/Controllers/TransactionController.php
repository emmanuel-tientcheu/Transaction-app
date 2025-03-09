<?php

namespace App\Transaction\Controllers;

use App\Http\Controllers\Controller;
use App\Transaction\Entities\Transaction;
use App\Transaction\Requests\CreateTransactionRequest;
use App\Transaction\Resources\TransactionResource;
use App\Transaction\UseCases\CreateTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller {
    private CreateTransaction $createTransaction;



    public function __construct(CreateTransaction $createTransaction) {
        $this->createTransaction = $createTransaction;
    }

    public function index() {
        $userId = Auth::id();

        $query = Transaction::with('receiver')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            });

        // Récupération des paramètres GET
        $type = request()->query('type');
        $date = request()->query('date');

        if ($type !== null) {
            $query->where('type', $type);
        }

        if ($date !== null) {
            $query->whereDate('transferred_at', $date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'message' => 'Transactions récupérées avec succès',
            'data' => TransactionResource::collection($transactions),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'has_more_pages' => $transactions->hasMorePages(),
            ],
        ], 200);
    }

    public function store(CreateTransactionRequest $request) {

        $user = Auth::user();

        $result = $this->createTransaction->execute($request, $user);

        return response()->json([
            'message' => 'Transaction créé avec succès',
            'data' => new TransactionResource($result)
        ], 201);
    }

    public function getUserTransaction($userId) {

        $query = Transaction::with('receiver')
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            });


        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'message' => 'Transactions récupérées avec succès',
            'data' => TransactionResource::collection($transactions),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'has_more_pages' => $transactions->hasMorePages(),
            ],
        ], 200);

    }
}


