<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

  
      @return array<string, 
    public function rules(): array
    {
        return [
            'requester_name' => ['required', 'string', 'max:255'],
            'book_id' => ['required', 'integer', 'exists:books,id'],
        ];
    }
}

