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

    private $schedule;

    public function __construct(Schedule $schedule)
    {
        date_default_timezone_set('Europe/Samara');
        $this->schedule = $schedule;
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
                $text = $object['text'];

                // Отключаем работу при обычной переписке
                $isUpdate = $text === 'update';
                if (!$payload && !$isUpdate) {
                    break;
                }

                // Обновляем клавиатуру
                if ($isUpdate) {
                    $keyboard = file_get_contents(app_path('Library/VK/keyboard.json'));
                    VK_API::sendMessage(__('app.keyboard_update'), $chat_id, $keyboard);
                    break;
                }

                VK_API::sendMessage($this->findScheduleOnDay($payload), $chat_id);
//                echo $this->findScheduleOnDay($payload);
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
        if ($day === BotController::NEXT_COUPLE) {
            return $this->getNextCouple();
        }

        // На определенный день
        if ($day < BotController::SUNDAY) {
            return $this->getSchedule($day);
        }

        // На сегодня или завтра
        return $this->getSchedule($this->schedule->getCurrentDay($day === 8 ? 'tomorrow' : 'today'));
    }

    /**
     * Возвращает все пары текстом на определенный день
     *
     * @param $day
     * @return string
     */
    function getSchedule(int $day): string
    {

        $data = $this->schedule->schedule($day);

        if (!$data) return __('app.couple_not_found');

        $text = null;
        foreach ($data as $item) {
            $text .= $this->getText($item);
        }

        return $text;
    }

    /**
     * Получаем следующую пару относительно расписания
     * Возвращает следущую пару текстом на сегодня
     *
     * @return string
     */
    private function getNextCouple(): string
    {
        $data = $this->schedule->nextCouple();

        if (!$data) return __('app.next_couple_not_found');

        return $this->getText((array)$data);
    }

    /**
     * Формируем текст расписания с переданного массива
     *
     * @param array $data
     * @return string
     */
    private function getText(array $data): string
    {
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];

        $teacher_emoji = '👨‍🏫 ';
        $couple_emoji = '📋 ';
        $time_emoji = '🕛 ';
        $cabinet_emoji = '🚪 ';

        $teacher = $teacher_emoji . $data['teacher'] . "\n";
        $couple = $couple_emoji . $data['name'] . "\n";
        $time = $time_emoji . $start_time . ' - ' . $end_time . "\n";
        $cabinet = $cabinet_emoji . $data['cabinet'];

        $text = "$teacher $couple $time $cabinet\n\n";
        return $text;
    }
}
