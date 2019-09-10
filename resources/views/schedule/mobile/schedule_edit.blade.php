@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Понедельник</h3>
        <form method="post" action="{{ route('save') }}">
            @csrf
            <textarea name="json" class="form-control" rows="30">
                {{ json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ) }}
            </textarea>
            <br>
            <button type="submit" class="btn btn-success btn-lg btn-block">Сохранить</button>
        </form>
    </div>

@endsection
