@extends('layouts.app')

@section('content')
    <div class="container">
        <select class="form-control" onchange="this.value !== '' ? location = this.value : ''; this.select">
            <option value="">Выберите</option>
            <option value="{{ url('schedule/') }}">На неделю</option>
            <option value="{{ url('schedule/today') }}">На сегодня</option>
            <option value="{{ url('schedule/tomorrow') }}">На завтра</option>
            @foreach($data as $item => $value)
                @if(7 > $value['id'])
                    <option
                        value="{{ url('schedule') . '#' . __('app.days.'.$value['id']) }}">{{ __('app.days.'.$value['id']) }}</option>
                @endif
            @endforeach
        </select>
        <br>

        @foreach($data as $item => $values)
            <!--
                Тут какая-то дичь. Нужно для того, чтобы вывести заголовки без повтора.
                НЕ ТРОГАТЬ!!!
            -->
            @php
                $prevElement = isset($data[$item - 1]) ? $data[$item - 1] : false;
                $isState = !$prevElement || $values['day'] !== $prevElement['day'];
            @endphp

            @if($isState)
                <h3 id="{{ __('app.days.'.$values['day']) }}">{{ __('app.days.'.$values['day']) }}</h3>
            @endif
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $values['start_time'] . ' - ' . $values['end_time'] }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $values['teacher'] }}</h6>
                    <p class="card-text">{{ $values['name'] . " (" . $values['cabinet'] . ")"}} </p>

                </div>
            </div>
            <br>

        @endforeach

    </div>
@endsection
