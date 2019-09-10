<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Collection;
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
    public static function getList($day)
    {
        $data = self::all();
        if ($day !== 'all') {
            $data = $data->where('day', self::getDay($day));
        }
        return $data->sortBy('day')->toArray();
    }

    /**
     * Получаем все пары на определенный день
     *
     * @param int $day
     * @return Schedule[]|Collection
     */
    public static function get(int $day)
    {
        return self::where('day', $day)->get();
    }

    private static function getDay(string $day)
    {
        $day_on_weekly = array('today' => getdate(), 'tomorrow' => getdate(strtotime('tomorrow')));
        return $day_on_weekly[$day]['wday'];
    }

    public function scopeSchedule($query, int $day): array
    {
        $data = $this->getSchedules($day)->sortBy('start_time')->toArray();
        return $data;
    }

    public function scopeNextCouple(): array
    {
        $day = date('w');
        $current_time = date('H:i');
        $result = $this->getSchedules($day)->firstWhere('start_time', '>', $current_time);
        $arr = [];
        if ($result !== null) {
            $arr = $result->toArray();
        }
        return $arr;
    }

    private function getSchedules(int $day)
    {
        return self::all()->where('day', $day);
    }
}
