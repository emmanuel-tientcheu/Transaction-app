<?php

namespace App\Transaction\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receiver_id' => 'nullable|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:transfer,deposit,withdrawal',
            'transferred_at' => 'nullable|date_format:Y-m-d',
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_id.required' => 'Le champ destinataire est requis.',
            'receiver_id.exists' => 'Le destinataire doit exister dans la base de données.',
            'amount.required' => 'Le montant est requis.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être supérieur ou égal à 1.',
            'type.required' => 'Le type de transaction est requis.',
            'type.in' => 'Le type de transaction doit être l\'un des suivants: transfer, deposit, withdrawal.',
            'transferred_at.date_format' => 'La date de transfert doit être au format Y-m-d.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'La validation a échoué.',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
