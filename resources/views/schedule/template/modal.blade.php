<div class="modal fade" id="{{$modalId}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Редактирование</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('edit') }}" method="post">
                @csrf

                <div class="modal-body">
                    <label for="recipient-name" class="col-form-label">Название</label>
                    <input required class="form-control" name="name"
                           value="{{ $val['name'] }}" placeholder="Название предмета">
                    <label for="recipient-name" class="col-form-label" placeholder="Имя перподавателя">Препод</label>
                    <input required class="form-control" name="teacher"
                           value="{{ $val['teacher'] }}">
                    <label for="recipient-name" class="col-form-label">Кабинет</label>
                    <input required class="form-control" name="cabinet"
                           maxlength="9" placeholder="Номер аудитории"
                           value="{{ $val['cabinet'] }}">
                    <div class="row">
                        <div class="col">
                            <label for="recipient-name" class="col-form-label">Начало</label>
                            <input required name="start_time" class="form-control"
                                   type="time"
                                   value="{{ $val['start_time'] }}">
                        </div>
                        <div class="col">
                            <label for="recipient-name" class="col-form-label">Конец</label>
                            <input required name="end_time" class="form-control"
                                   type="time"
                                   value="{{ $val['end_time'] }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть
                    </button>
                    <button type="submit" class="btn btn-primary" value="{{ $val['id'] }}"
                            name="id">Сохранить
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
