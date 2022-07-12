<!-- Modal -->
<div class="modal fade" id="college-show" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة كلية</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('class.insert.college')}}" method="GET"
                  id="form_college_create">
                <div class="modal-body">
                    <input autocomplete="off" name="name" type="text" class="form-control" value=""
                           placeholder="الكلية">
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-college" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>


