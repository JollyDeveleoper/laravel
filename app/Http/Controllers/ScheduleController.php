<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Bot\BotController;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    static function isMobile(Request $request) {
        return strpos(strtolower($request->server('HTTP_USER_AGENT')), 'mobile');
    }

    public static function getData() {
        $content = file_get_contents(storage_path('schedule/config.json'));
        $data = json_decode($content, true);
        return $data;
    }

    /**
     * Если получаем ..schedule/today или ..schedule/tomorrow,
     * то выбираем из массива конкертный день и отдаем его во view,
     * то есть убираем все остальные дни кроме выбранного
     *
     * @param string $day
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($day = 'all') {
        // Дни недели для списка
        $days_title = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');

        // Новый и исходный массивы для формирования массива для выдачи
        $data = self::getData()['schedule'];
        $new_data = [];

        // Выбираем нужный день
        $title = null;

        if ($day !== 'all') {
            $new_day = self::getLoadedDay($day); // название нового дня
            $new_data[$new_day] = $data[$new_day]; // в новый массив вставляем значение из старого

            // Выбираем нужный заголовок/заголовки
            $title = $day === 'today' ? 'Сегодня' : 'Завтра';
            unset($data);
        }

        return view($this->getView(), array(
            'data' => !$new_data ? $data : $new_data,
            'days_list' => $days_title,
            'current_title' => $title
        ));
    }

    private static function getView() {
        return self::isMobile(\request()) ? 'schedule/schedule_mobile' : 'schedule/schedule';
    }

    /**
     * Возвращает название дня в зависимости от входного параметра
     *
     * Вернет завтрашний день при tomorrow
     * Вернет сегодняшний день при today
     *
     * @param $day
     * @return string
     */
    private static function getLoadedDay($day) {
        $day_on_weekly = array('today' => date('l'), 'tomorrow' => BotController::getCurrentDateWithOffset(strtotime('tomorrow')));
        $day = strtolower($day_on_weekly[$day]);
        return $day;
    }
}
