<!-- Modal -->
<div class="modal fade" id="payment-edit-show">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل وصل</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('payments.update')}}" method="POST"
                  id="edit_payment_form">
                @csrf
                @method('PUT')
                <input name="programing_id" type="hidden">
                <input name="student_id" type="hidden" value="{{$std->student_id}}">
                <input name="fees_id" type="hidden" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="payment_date" class="control-label">تأريخ الوصل</label>
                        <input name="payment_date" type="text" class="form-control " id="payment_date">
                    </div>
                    <div class="form-group">
                        <label for="payment_id" class="control-label">رقم الوصل</label>
                        <input name="payment_id" type="number" class="form-control " id="payment_id">
                    </div>
                    <div class="form-group">
                        <label for="payment_amount" class="control-label">مبلغ الوصل</label>
                        <input value="" name="payment_amount" type="number" class="form-control " id="payment_amount">
                    </div>
                    <div class="form-group">
                        <label for="payment_method" class="control-label">رقم الوصل</label>
                        <select id="payment_method" name="payment_method" class="form-control">
                            <option value="">نوع الدفع</option>
                            <option value="1">كاش</option>
                            <option value="2">فيشه</option>
                            <option value="3">صك سفتجه</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cheque_number" class="control-label">رقم الصك</label>
                        <input name="cheque_number" type="number" class="form-control " id="cheque_number">
                    </div>
                    <div class="form-group">
                        <label for="cheque_date" class="control-label">تأريخ الصك</label>
                        <input name="cheque_date" type="text" class="form-control " id="cheque_date">
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">الملاحضات</label>
                        <textarea rows="3" id="description" name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" id="add_payment_btn_form" type="submit">تعديل</button>
                </div>
            </form>
        </div>
    </div>
</div>

