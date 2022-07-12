<!-- Modal -->
<div class="modal fade" id="edit_fee">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تعديل المبلغ المطلوب</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('fees.edit_fee')}}" method="POST"
                  id="edit_fee_form">

                @csrf
                <input name="student_id" hidden value="{{$student_id}}">
                <input name="fee_id" hidden id="fee_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="amount"> مبلغ القسط</label>
                        <input type="number" name="amount" id="amount" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
