<!-- Modal -->
<div class="modal fade" id="type-show" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة نوع الكورس</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('class.insert.types')}}" method="POST" id="form_type_create" class="mb-0">
                <div class="modal-body">
                    <input autocomplete="off" autofocus type="text" name="type" id="type" class="form-control" placeholder="نوع الكورس (مقررات,كورسات,سنوي)"/>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-time" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
