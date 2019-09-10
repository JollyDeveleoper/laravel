<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Bot\BotController;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ScheduleController();
        }
        return self::$instance;
    }

    function isMobile(Request $request) {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $request->server('HTTP_USER_AGENT'));
    }

    public function getData() {
        $content = file_get_contents(storage_path('schedule/config.json'));
        $data = json_decode($content, true);
        return $data;
    }

    public function index($day = 'all') {
        $days_title = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");
        $arr = [];
        $current_title = '';
        if ($day !== 'all') {
            // Либо сегодня, либо завтра. Выбираем день
            $new_day = self::getLoadedDay($day);
            $arr['schedule'] =  $this->getData()['schedule'][$new_day];

            // Для заголовока
            $current_title = $day === 'today' ? 'Сегодня' : 'Завтра';
        }
        // Для сеглдня или завтра формируем расписание в отдельный массив
        $current_day = $day === 'all' ? $this->getData()['schedule'] : $arr;
        return view($this->isMobile(\request()) ? 'schedule/schedule_mobile' : 'schedule/schedule', array(
            'data' => $current_day,
            'days_list' => $days_title,
            'current_title' => $current_title
        ));
    }

    private static function getLoadedDay($day) {
        $day_of_weekly = array('today' => date('l'), 'tomorrow' => BotController::getCurrentDateWithOffset(strtotime('tomorrow')));
        $day = strtolower($day_of_weekly[$day]);
        return $day;
    }
}
