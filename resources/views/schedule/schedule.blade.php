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
        @php
        $i = 0;
        @endphp
        @foreach($data as $item)
            @if(!$item)
                @break
            @endif
            <tr>
                <td rowspan="{{ count($item) + 1 }}" class="font-weight-bold day">
                    <span>{{ $days[$i] }}</span>
                </td>
            </tr>
            @foreach($item as $value)
                <tr>

                    <td>{{ $value['start_time'] . ' - ' . $value['end_time'] }}</td>
                    <td>{{ $value['name'] }}</td>
                    <td>{{ $value['teacher'] }}</td>
                    <td><span>{{ $value['cabinet'] }}</span></td>
                </tr>
            @endforeach
            @php
                $i++;
            @endphp
            @endforeach
        </thead>
    </table>
</div>
@endsection
