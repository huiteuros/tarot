<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'played_at' => 'required|date',
            'nombre_joueurs' => 'required|in:4,5',
            'contract_type' => 'required|in:petite,garde,garde_sans,garde_contre',
            'points' => 'required|integer|min:0|max:91',
            'oudlers' => 'required|integer|min:0|max:3',
            'contract_success' => 'required|boolean',
            'preneur_id' => 'required|exists:users,id',
            'attaquant_id' => 'nullable|exists:users,id',
            'player_ids' => 'required|array',
            'player_ids.*' => 'exists:users,id',
            'petit_au_bout' => 'nullable|boolean',
            'petit_au_bout_team' => 'nullable|in:attaque,defense',
            'poignee' => 'nullable|in:aucune,simple,double,triple',
            'poignee_team' => 'nullable|in:attaque,defense',
            'chelem' => 'nullable|in:aucun,annonce_reussi,annonce_chute,non_annonce',
            'miseres' => 'nullable|array',
            'miseres.*' => 'nullable|array',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'played_at.required' => 'La date de la partie est requise.',
            'nombre_joueurs.required' => 'Le nombre de joueurs est requis.',
            'nombre_joueurs.in' => 'Le nombre de joueurs doit être 4 ou 5.',
            'contract_type.required' => 'Le type de contrat est requis.',
            'points.required' => 'Le nombre de points est requis.',
            'points.min' => 'Le nombre de points doit être au moins 0.',
            'points.max' => 'Le nombre de points doit être au maximum 91.',
            'oudlers.required' => 'Le nombre de bouts est requis.',
            'oudlers.min' => 'Le nombre de bouts doit être entre 0 et 3.',
            'oudlers.max' => 'Le nombre de bouts doit être entre 0 et 3.',
            'contract_success.required' => 'Le résultat du contrat est requis.',
            'preneur_id.required' => 'Le preneur est requis.',
            'preneur_id.exists' => 'Le preneur sélectionné n\'existe pas.',
            'attaquant_id.exists' => 'L\'attaquant sélectionné n\'existe pas.',
            'player_ids.required' => 'Les joueurs sont requis.',
            'player_ids.*.exists' => 'Un des joueurs sélectionnés n\'existe pas.',
        ];
    }

    /**
     * Validation supplémentaire après les règles de base
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Vérifier que le nombre de joueurs correspond
            if (count($this->input('player_ids', [])) != $this->input('nombre_joueurs')) {
                $validator->errors()->add(
                    'player_ids',
                    'Le nombre de joueurs sélectionnés ne correspond pas.'
                );
            }
        });
    }
}
