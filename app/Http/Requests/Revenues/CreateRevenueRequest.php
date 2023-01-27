<?php
use Illuminate\Foundation\Http\FormRequest;

class CreateRevenueRequest extends FormRequest
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