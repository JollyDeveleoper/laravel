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
                    $modalId = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 7); // Уникальный id для модалки
                @endphp

                @if($isState)
                    <h3 id="{{ __('app.days.'.$values['day']) }}">
                        @if($isToday)
                            <small class="badge badge-pill badge-primary">{{ __('app.days.'.$values['day']) }}</small>
                        @else
                            {{  __('app.days.'.$values['day']) }}
                        @endif
                    </h3>
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#addModal">
                        Добавить
                    </button><br>
                    @include('schedule.template.modal_add')

                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $values['start_time'] . ' - ' . $values['end_time'] }}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">{{ $values['teacher'] }}</h6>
                        <p class="card-text">{{ $values['name'] . " (" . $values['cabinet'] . ")"}} </p>

                        <!-- Modal -->
                        @include('schedule.template.modal')

                        <form action="{{ route('delete') }}" method="post">

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#{{$modalId}}">
                            Редактировать
                        </button>
                            @csrf
                            <button type="submit" class="btn btn-danger" value="{{ $values['id'] }}"
                                    name="deleteID">
                                Удалить
                            </button>
                        </form>
                    </div>
                </div>
                <br>



            @endforeach
        @else
            <div class="text-center font-weight-bold">Нет пар на этот день <br>¯\_(ツ)_/¯</div>
        @endif
    </div>
@endsection
