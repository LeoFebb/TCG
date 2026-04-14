<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\ValidatorRegistrationRequest;

class ValidatorRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email:rfc,dns', 'unique:users,email'],
            'password'           => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'identity_document'  => ['required', 'file', 'mimes:jpeg,jpg,pdf', 'max:5120'],
            'tcg_categories'     => ['required', 'array', 'min:1'],
            'tcg_categories.*'   => ['string', 'in:mtg,pokemon,yugioh,onepiece,dragon_ball_super,naruto'],
            'vat_number'         => ['required', 'string', 'min:11', 'max:16', 'unique:users,vat_number'],
            'address'            => ['required', 'string', 'max:255'],
            'city'               => ['required', 'string', 'max:100'],
            'zip'                => ['required', 'string', 'max:10'],
            'phone'              => ['required', 'string', 'max:20'],
            'accept_validator_terms' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'identity_document.required' => 'Il documento d\'identita e obbligatorio.',
            'identity_document.mimes'    => 'Il documento deve essere in formato JPEG o PDF.',
            'identity_document.max'      => 'Il documento non puo superare 5MB.',
            'tcg_categories.required'    => 'Seleziona almeno una categoria TCG.',
            'vat_number.required'        => 'La partita IVA e obbligatoria per i validatori.',
            'vat_number.unique'          => 'Questa partita IVA e gia registrata.',
            'vat_number.min'             => 'La partita IVA deve essere di almeno 11 caratteri.',
            'accept_validator_terms.accepted' => 'Devi accettare i termini per i validatori.',
        ];
    }
}