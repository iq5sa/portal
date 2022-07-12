<!-- Modal -->
<div class="modal fade" id="shift-show" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة نوع الدراسة</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('class.insert.shift')}}" method="POST" id="form_shift_create">
                <div class="modal-body">
                    <input autocomplete="off" autofocus type="text" name="shift" id="shift" class="form-control" placeholder="ادخل نوع الدراسة"/>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
