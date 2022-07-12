<!-- Modal -->
<div class="modal fade" id="college_or_department-show" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة كلية/قسم</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="" method="POST" id="form_college_or_department_create">
                <div class="modal-body">
                    <div class="form-group">
                        <input required autocomplete="off" autofocus type="text" name="college_or_department" id="college_or_department" class="form-control" placeholder="الكلية/القسم"/>
                    </div>
                    <input type="text" name="description" id="description" class="form-control" placeholder="الوصف" required/>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
