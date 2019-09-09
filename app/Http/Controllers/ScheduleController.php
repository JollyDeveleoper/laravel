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

    public function getData() {
        $content = file_get_contents(storage_path('schedule/config.json'));
        $data = json_decode($content, true);
        return $data;
    }

    public function index() {
        $days = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");
        return view('schedule/schedule', array(
            'data' => $this->getData()['schedule'],
            'days' => $days,
        ));
    }
}
