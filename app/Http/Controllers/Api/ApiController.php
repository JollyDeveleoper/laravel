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
     * Удаление пар(ы)
     */
    public function deleteCouple()
    {
        $rules = [
            'days' => 'required|array'
        ];

        if (!$this->validate($rules)) {
            return $this->response(['error' => 'days is incorrect']);
        }

        $days = request()->get('days');

        // Проверяем наличие дня в бд
        foreach ($days as $day) {
            if (!$this->schedule->find($day)) {
                return $this->response(['error' => 'day with id = ' . $day . ' is not exist']);
            }
        }

        // Удаляем по id
        $this->schedule->destroy($days);
        return $this->response(['success' => true]);
    }

    /**
     * Обновление пары
     */
    public function updateCouple($id)
    {
        request()->merge(['id' => $id]);
        $rules = [
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'day' => 'required|numeric|max:7',
            'cabinet' => 'required',
            'teacher' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ];

        if (!$this->validate($rules)) {
            return $this->response(['error' => 'Неверные данные']);
        }

        if (!$this->schedule->find(request()->get('id'))) {
            return $this->response(['error' => 'Запись не найдена']);
        }

        $this->schedule->find($id)->update(request()->all());

        return $this->response(['success' => true]);
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
