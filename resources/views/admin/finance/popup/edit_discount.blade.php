<!-- Modal -->
<div class="modal fade" id="dis-edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل التخفيض</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('discounts.edit')}}" method="POST">
                @csrf
                <input name="student_id" type="hidden" value="{{$std->student_id}}">
                <input name="discount_id" type="hidden" value="" id="discount_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="discount_name">عنوان التخفيض</label>
                        <input required type="text" name="discount_name" class="form-control" id="discount_name2">
                    </div>
                    <div class="form-group">
                        <label for="discount_amount">قيمة التخفيض</label>
                        <input required type="number" name="discount_amount" class="form-control " id="discount_amount2">
                    </div>
                    <div class="form-group">
                        <label for="discount_description">الملاحضات</label>
                        <textarea rows="4" name="discount_description" class="form-control" id="discount_description2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" id="add_payment_btn_form" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
