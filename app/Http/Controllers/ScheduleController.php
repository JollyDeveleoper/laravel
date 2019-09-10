<?php

namespace App\Http\Controllers;

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

    public function index() {
        $days = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");

        return view($this->isMobile(\request()) ? 'schedule/schedule_mobile' : 'schedule/schedule', array(
            'data' => $this->getData()['schedule'],
            'days' => $days,
            'day' => date('l')
        ));
    }
}
