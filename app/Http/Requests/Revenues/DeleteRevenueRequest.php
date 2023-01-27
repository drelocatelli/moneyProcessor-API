<?php
namespace App\Http\Requests\Revenues;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRevenueRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'id' => 'required',
        ];
    }
    
}