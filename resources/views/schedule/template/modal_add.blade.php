<div class="modal fade" id="addModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавить пару</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('add') }}" method="post">
                @csrf

                <div class="modal-body">
                    <label for="recipient-name" class="col-form-label">Название</label>
                    <input required type="text" class="form-control" name="name">
                    <label for="recipient-name" class="col-form-label">Преподаватель</label>
                    <input required type="text" class="form-control" name="teacher">
                    <label for="recipient-name" class="col-form-label">Кабинет</label>
                    <input required type="number" class="form-control" name="cabinet"
                           maxlength="3">
                    <div class="row">
                        <div class="col">
                            <label for="recipient-name" class="col-form-label">Начало</label>
                            <input required type="text" name="start_time" class="form-control"
                                   maxlength="5">
                        </div>
                        <div class="col">
                            <label for="recipient-name" class="col-form-label">Конец</label>
                            <input required type="text" name="end_time" class="form-control"
                                   maxlength="5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть
                    </button>
                    <button type="submit" class="btn btn-primary"
                            value="{{ $val['day'] }}"
                            name="day">Сохранить
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
