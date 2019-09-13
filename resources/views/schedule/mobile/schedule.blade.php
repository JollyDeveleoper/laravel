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

    @if($data)
        @foreach($data as $item => $values)
            <!--
                Тут какая-то дичь. Нужно для того, чтобы вывести заголовки без повтора.
                НЕ ТРОГАТЬ!!!
            -->
                @php
                    $prevElement = isset($data[$item - 1]) ? $data[$item - 1] : false;
                    $isState = !$prevElement || $values['day'] !== $prevElement['day'];
                    $isToday = date('w') === $values['day'];
                @endphp

                @if($isState)
                    <h3 id="{{ __('app.days.'.$values['day']) }}">
                        @if($isToday)
                            <small class="badge badge-pill badge-primary">{{ __('app.days.'.$values['day']) }}</small>
                        @else
                            {{  __('app.days.'.$values['day']) }}
                        @endif
                    </h3>

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
        @else
        <div class="text-center font-weight-bold">Нет пар на этот день <br>¯\_(ツ)_/¯</div>
        @endif
    </div>
@endsection
