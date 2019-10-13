<?php

namespace App\Http\Controllers;

use App\Http\Models\Schedule;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use function request;

class ScheduleController extends Controller
{
    private $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Выдаем все пары на неделю
     *
     * @param string $day
     * @return Factory|View
     */
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

    /**
     * Чекаем мобильную версию и выдаем макет
     *
     * @return string
     */
    private static function getView()
    {
        $isMobile = strpos(strtolower(request()->server('HTTP_USER_AGENT')), 'mobile');
        return $isMobile ? 'schedule/mobile/schedule' : 'schedule/schedule';
    }

    /**
     * Обновляем пару по id
     *
     * @return RedirectResponse
     */
    public function update()
    {
        $id = request('id', 0);

        $item = $this->schedule->find($id);
        // Обновляем
        $item->update(request()->all());

        session()->put('success', __('app.success_edit'));
        return back();
    }

    /**
     * Создаем новую пару
     *
     * @return RedirectResponse
     */
    public function create()
    {
        $this->schedule->create(request()->all());

        session()->put('success', __('app.success_add'));
        return back();
    }

    /**
     * Удаляем определенную пару
     *
     * @return RedirectResponse
     */
    public function delete()
    {
        $deleteID = request('deleteID');
        $this->schedule->destroy($deleteID);

        session()->put('success', __('app.success_delete'));
        return back();
    }
}
