@extends('layouts.app')

@section('content')
    <div class="container">
        <span class="title">Расписание</span>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th></th>
                <th scope="col">Время</th>
                <th scope="col">Предмет</th>
                <th scope="col">Препод</th>
                <th scope="col">Ауд</th>
            </tr>

            @foreach($data as $item => $value)

                @if(empty($value['name']))
                    @break
                @endif
                @php
                    $prevElement = isset($data[$item - 1]) ? $data[$item - 1] : false;
                    $isState = !$prevElement || $value['day'] !== $prevElement['day'];
                    $row = array_count_values($data[$item])[$item + 1];
                @endphp
                {{ $row }}

                @if($isState)
                <tr>
                    <td rowspan="{{$row}}" class="font-weight-bold day">
                        <span>

                            {{__('app.days.'.$value['day'])}}
                        </span>
                    </td>
                </tr>
                @endif

                <tr>


                    <td>{{ $value['start_time'] . ' - ' . $value['end_time'] }}</td>
                    <td>{{ $value['name'] }}</td>
                    <td>{{ $value['teacher'] }}</td>
                    <td><span>{{ $value['cabinet'] }}</span></td>
                </tr>
{{--                @break--}}

            @endforeach
            </thead>
        </table>
    </div>
@endsection
