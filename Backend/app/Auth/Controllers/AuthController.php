<?php

namespace App\Auth\Controllers;

use App\Auth\Requests\LoginRequest;
use App\Auth\UseCases\Login;
use App\Http\Controllers\Controller;
use App\Auth\UseCases\Register;
use App\User\Requests\CreateUserRequest;
use App\User\Resources\UserResource;

class AuthController extends Controller {
    private Register $register;
    private Login $login;

    public function __construct(Register $useCase, Login $login) {
        $this->register = $useCase;
        $this->login = $login;
    }

    public function register(CreateUserRequest $request) {
        $result = $this->register->execute($request);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token']
            ]
        ], 201);
    }

    public function login(LoginRequest $request) {
        $result = $this->login->execute($request);

        return response()->json([
            'message' => 'Utilisateur connecté avec succès',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token']
            ]
        ], 201);
    }
}
