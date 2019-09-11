@extends('layouts.app')

@section('content')
    <div class="container">
        <select class="form-control" onchange="this.value !== '' ? location = this.value : ''; this.select">
            <option value="">Выберите</option>
            <option value="{{ url('schedule/') }}">На неделю</option>
            <option value="{{ url('schedule/today') }}">На сегодня</option>
            <option value="{{ url('schedule/tomorrow') }}">На завтра</option>

            @foreach($data as $item => $key)
                <option value="{{ url('schedule') . '#' . __('app.'.$item) }}">{{__('app.'.$item)}}</option>
            @endforeach
        </select>
        <br>
        <form action="{{ route('edit') }}" method="post">
            @csrf
            @if(Auth::user()->status === 1)
                <button type="submit" class="btn btn-success btn-block">
                    Редактировать
                </button>
                <br>
            @endif
        </form>

        <!--Все дни-->
        @foreach($data as $item => $key)
        @if(!$key) @break @endif

        <!--Заголовок текущего дня берем из локали-->
        <h3 id="{{ __('app.'.$item) }}">{{ __('app.'.$item) }}</h3>

        <!--Конкретный день-->
        @foreach($key as $value)

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $value['start_time'] . ' - ' . $value['end_time'] }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $value['teacher'] }}</h6>
                <p class="card-text">{{ $value['name'] . " (" . $value['cabinet'] . ")"}} </p>

            </div>
        </div>
        <br>

        @endforeach
        @endforeach

    </div>
@endsection
