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
    private $chat_id = 0;

    private $isEditMode = false; // находимся в режие редактирования
    private $isAdd = false; // в режиме добавления пары
    private $isEdit = false; // в режиме редактирования пары
    private $daySelected = 0; // Какой день редактируем
    private $coupleInfo = []; // Массив с данными о паре

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
                $this->chat_id = $chat_id;
                $text = $object['text'];

                $isUpdate = $text === 'update';
                // Не реагируем на обычный текст
                if (!$payload && !$isUpdate) {
                    break;
                }

                if ($text === 'edit' || $this->isEditMode) {
                    // Если мы уже выбрали день
                    if ($this->daySelected !== 0) {
                        $text = $this->setCoupleInfo();
                        VK_API::sendMessage($text, $chat_id);
                    }
                    $this->isEditMode = true;
                    $this->parseEditCommand($payload);
                    break;
                }

                // Обновляем клавиатуру
                if ($isUpdate) {
                    VK_API::sendMessage('Клавиатура обновлена', $chat_id, VK_API::getKeyboard());
                    break;
                }

                VK_API::sendMessage($this->findScheduleOnDay($payload), $chat_id);
//                echo $this->findScheduleOnDay($payload);
        }
        return response('ok');
    }

    /**
     * Парсим команды редактирования и отдаем соответствующую клавиатуру
     * 0-10 основные команды
     * 20-30 - команды редактирования
     *
     * @param $action
     */
    private function parseEditCommand(int $action) {
        $editMode = new BotEditController();
        $keyboardPath = 'Library/VK/Keyboard/';

        if ($this->daySelected > 0) {
            if ($this->isAdd) {
                VK_API::sendMessage($this->setCoupleInfo(), $this->chat_id);
                return;
            }
        }

        $text = 'FUCK';

        // Выбор дня недели
        if ($action <= 7) {
            $this->daySelected = $action;
            $keyboardPath .= 'Edit/select_day';
            VK_API::sendMessage('Что дальше?', $this->chat_id, file_get_contents(app_path($keyboardPath . '.json')));
            return;
        }

        switch ($action) {
            case 20: // добавление пары
                $text = 'Выберите день';
                $this->isAdd = true;
                $keyboardPath .= 'Edit/select_day';
                break;
            case 21: // Редактирование пары
                $text = 'Выберите день';
                $this->isEdit = true;
                $keyboardPath .= 'Edit/select_day';
                break;
            case 22: // Сохраняем
                $this->resetEditMode();
                if ($this->isAdd) {
                    $editMode->add($this->daySelected, $this->coupleInfo);
                }

                $editMode->edit($this->daySelected, $this->coupleInfo);
                $keyboardPath .= 'keyboard';
                $text = 'Понял. Принял. Сохранил';
                break;

            case 100: // Отменили редактирование. Возвращаем на место стандартную клаву
                $this->resetEditMode();
                $keyboardPath .= 'keyboard';
                $text = 'Ну ок, продолжаю следить за расписанием';
                break;
        }

        VK_API::sendMessage($text, $this->chat_id, file_get_contents(app_path($keyboardPath . '.json')));
    }

    private function resetEditMode() {
        $this->isEdit = false;
        $this->isAdd = false;
        $this->isEditMode = false;
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
        $data = Schedule::nextCouple();

        if (!$data) return 'Следующая пара не найдена';

        return $this->getText($data);
    }

    /**
     * Формируем текст расписания с переданного массива
     *
     * @param array $data
     * @return string
     */
    private function getText(array $data): string
    {
        $time = $data['start_time'] . ' - ' . $data['end_time'] . "\n";
        $name = $data['name'] . ' (' . $data['cabinet'] . 'каб.)';
        $text = "$time $name \n\n";
        return $text;
    }

    // TODO доделать
    private function setCoupleInfo()
    {
        $info = $this->coupleInfo;
        if (isset($info['name'])) {
            return 'Введите номер кабинета';
        }

    }
}
