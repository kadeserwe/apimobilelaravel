<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBailleurRequest extends FormRequest
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
            'libelle' => 'sometimes|min:3',
            'description' => 'sometimes|min:3',
            'fichier' => 'sometimes|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ];
    }
     public function messages()
    {
        return [
          //  'libelle.sometimes' => 'Le champ libellé nest obligatoire',
            'libelle.min' => 'le nom doit contenir au moins 3 caractères',
           // 'description.sometimes' => 'Le champ description nest obligatoire',
            'description.min' => 'le nom doit contenir au moins 3 caractères',
        ];
    }
}
