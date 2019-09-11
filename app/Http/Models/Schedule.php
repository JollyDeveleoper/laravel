<?php

namespace App\Http\Models;

use App\Library\Utils\Utils;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    /**
     * Если получаем ..schedule/today или ..schedule/tomorrow,
     * то выбираем из массива конкертный день и отдаем его во view,
     * то есть убираем все остальные дни кроме выбранного
     *
     * @param string $day
     * @return array
     */
    public static function getList($day) {
        $data = self::all();
        if ($day !== 'all') {
            $data = $data->where('day', self::getDay($day));
        }
        return $data;
    }

    /**
     * Получаем все пары на определенный день
     *
     * @param int $day
     * @return Schedule[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function get(int $day) {
        return self::where('day', $day)->get();
    }

    private static function getDay($day) {
        $day_on_weekly = array('today' => getdate(), 'tomorrow' => getdate(strtotime('tomorrow')));
        return $day_on_weekly[$day]['wday'];
    }
}
