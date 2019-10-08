<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\JsonResponse;
use function request;

class ApiController extends BaseApiController
{
    const ALL_DAYS = 'all';

    /**
     * Отдаем все пары на каждый день
     * Сортировка по времени и дням (пн-сб)
     *
     * @return JsonResponse
     */
    public function getAllCouples()
    {
        $list = $this->schedule->getList(self::ALL_DAYS);
        return $this->response($list);
    }

    /**
     * Отдаем пары на 1 день
     * Соритровка по времени начала пары
     *
     * @return JsonResponse
     */
    public function getCouple($day)
    {
        request()->merge(['day' => $day]);
        $rules = ['day' => 'required|numeric|max:7'];
        if (!$this->validate($rules)) {
            return $this->response(['success' => false, 'message' => 'day is incorrect']);
        }

        $list = $this->schedule->schedule(request()->get('day'));
        return $this->response($list);
    }

    /**
     * Отдаем следующую пару
     * Сортировка по времени
     *
     * @return JsonResponse
     */
    public function getNextCouple()
    {
        $list = $this->schedule->nextCouple();
        return $this->response($list);
    }


    /**
     * Отдаем врем на сервере
     *
     * @return int
     */
    public function serverTime()
    {
        return $this->response(['time' => time()]);
    }

}
