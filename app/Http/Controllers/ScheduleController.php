<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Bot\BotController;
use App\Http\Request\PostRequest;
use App\Library\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Если получаем ..schedule/today или ..schedule/tomorrow,
     * то выбираем из массива конкертный день и отдаем его во view,
     * то есть убираем все остальные дни кроме выбранного
     *
     * @param PostRequest $request
     * @param string $day
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function index($day = 'all') {
        if ($day !== 'all' && $day !== 'today' && $day !== 'tomorrow') {
            return response('', 403);
        }
        // Дни недели для списка
        $days_title = array('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');

        // Новый и исходный массивы для формирования массива для выдачи
        $data = Utils::getData()['schedule'];
        $new_data = [];

        // Выбираем нужный день
        $title = null;

        if ($day !== 'all') {
            $new_day = Utils::getLoadedDay($day); // название нового дня
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
        return Utils::isMobile(\request()) ? 'schedule/mobile/schedule' : 'schedule/schedule';
    }

    public function edit() {
        $data = Utils::getData();
        return view('schedule/mobile/schedule_edit', [
            'data' =>$data
        ]);
    }

    public function save(PostRequest $request) {
        $data = $request->get('json', '');
        if (!$data) {
            return;
        }
        Storage::disk('schedule')->put('config.json', str_replace('\/', '|', $data));
        return redirect(route('schedule'));
    }
}
