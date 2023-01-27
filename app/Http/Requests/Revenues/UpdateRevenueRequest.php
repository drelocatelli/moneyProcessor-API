<?php
namespace App\Http\Requests\Revenues;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRevenueRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'title' => 'required',
            'total' => 'required|numeric'
        ];
    }
    
}