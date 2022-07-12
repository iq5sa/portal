<!-- Modal -->
<div class="modal fade" id="select_previous_course_modal">
    <div class="modal-dialog dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أختر العام الدارسي المراد الترحيل منه</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="loader"></div>
                <div id="card-data">
                    <form id="course_select_previous_form">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="academic_year_id" class="control-label">السنة الدراسية</label>
                                <div class="input-group input-group-sm">
                                    <select autocomplete="off" name="academic_year_id"
                                            class="form-control"
                                            id="academic_year_id"
                                    >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="college_id" class="control-label">الكلية</label>
                                <div class="input-group input-group-sm">
                                    <select name="college_id" type="text"
                                            class="form-control @if ($errors->has('college_id')) is-invalid @endif"
                                            id="college_id"
                                    >
                                        <option value="">أختر الكلية</option>
                                        @foreach($colleges as $college)
                                            <option value="{{$college->id}}">{{$college->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="department_id" class="control-label">القسم</label>
                                <div class="input-group input-group-sm">
                                    <select name="department_id" type="text"
                                            class="form-control @if ($errors->has('department_id')) is-invalid @endif"
                                            id="department_id"
                                    >
                                        <option value="">أختر القسم</option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="level_id" class="control-label">المرحلة</label>
                                <div class="input-group input-group-sm">
                                    <select name="level_id" type="text"
                                            class="form-control @if ($errors->has('level_id')) is-invalid @endif"
                                            id="level_id"
                                    >
                                        <option value="">أختر المرحلة</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="type_id" class="control-label">نوع الكورس</label>
                                <div class="input-group input-group-sm">
                                    <select name="type_id" type="text"
                                            class="form-control @if ($errors->has('type_id')) is-invalid @endif"
                                            id="type_id"
                                    >
                                        <option value="">أختر نوع الكورس</option>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}">{{$type->type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="shift_id" class="control-label">نوع الدراسة</label>
                                <div class="input-group input-group-sm">
                                    <select name="shift_id" type="text"
                                            class="form-control @if ($errors->has('shift_id')) is-invalid @endif"
                                            id="shift_id"
                                    >
                                        <option value="">أختر نوع الدراسة</option>
                                        @foreach($shifts as $shift)
                                            <option value="{{$shift->id}}">{{$shift->shift}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="time_id" class="control-label">وقت الدوام</label>
                                <div class="input-group input-group-sm">
                                    <select name="time_id" type="text"
                                            class="form-control @if ($errors->has('time_id')) is-invalid @endif"
                                            id="time_id"
                                    >
                                        <option value="">أختر وقت الدوام</option>
                                        @foreach($times as $time)
                                            <option value="{{$time->id}}">{{$time->time}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="batch_id" class="control-label">عنوان الدفعة</label>
                                <div class="input-group input-group-sm">
                                    <select name="batch_id" type="text"
                                            class="form-control @if ($errors->has('batch_id')) is-invalid @endif"
                                            id="batch_id"
                                    >
                                        <option value="">أختر عنوان الدفعة</option>
                                        @foreach($batches as $batch)
                                            <option value="{{$batch->id}}">{{$batch->batch}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="group_id" class="control-label">الكروب</label>
                                <div class="input-group input-group-sm">
                                    <select name="group_id" type="text"
                                            class="form-control @if ($errors->has('group_id')) is-invalid @endif"
                                            id="group_id"
                                    >
                                        <option value="">أختر الكروب</option>
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}">{{$group->group}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive table-sm">
                        <table class="table table-hover table-bordered table-sm" id="previous_course_table" style="width: 100%">
                            <thead>
                            <tr>
                                <th>السنة الاكاديمية</th>
                                <th>الكلية/القسم</th>
                                <th>المرحلة/الكروب</th>
                                <th>نوع الدراسة</th>
                                <th>نوع الكورس</th>
                                <th>الدفعة</th>
                                <th>اختر</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button data-dismiss="modal" class="btn btn-danger" type="button">الغاء</button>
            </div>
        </div>
    </div>
</div>
