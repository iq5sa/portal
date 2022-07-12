<!-- Modal -->
<div class="modal fade" id="academic-year-show" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة سنة قبول</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('class.insert.academics')}}" method="POST"
                  id="form_academic_year_create">
                <div class="modal-body date_year">
                    <div class="form-group">
                        <input autocomplete="off" name="start_year" type="text" class="form-control" value=""
                               placeholder="البداية">
                    </div>
                    <input autocomplete="off" name="end_year" type="text" class="form-control" value=""
                           placeholder="النهاية">
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
