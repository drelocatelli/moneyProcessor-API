<?php
namespace App\Http\Requests\Resume;

use Illuminate\Foundation\Http\FormRequest;

class ResumeRequest extends FormRequest
{

    public function authorize() : bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ];
    }
    
}