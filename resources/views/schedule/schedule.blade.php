@extends('layouts.app')

@section('content')
    <div class="container">
        <span class="title">Расписание</span>

        @foreach($data as $item => $value)

            @if(empty($value['name']))
                @break
            @endif
            @php
                $prevElement = isset($data[$item - 1]) ? $data[$item - 1] : false;
                $isState = !$prevElement || $value['day'] !== $prevElement['day'];
                $row = array_count_values($data[$item])[$item + 1];
                $isToday = date('w') === $value['day'];
            @endphp


            @if($isState)
                <h2 class="mb-2 mt-2">
                    {{__('app.days.'.$value['day'])}}
                    @if($isToday)
                        <small class="badge badge-pill badge-primary">Сегодня</small>
                    @endif
                </h2>
            @endif
                <div class="mr-2  mt-2 mb-3 rounded-lg shadow p-3   d-inline-block " style="min-width: 300px">
                    <div class="card-title font-weight-bold">{{ $value['start_time'] . ' - ' . $value['end_time'] }}</div>
                    <small>{{ $value['teacher'] }}</small>
                    <p class="card-text">{{ $value['name'] }}</p>
                    <span class="font-weight-bold">Кабинет:</span> {{ $value['cabinet'] }}

                </div>
        @endforeach


    </div>
@endsection
