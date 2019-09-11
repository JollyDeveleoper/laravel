<?php

namespace App\Library\Utils;

use App\Http\Controllers\Bot\BotController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class Utils {
    /**
     * Возвращает название дня в зависимости от входного параметра
     *
     * Вернет завтрашний день при tomorrow
     * Вернет сегодняшний день при today
     *
     * @param $day
     * @return string
     */
    public static function getLoadedDay($day) {

        $day_on_weekly = array('today' => getdate()['wday'], 'tomorrow' => getdate(strtotime('tomorrow'))['wday']);
        // Тут какой-то пиздец. Закрываем доступ
        if (!isset($day_on_weekly[$day])) {
            return strtolower($day_on_weekly['today']);
        }
        $result = $day_on_weekly[$day];
        return $result;
    }

    /**
     * Получение расписания в json
     *
     * @return mixed
     */
    public static function getData() {
        $content = file_get_contents(storage_path('schedule/config.json'));
        $data = json_decode($content, true);
        return $data;
    }

    /**
     * Возвращает true, если страница была загружена с моб.устройства
     *
     * @param Request $request
     * @return bool|int
     */
    static function isMobile(Request $request) {
        return strpos(strtolower($request->server('HTTP_USER_AGENT')), 'mobile');
    }
}
