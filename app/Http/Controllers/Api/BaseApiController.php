<?php


namespace App\Http\Controllers\Api;

use App\Http\Models\Schedule;
use Illuminate\Support\Facades\Validator;

abstract class BaseApiController
{
    protected $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    function response(array $args = [], int $code = 200)
    {
        return response()->json($args, $code);
    }

    function validate(array $rules)
    {
        $errors = Validator::make(\request()->all(), $rules);
        if ($errors->fails()) {
            return false;
        }
        return true;
    }
}
