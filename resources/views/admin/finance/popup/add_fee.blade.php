<!-- Modal -->
<div class="modal fade" id="add_fee">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة مبلغ القسط</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('fees.add_fee')}}" method="POST"
                  id="add_fee_form">

                @csrf
                <input name="student_id" hidden value="{{$student_id}}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="academic_year_id">اختر العام الدراسي</label>
                        <select name="academic_year_id" id="academic_year_id" class="form-control">
                            <option value="">أختر</option>
                            @foreach($academics as $year)
                                <option  value="{{$year->id}}">{{$year->end_year}}-{{$year->start_year}}</option>
                            @endforeach
                        </select>
                    </div>
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
