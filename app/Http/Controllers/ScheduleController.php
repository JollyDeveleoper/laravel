<?php

namespace App\Http\Controllers;

use App\Http\Models\Schedule;
use App\Library\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    public function index($day = 'all')
    {
        $schedule = new Schedule();
        return view($this->getView(), array(
            'data' => $schedule->getList($day)
        ));
    }

    private static function getView()
    {
        return Utils::isMobile(\request()) ? 'schedule/mobile/schedule' : 'schedule/schedule';
    }

    public function edit()
    {
        $data = Utils::getData();
        return view('schedule/mobile/schedule_edit', [
            'data' => $data
        ]);
    }

    public function save(Request $request)
    {
        $data = str_replace('\/', '|', $request->get('json', ''));
        Storage::disk('schedule')->put('config.json', $data);
        return redirect(route('schedule'));
    }
}
