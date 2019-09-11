<?php

namespace App\Http\Models;

use App\Library\Utils\Utils;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{

    /**
     * Если получаем ..schedule/today или ..schedule/tomorrow,
     * то выбираем из массива конкертный день и отдаем его во view,
     * то есть убираем все остальные дни кроме выбранного
     *
     * @param string $day
     * @return array
     */
    public function getList($day) {
        // Новый и исходный массивы для формирования массива для выдачи
        $data = Utils::getData()['schedule'];
        $new_data = [];

        if ($day !== 'all') {
            $new_day = Utils::getLoadedDay($day); // название нового дня
            $new_data[$new_day] = $data[$new_day]; // в новый массив вставляем значение из старого

            unset($data);
            return $new_data;
        }

        return $data;
    }
}
