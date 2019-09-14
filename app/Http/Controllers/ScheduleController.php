<?php

namespace App\Http\Controllers;

use App\Http\Models\Schedule;
use App\Library\Utils\Utils;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Cast\Object_;
use PhpParser\Node\Scalar\String_;

class ScheduleController extends Controller
{

    public function index($day = 'all')
    {
        $list = Schedule::getList($day);
        return view($this->getView(), [
            'data' => $list
        ]);
    }

    private static function getView()
    {
        return Utils::isMobile(\request()) ? 'schedule/mobile/schedule' : 'schedule/schedule';
    }

    public function edit(Request $request)
    {
        $data = $request->all();
        $item = Schedule::find($data['id']);

        $item->name = $data['name'];
        $item->teacher = $data['teacher'];
        $item->start_time = $data['start_time'];
        $item->end_time = $data['end_time'];
        $item->cabinet = $data['cabinet'];

        $item->save();
        return back();
    }

    public function add(Request $request)
    {
        $data = $request->all();

        $item = new Schedule();
        $item->day = $data['day'];
        $item->name = $data['name'];
        $item->teacher = $data['teacher'];
        $item->start_time = $data['start_time'];
        $item->end_time = $data['end_time'];
        $item->cabinet = $data['cabinet'];

        $item->save();
        return back();

    }

    public function delete(Request $request) {
        $deleteID = $request->all()['deleteID'];
        Schedule::destroy($deleteID);
        return back();
    }
}
