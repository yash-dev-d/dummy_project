<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignBooksRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'shelf_id' => 'required|exists:shelf,id,user_id,' . $this->input('user_id'),
            'book_id' => 'required|exists:books,id',
        ];
    }
    
}
