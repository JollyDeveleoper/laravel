@extends('layouts.app')

@section('content')
    <div class="container">

        @php
            $i = 0;
        @endphp
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Расписание
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ url('schedule/') }}">Неделя</a>
                <a class="dropdown-item" href="{{ url('schedule/today') }}">Сегодня</a>
                <a class="dropdown-item" href="{{ url('schedule/tomorrow') }}">Завтра</a>
                <!--Дни недели с якорями-->
                @foreach($days_list as $item)
                    <a class="dropdown-item" href="{{ route('schedule') . '#' . $item }}">{{$item}}</a>
                @endforeach
            </div>
        </div>
        <br>
        @foreach($data as $item) <!--Все дни-->
        @if(!$item) @break @endif

        <!--Заголовок текущего дня-->
        @if($current_title)
            <h3 id="{{$days_list[$i]}}">{{ $current_title}}</h3>
        @else
            <h3 id="{{$days_list[$i]}}">{{ $days_list[$i]}}</h3>
        @endif

        @foreach($item as $value) <!--Конкретный день-->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $value['start_time'] . ' - ' . $value['end_time'] }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $value['teacher'] }}</h6>
                <p class="card-text">{{ $value['name'] . " (" . $value['cabinet'] . ")"}} </p>
            </div>
        </div>
        <br>
        @endforeach

        @php
            $i++;
        @endphp
        @endforeach

    </div>
@endsection
