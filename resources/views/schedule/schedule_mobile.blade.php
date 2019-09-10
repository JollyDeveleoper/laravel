@extends('layouts.app')

@section('content')
    <div class="container">

        @php
            $i = 0;
        @endphp
        <select class="form-control" onChange="window.location.href=this.value">
            <option value="{{ url('/') }}">На неделю</option>
            <option value="{{ url('schedule/today') }}">На сегодня</option>
            <option value="{{ url('schedule/tomorrow') }}">На завтра</option>

            @foreach($days_list as $item)
                <option value="{{ route('schedule') . '#' . $item }}">{{$item}}</option>
            @endforeach
        </select>
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
