<!-- Modal -->
<div class="modal fade" id="group-show" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة الكروب</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('class.insert.group')}}" method="POST" id="form_group_create">
                <div class="modal-body">
                    <input autocomplete="off" autofocus type="text" name="group" id="group" class="form-control" placeholder="الكروب"/>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
