<!-- Modal -->
<div class="modal fade" id="level-show" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة مرحلة</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('class.insert.level')}}" method="POST" id="form_level_create">
                <div class="modal-body">
                    <div class="form-group">
                        <select class="form-control" name="college_id" id="college_id">
                            <option class="text-center" value="">أختر الكلية/القسم</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}">{{ $college->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="department_id" id="department_id">
                            <option class="text-center" value="">أختر القسم</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input autocomplete="off" autofocus type="text" name="level" id="level" class="form-control" placeholder="المرحلة"/>
                    </div>
                    <input type="text" name="description" id="description" class="form-control" placeholder="الوصف"/>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
