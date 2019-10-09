<?php


namespace App\Http\Controllers\Api;

use App\Http\Models\Schedule;
use Illuminate\Support\Facades\Validator;

abstract class BaseApiController
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_PAYMENT_REQUIRED = 402;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    protected $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    function response(array $args = [], int $code = self::HTTP_OK)
    {
        return response()->json($args, $code);
    }

    function validate(array $rules): bool
    {
        $errors = Validator::make(\request()->all(), $rules);
        if ($errors->fails()) {
            return false;
        }
        return true;
    }

}
