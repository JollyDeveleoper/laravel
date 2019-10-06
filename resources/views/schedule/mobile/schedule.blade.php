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
            @for($i = 1; $i < $count_day; $i++)
                <option
                    value="{{ url('schedule') . '#' . __('app.days.'.$i) }}">{{ __('app.days.'.$i) }}
                </option>
            @endfor
        </select>
        <br>

        @if($data)
            @foreach($data as $item => $values)
                <h3 id="{{  __('app.days.'.$values[0]['day']) }}">
                    <small {{$today !== $values[0]['day']}}class="badge badge-pill badge-primary">{{ __('app.days.'.$values[0]['day']) }}</small>

                </h3>
                @php($modalAddId = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 7))

                @if($isAuth)
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

                        @if($isAuth)
                            @include('schedule.template.modal_add')
                            <!-- Modal -->
                                @include('schedule.template.modal')
                                <form action="{{ route('delete') }}" method="post" class="btn-group w-100" role="group">
                                    @csrf
                                    <button type="button" class="btn btn-success w-50" data-toggle="modal"
                                            data-target="#{{$modalId}}">
                                        Редактировать
                                    </button>
                                    <button type="submit" class="btn btn-danger w-50" value="{{ $val['id'] }}"
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
