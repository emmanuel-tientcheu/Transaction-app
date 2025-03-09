<?php

namespace App\User\Controllers;

use App\Http\Controllers\Controller;
use App\User\Entities\User;
use App\User\Requests\CreateUserRequest;
use App\User\Requests\UpdateUserRequest;
use App\User\Resources\UserResource;
use App\User\UseCases\CreateUser;
use App\User\UseCases\DeleteUser;
use App\User\UseCases\UpdateUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    private CreateUser $createUser;
    private UpdateUser $updateUser;
    private DeleteUser $deleteUser;

    public function __construct(CreateUser $createUser, UpdateUser $updateUser, DeleteUser $deleteUser) {
        $this->createUser = $createUser;
        $this->updateUser = $updateUser;
        $this->deleteUser = $deleteUser;
    }

    public function index() {
        $userId = Auth::id();
        $users = User::where('id', '!=', $userId)->get();

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'data' => UserResource::collection($users)
        ], 201);
    }

    public function show($id) {}

    public function store(CreateUserRequest $request) {


        $result = $this->createUser->execute($request);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'data' => new UserResource($result)
        ], 201);
    }

    public function update(UpdateUserRequest $request, $id){

        $user = Auth::user();

        $result = $this->updateUser->execute($id, $user, $request);

        return response()->json([
            'message' => 'Mise a jour des données de l\'utilisateur',
            'data' => new UserResource($result)
        ], 201);
    }

    public function destroy($id) {

        $user = Auth::user();

        $result = $this->deleteUser->execute($id, $user);

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès',
            'data' => new UserResource($user)
        ], 201);
    }
}
