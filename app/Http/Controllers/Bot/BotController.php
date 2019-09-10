<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use App\Library\VK\VK_API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BotController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'type' => 'bail|required',
        ]);

        if ($validate->fails()) {
            return response('', 403);
        }

        $post_id = $request->input('object.id', ''); // id нового поста в группе
        $chat_id = $request->input('object.peer_id', ''); // id чата, в котором произошло событие
        $payload = (int) $request->input('object.payload', ''); // Юзер нажал кнопку на клавиатуре
        $text = $request->input('object.text', ''); // Юзер нажал кнопку на клавиатуре

        switch ($data['type']) {
            case env('VK_EVENT_CONFIRMATION'):
                return response('123');

            case env('VK_EVENT_WALL_POST_NEW'):
                VK_API::repost($post_id);
                break;
            case env('VK_EVENT_MESSAGE_NEW'):

                if ($chat_id === env('VK_CHAT_ID')) {
                    break;
                }

                // Обновляем клавиатуру
                if ($text === 'update') {
                    VK_API::sendMessageWithKeyboard('Клавиатура обновлена!');
                    break;
                }

                if (!$payload) break;

                VK_API::sendMessage($this->parseDays($payload));
                break;
        }
        return response('ok');
    }

    private function parseDays($payload)
    {
        // Следующая пара
        if ($payload === 10) {
            return $this->getNextCouple();
        }
        $day = array(
            $this->getCurrentDateWithOffset(strtotime('yesterday')),
            $this->getCurrentDateWithOffset(strtotime('tomorrow')),
            $this->getCurrentDateWithOffset(null),
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
        );
        return $this->getSchedule(strtolower($day[$payload - 1]));
    }

    /**
     * Достаем расписание пар и отправляем в чат
     * @param $day
     * @return string
     */
    function getSchedule($day)
    {
        $data = ScheduleController::getInstance()->getData();
        $current_day = $data['schedule'][$day];
        if (!$current_day) {
            return ('Пар нет');
        }
        $text = '';
        foreach ($current_day as $item) {
            $time = $item['start_time'] . ' - ' . $item['end_time'] . "\n";
            $name = $item['name'] . ' (' . $item['cabinet'] . 'каб.)';
            $text .= "$time $name \n\n";
        }
        return $text;
    }

    /**
     * Получаем следующую пару относительно расписания
     * Смещение на +1 от МСК хардкорим в коде
     * @return string
     */
    private function getNextCouple()
    {
        $current_day = mb_strtolower($this->getCurrentDateWithOffset(null));
        $current_time = $this->getCurrentDateWithOffset(null, 'Hi');

        $data = ScheduleController::getInstance()->getData();

        // Получаем ищем по текущему дню
        $day = $data['schedule'][$current_day];

        if (!$day) {
            return 'На сегодня пар нет';
        }

        $text = 'Следующая пара не найдена. Видимо, на сегодня все!';
        foreach ($day as $item) {
            $time = strtr($item['start_time'], array(':' => ''));
            if ($time > $current_time) {
                $time = $item['start_time'] . ' - ' . $item['end_time'] . "\n";
                $name = $item['name'] . ' (' . $item['cabinet'] . 'каб.)';
                $text = "$time $name";
                // Важно поставить break, т.к дальше могут быть еше пары и данные перезапишутся
                break;
            }
        }
        return $text;
    }

    /**
     * Получаем текущее время с дефолтным оффсетом на 1 час от МСК
     *
     * Смещение по оффсету идет относительно времени по мск
     * @param $format
     * @param int $offset
     * @return false|string
     */
    static function getCurrentDateWithOffset($offset = null, $format = 'l')
    {
        date_default_timezone_set('Europe/Samara');
        return date($format, $offset === null ? time() : $offset);
    }
}
