<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProduitRequest extends FormRequest
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
            'nom' => 'required',
            'description' => 'nullable|string',
            'prix' => 'required|numeric',
            'quantite_stock' => 'required|integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif|max:2048',
            'idCategory' => 'required|exists:categories,id',
        ];
    }
}
