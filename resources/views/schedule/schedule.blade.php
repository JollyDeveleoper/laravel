@extends('layouts.app')

@section('content')
    <div class="container">
        <span class="title">Расписание</span>

        @foreach($data as $item => $values)
            @php
                $isToday = date('w') === $values[0]['day'];
            @endphp
            <h2 class="mb-2 mt-2">
                {{__('app.days.'.$values[0]['day'])}}
                @if($isToday)
                    <small class="badge badge-pill badge-primary">Сегодня</small>
                @endif
            </h2>
            @foreach($values as $val)

                <div class="mr-2  mt-2 mb-3 rounded-lg shadow p-3   d-inline-block " style="min-width: 300px">
                    <div class="card-title font-weight-bold">{{ $val['start_time'] . ' - ' . $val['end_time'] }}</div>
                    <small>{{ $val['teacher'] }}</small>
                    <p class="card-text">{{ $val['name'] }}</p>
                    <span class="font-weight-bold">Кабинет:</span> {{ $val['cabinet'] }}

                </div>
            @endforeach
        @endforeach

    </div>
@endsection
