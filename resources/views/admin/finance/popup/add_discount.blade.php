<!-- Modal -->
<div class="modal fade" id="dis-show">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة تخفيض</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('fees.add_dis')}}" method="POST" id="add_dis_form">
                @csrf
                <input name="student_id" type="hidden" value="{{$std->student_id}}">
                <input name="fees_id" type="hidden" value="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="discount_name">عنوان التخفيض</label>
                        <a href="#" style="margin-right: 10px;"> اضافة عنوان جديد</a>
                        <select class="form-control" name="discount_name" id="discount_name">
                            @foreach($discountNames as $name)
                                <option value="{{$name->discount_name}}">{{$name->discount_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="discount_amount">قيمة التخفيض</label>
                        <input required type="number" name="discount_amount" class="form-control " id="discount_amount">
                    </div>
                    <div class="form-group">
                        <label for="discount_description">الملاحضات</label>
                        <textarea rows="4" name="discount_description" class="form-control"
                                  id="discount_description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" id="add_payment_btn_form" type="submit">حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
