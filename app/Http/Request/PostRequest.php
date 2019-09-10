<?php

namespace App\Http\Request;

use Illuminate\Http\Request;

class PostRequest extends Request
{
    public function rules()
    {
        return [
            '_token' => 'required'
        ];
    }
}
