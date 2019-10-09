<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use function request;

class ApiController extends BaseApiController
{
    const ALL_DAYS = 'all';
    const CREATE_OR_UPDATE_RULES = [
        'id' => 'required|integer',
        'name' => 'required|string|max:255',
        'day' => 'required|numeric|max:7',
        'cabinet' => 'required',
        'teacher' => 'required|string|max:255',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i',
    ];

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
            return $this->response(['error' => 'Некорректный день'], self::HTTP_BAD_REQUEST);
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
     *
     * @return JsonResponse
     */
    public function deleteCouple()
    {
        $rules = [
            'days' => 'required|array'
        ];

        if (!$this->validate($rules)) {
            return $this->response(['error' => 'Некорректный день'], self::HTTP_BAD_REQUEST);
        }

        $days = request()->get('days');

        // Проверяем наличие дня в бд
        foreach ($days as $day) {
            if (!$this->schedule->find($day)) {
                return $this->response(['error' => 'День с id = ' . $day . ' не найден'], self::HTTP_NOT_FOUND);
            }
        }

        // Удаляем по id
        $this->schedule->destroy($days);
        return $this->response(['message' => 'Запись успешно удалена']);
    }


    /**
     * Обновление пары
     *
     * @param $id
     * @return JsonResponse
     */
    public function updateCouple($id)
    {
        request()->merge(['id' => $id]);

        // Валидируем данные
        if (!$this->validate(self::CREATE_OR_UPDATE_RULES)) {
            return $this->response(['error' => 'Некорректные данные'], self::HTTP_BAD_REQUEST);
        }

        $item = $this->schedule->find($id);

        // Проверяем наличие записи
        if (!$item) {
            return $this->response(['error' => 'Запись не найдена'], self::HTTP_NOT_FOUND);
        }

        // Обновляем
        $item->update(request()->all());

        return $this->response(['message' => 'Запись обновлена!']);
    }


    /**
     * Создаем пару на определенный день
     *
     * @return JsonResponse
     */
    public function createCouple()
    {
        // Не проверяем id при создании
        $rules = self::CREATE_OR_UPDATE_RULES;
        unset($rules['id']);

        if (!$this->validate($rules)) {
            return $this->response(['error' => 'Неверные данные'], self::HTTP_BAD_REQUEST);
        }

        // Создаем и отдаем id созданной записи
        $item = $this->schedule->create(request()->all());
        return $this->response(['message' => 'Новая пара успешно создана', 'id' => $item['id']], self::HTTP_CREATED);
    }


    /**
     * Отдаем время на сервере
     *
     * @return int
     */
    public function serverTime()
    {
        return $this->response(['time' => time()]);
    }
}
