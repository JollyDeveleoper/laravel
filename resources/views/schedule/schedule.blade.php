@extends('layouts.app')

@section('content')
    <div class="container">
        <span class="title">Расписание</span>

        @foreach($data as $item => $values)
            @php($modalAddId = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 7))

            <h2 class="mb-2 mt-2">
                {{__('app.days.'.$values[0]['day'])}}
                @if($today === $values[0]['day'])
                    <small class="badge badge-pill badge-primary">Сегодня</small>
                @endif
                @if($isAuth)
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#{{$modalAddId}}">Добавить
                    </button><br>
                @endif
            </h2>
            @foreach($values as $val)
                @php($modalId = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 7))

                <div class="mr-2  mt-2 mb-3 rounded-lg shadow p-3   d-inline-block " style="min-width: 300px">
                    <div class="card-title font-weight-bold">{{ $val['start_time'] . ' - ' . $val['end_time'] }}</div>
                    <small>{{ $val['teacher'] }}</small>
                    <p class="card-text">{{ $val['name'] }}</p>
                    <span class="font-weight-bold">Кабинет:</span> {{ $val['cabinet'] }}
                    <br>
                    @if($isAuth)
                        @include('schedule.template.modal_add')

                    <!-- Modal -->
                        @include('schedule.template.modal')
                        <form action="{{ route('delete') }}" method="post" class="btn-group w-100 mt-2" role="group">

                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-success w-50" data-toggle="modal"
                                    data-target="#{{$modalId}}">
                                Редактировать
                            </button>
                            @csrf
                            <button type="submit" class="btn btn-danger w-50" value="{{ $val['id'] }}"
                                    name="deleteID">
                                Удалить
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        @endforeach

    </div>
@endsection
