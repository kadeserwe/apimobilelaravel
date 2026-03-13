<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcedureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *php artisan make:request ProcedureRequest 
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'libelle' => 'required|min:3',
            'description' => 'required|min:3',
            'bailleur_id'=> 'required|exists:bailleurs,id',
            'demande_ids' => 'array',
            'demande_ids.*' => 'exists:demandes,id',
        ];
    }
     public function messages()
    {
        return [
            'libelle.required' => 'Le champ libellé est obligatoire',
            'libelle.min' => 'le nom doit contenir au moins 3 caractères',
            'description.required' => 'Le champ description est obligatoire',
            'description.min' => 'le nom doit contenir au moins 3 caractères',
             'bailleur_id.required' => 'bailleur id  est introuvable',
          //  'demande_ids.array'=> 'demande  id  est introuvable',
        ];
        
    }
}
