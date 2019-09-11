<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use App\Http\Models\Schedule;
use App\Library\VK\VK_API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BotController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Europe/Samara');
    }

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
        $payload = (int)$request->input('object.payload', ''); // Юзер нажал кнопку на клавиатуре
        $text = $request->input('object.text', ''); // Юзер нажал кнопку на клавиатуре

        switch ($data['type']) {
            case config('api.VK_EVENT_CONFIRMATION'):
                return response('123');

            case config('api.VK_EVENT_WALL_POST_NEW'):
                VK_API::repost($post_id);
                break;
            case config('api.VK_EVENT_MESSAGE_NEW'):

                if ($chat_id === config('api.VK_CHAT_ID')) {
                    break;
                }

                // Обновляем клавиатуру
                if ($text === 'update') {
                    VK_API::sendMessageWithKeyboard('Клавиатура обновлена!');
                    break;
                }

                if (!$payload) break;

                // Передаем нажатую клавишу
                if ($payload < 7) {
                    VK_API::sendMessage($this->getSchedule($payload));
                    break;
                }

                // Следующая пара
                if ($payload === 10) {
                    VK_API::sendMessage($this->getNextCouple());
                    break;
                }


                $day = (int)$this->parseDays($payload); // выбранный день в виде строки
                VK_API::sendMessage($this->getSchedule($day));
                break;
        }
        unset($data);
        return response('ok');
    }

    private static function parseDays($payload)
    {
        if ($payload === 9) {
            return date('w');
        }

        if ($payload === 7) {
            return date('w', strtotime('tomorrow'));
        }

        if ($payload === 8) {
            return date('w', strtotime('yesterday'));
        }
    }

    /**
     * Достаем расписание пар и отправляем в чат
     * @param $day
     * @return string
     */
    function getSchedule($day)
    {
        $text = null;
        foreach (Schedule::where('day', $day)->cursor() as $item) {
            if (empty($item->name)) {
                return 'Нет пар на этот день';
            }
            $time = $item->start_time . ' - ' . $item->end_time . "\n";
            $name = $item->name . ' (' . $item->cabinet . 'каб.)';
            $text .= "$time $name \n\n";
        }
        return $text;
    }

    /**
     * Получаем следующую пару относительно расписания
     *
     * @return string
     */
    private function getNextCouple()
    {
        $data = Schedule::get(date('w'));

        if (empty($data[0]->name)) {
            return 'На сегодня пар нет';
        }

        $text = 'Следующая пара не найдена. Видимо, на сегодня все!';
        $current_time = date('Hi');

        foreach ($data as $item) {
            $time = strtr($item['start_time'], array(':' => ''));
            if ($time > $current_time) {
                $time = $item['start_time'] . ' - ' . $item['end_time'] . "\n";
                $name = $item['name'] . ' (' . $item['cabinet'] . 'каб.)';
                $text = "$time $name";
                unset($data);
                // Важно поставить break, т.к дальше могут быть еше пары и данные перезапишутся
                break;
            }
        }
        return $text;
    }
}
