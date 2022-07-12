<!-- Modal -->
<div class="modal fade" id="department-show" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة قسم</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('class.insert.departments')}}" method="POST"
                  id="form_department_create">
                <div class="modal-body">
                    <div class="form-group">
                        <select id="college_id" name="college_id" class="form-control">
                            <option value="">أختر الكلية</option>
                            @foreach($colleges as $college)
                                <option value="{{$college->id}}">{{$college->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input autocomplete="off" name="name" type="text" class="form-control" value=""
                           placeholder="القسم">
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

