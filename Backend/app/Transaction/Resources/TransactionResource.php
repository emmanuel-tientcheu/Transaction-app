<?php

namespace App\Transaction\Resources;

use App\User\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'transferred_at' => $this->transferred_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sender' => new UserResource($this->whenLoaded('sender')), // Optionnel: Ajouter la relation avec l'utilisateur expéditeur
            'receiver' => new UserResource($this->whenLoaded('receiver')), // Optionnel: Ajouter la relation avec l'utilisateur destinataire
        ];
    }
}
