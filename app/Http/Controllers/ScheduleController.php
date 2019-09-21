<?php

namespace App\Http\Controllers;

use App\Http\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    private $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function index($day = 'all')
    {
        $list = Schedule::getList($day); // исходные данные

        return view($this->getView(), [
            'data' => $list
        ]);
    }

    private static function getView()
    {
        $isMobile = strpos(strtolower(\request()->server('HTTP_USER_AGENT')), 'mobile');
        return $isMobile ? 'schedule/mobile/schedule' : 'schedule/schedule';
    }

    public function edit(Request $request)
    {
        $data = $request->all();
        $item = $this->schedule->find($data['id']);

        $item->name = $data['name'];
        $item->teacher = $data['teacher'];
        $item->start_time = $data['start_time'];
        $item->end_time = $data['end_time'];
        $item->cabinet = $data['cabinet'];

        $item->save();
        session()->put('success', __('app.success_edit'));
        return back();
    }

    public function add(Request $request)
    {
        $this->schedule->create($request->all());

        session()->put('success', __('app.success_add'));
        return back();

    }

    public function delete(Request $request)
    {
        $deleteID = \request('deleteID');
        $this->schedule->destroy($deleteID);
        session()->put('success', __('app.success_delete'));
        return back();
    }
}
