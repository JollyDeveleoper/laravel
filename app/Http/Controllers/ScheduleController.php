<?php

namespace App\Http\Controllers;

use App\Http\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    private $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function index($day = 'all')
    {
        $list = $this->schedule->getList($day); // исходные данные
        $current_day = date('w');
        $isAuth = Auth::check();
        $count_day = count($list) + 1;

        return view($this->getView(), [
            'data' => $list,
            'today' => $current_day,
            'isAuth' => $isAuth,
            'count_day' => $count_day
        ]);
    }

    private static function getView()
    {
        $isMobile = strpos(strtolower(\request()->server('HTTP_USER_AGENT')), 'mobile');
        return $isMobile ? 'schedule/mobile/schedule' : 'schedule/schedule';
    }

    public function update()
    {
        $id = \request('id', 0);
        $item = $this->schedule->find($id);

        // Обновляем
        $item->update(request()->all());

        session()->put('success', __('app.success_edit'));
        return back();
    }

    public function create()
    {
        $this->schedule->create(\request()->all());

        session()->put('success', __('app.success_add'));
        return back();

    }

    public function delete()
    {
        $deleteID = \request('deleteID');
        $this->schedule->destroy($deleteID);
        session()->put('success', __('app.success_delete'));
        return back();
    }
}
