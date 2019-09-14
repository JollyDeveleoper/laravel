@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ __(session()->get('success')) }}
                {{ session()->forget('success') }}
            </div>
        @endif
        <select class="form-control" onchange="this.value !== '' ? location = this.value : ''; this.select">
            <option value="">Выберите</option>
            <option value="{{ url('schedule/') }}">На неделю</option>
            <option value="{{ url('schedule/today') }}">На сегодня</option>
            <option value="{{ url('schedule/tomorrow') }}">На завтра</option>
            @foreach($data as $item => $value)
                @foreach($value as $val)
                    @if(7 > $val['id'])
                        <option
                            value="{{ url('schedule') . '#' . __('app.days.'.$val['id']) }}">{{ __('app.days.'.$val['id']) }}
                        </option>
                    @endif
                @endforeach
            @endforeach
        </select>
        <br>

        @if($data)
            @foreach($data as $item => $values)
                @php($isToday = date('w') === $values[0]['day'])
                <h3 id="{{  __('app.days.'.$values[0]['day']) }}">
                    @if($isToday)
                        <small class="badge badge-pill badge-primary">{{ __('app.days.'.$values[0]['day']) }}</small>
                    @else
                        {{  __('app.days.'.$values[0]['day']) }}
                    @endif
                </h3>
                @php($modalAddId = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 7))

                @if(Auth::check())
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                            data-target="#{{$modalAddId}}">Добавить
                    </button><br>
                @endif
                @foreach($values as $val)
                    @php($modalId = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 7))


                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $val['start_time'] . ' - ' . $val['end_time'] }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $val['teacher'] }}</h6>
                            <p class="card-text">{{ $val['name'] . " (" . $val['cabinet'] . ")"}} </p>

                            @if(Auth::check())
                                @include('schedule.template.modal_add')

                            <!-- Modal -->
                                @include('schedule.template.modal')
                                <form action="{{ route('delete') }}" method="post">

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#{{$modalId}}">
                                        Редактировать
                                    </button>
                                    @csrf
                                    <button type="submit" class="btn btn-danger" value="{{ $val['id'] }}"
                                            name="deleteID">
                                        Удалить
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                    <br>
                @endforeach
            @endforeach
        @else
            <div class="text-center font-weight-bold">Нет пар на этот день <br>¯\_(ツ)_/¯</div>
        @endif
    </div>
@endsection
