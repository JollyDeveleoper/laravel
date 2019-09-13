<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use App\Http\Models\Schedule;
use App\Library\VK\VK_API;
use Illuminate\Http\Request;

class BotController extends Controller
{
    private const SUNDAY = 7;
    private const NEXT_COUPLE = 10;

    public function __construct()
    {
        date_default_timezone_set('Europe/Samara');
    }

    public function index(Request $request)
    {
        $data = $request->post();

        if (!isset($data['type'])) {
            return response('', 403);
        }

        $object = $data['object'];
        $payload = isset($object['payload']) ? (int)$object['payload'] : false; // Юзер нажал кнопку на клавиатуре

        switch ($data['type']) {
            case config('api.VK_EVENT_CONFIRMATION'):
                return response('123');

            case config('api.VK_EVENT_WALL_POST_NEW'):
                $post_id = $object['id']; // id нового поста в группе

                VK_API::repost($post_id);
                break;
            case config('api.VK_EVENT_MESSAGE_NEW'):
                $chat_id = $object['peer_id']; // id чата, в котором произошло событие
                $text = $object['text']; // Юзер нажал кнопку на клавиатуре

                $isUpdate = self::isUpdateKeyboard($text);
                if ($chat_id === config('api.VK_CHAT_ID') || !$payload && !$isUpdate) {
                    break;
                }

                // Обновляем клавиатуру
                if ($isUpdate) {
                    VK_API::updateKeyboard();
                    break;
                }

                VK_API::sendMessage($this->findScheduleOnDay($payload));
        }
        return response('ok');
    }

    /**
     * Поиск расписания на конкретный день
     *
     * @param $day
     * @return int|string|null
     */
    private function findScheduleOnDay(int $day): string
    {
        // Следующая пара
        if ($day === self::NEXT_COUPLE) {
            return $this->getNextCouple();
        }

        // На сегодня или завтра
        return $this->getSchedule($day < self::SUNDAY ? $day : (int)$this->parseDays($day));
    }

    /**
     * Обновляем ли клавиатуру по запросу
     * Возвращает boolean
     *
     * @param $text
     * @return bool
     */
    private static function isUpdateKeyboard(string $text): bool
    {
        return $text === 'update';
    }

    /**
     * Получение дня в неделе относительно запроса
     *
     * @param int $payload
     * @return int
     */
    private static function parseDays(int $payload): int
    {
        $day = date('w', strtotime($payload === 8 ? 'tomorrow' : 'today'));
        return $day;
    }

    /**
     * Достаем расписание пар и отправляем в чат
     * Возвращает все пары текстом на определенный день
     *
     * @param $day
     * @return string
     */
    function getSchedule(int $day): string
    {
        $data = Schedule::schedule($day);

        if (!$data) return 'Пары не найдены';

        $text = null;
        $count_couple = count($data);
        for ($i = 0; $i < $count_couple; ++$i) {
            $item = $data[$i];
            $time = $item['start_time'] . ' - ' . $item['end_time'] . "\n";
            $name = $item['name'] . ' (' . $item['cabinet'] . 'каб.)';
            $text .= "$time $name \n\n";
        }
        return $text;
    }

    /**
     * Получаем следующую пару относительно расписания
     * Возвращает следущую пару текстом на сегодня
     *
     * @return string
     */
    private function getNextCouple() : string
    {
        $data = Schedule::nextCouple();

        if (!$data) return 'Следующая пара не найдена';
        $data = $data[0];

        $time = $data['start_time'] . ' - ' . $data['end_time'] . "\n";
        $name = $data['name'] . ' (' . $data['cabinet'] . 'каб.)';
        $text = "$time $name";

        return $text;
    }
}
