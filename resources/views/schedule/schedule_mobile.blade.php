@extends('layouts.app')

@section('content')
    <div class="container">

        @php
            $i = 0;
        @endphp
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                На неделю
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item">На сегодня</a>
                <a class="dropdown-item">На завтра</a>
            @foreach($days as $item)
                    <a class="dropdown-item" href="#{{$item}}">{{$item}}</a>
                @endforeach
            </div>
        </div>
        <br>
        @foreach($data as $item) <!--Все дни-->

        @if(!$item) @break @endif

        <h3 id="{{$days[$i]}}">{{$days[$i]}}</h3>
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
