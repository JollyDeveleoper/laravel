<?php

namespace App\Http\Request;

use Illuminate\Http\Request;

class GetRequest extends Request
{
    public function rules()
    {
        return [
            'title' => 'required'
        ];
    }
}
