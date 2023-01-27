<?php
namespace App\Http\Requests\Expenses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest 
{
    public function authorize(): bool 
    {
        return true;
    }

    public function rules(): array 
    {
        return [
            'id' => 'required',
            'title' => 'nullable',
            'total' => 'required|numeric'
        ];
    }
}