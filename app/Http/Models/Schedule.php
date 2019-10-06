<?php

namespace App\Http\Models;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';
    protected $fillable  = ['name', 'teacher', 'cabinet', 'start_time', 'end_time', 'day'];

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
        // Список пар из базы
        $list = $data->sortBy('day')->toArray();

        // Сортируем по дня недели и времени началу пар
        $sort = new Sort();
        $sortedListByDay = $sort->getSortedListByDay($list);
        $sortedListByTime = $sort->getSortedListByStartTime($sortedListByDay);

        unset($sortedListByDay);
        unset($list);
        unset($data);

        return $sortedListByTime;
    }

    /**
     * Получение дня недели
     *
     * @param string $day
     * @return mixed
     */
    private static function getDay(string $day)
    {
        $day_on_weekly = array('today' => getdate(), 'tomorrow' => getdate(strtotime('tomorrow')));
        return $day_on_weekly[$day]['wday'];
    }

    /**
     * Получение всех пар на определенный день
     *
     * @param $query
     * @param int $day
     * @return array
     */
    public function scopeSchedule($query, int $day): array
    {
        $data = $this->getSchedules($day)->sortBy('start_time')->toArray();
        return $data;
    }

    /**
     * Получение следующей пары на текущий день
     *
     * @return array
     */
    public function scopeNextCouple(): array
    {
        $day = date('w');
        $current_time = date('H:i');
        $result = $this->getSchedules($day)->sortBy('start_time')->firstWhere('start_time', '>', $current_time);
        $arr = [];
        if ($result !== null) {
            $arr = $result->toArray();
        }
        return $arr;
    }

    /**
     * Получаем все пары на определенный день
     *
     * @param int $day
     * @return Schedule[]|Collection
     */
    private function getSchedules(int $day)
    {
        return self::where('day', $day)->get();
    }
}

/**
 * Сортировка расписания по дате начала и дням
 *
 * Class Sort
 * @package App\Http\Models
 */
class Sort {
    /**
     * Возвращает отсортированный массив по дням недели
     *
     * @param array $list
     * @return array
     */
    public function getSortedListByDay(array $list): array
    {
        $new_list = [];
        // Создаем списки по дням недель
        foreach ($list as $item => $value) {
            if ($list[$item]['day'] === next($list[$item])) {
                $new_list[$value['day']][] = $list[$item];
            }
        }
        return $new_list;
    }

    /**
     * Возвращает отсортированный ассцоиативный массив по времени начала пары
     *
     * @param array $list
     * @return array
     */
    public  function getSortedListByStartTime(array $list): array
    {
        $new_1 = [];
        foreach ($list as $lb) {
            usort($lb, function ($a, $b) use ($list) {
                return new DateTime($a['start_time']) <=> new DateTime($b['start_time']);
            });
            $new_1[] = $lb;
            unset($lb);
        }
        return $new_1;
    }
}
