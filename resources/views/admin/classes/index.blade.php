@extends('admin.layouts.app')
@include('admin.classes.popup.time')
@include('admin.classes.popup.academic')
@include('admin.classes.popup.college')
@include('admin.classes.popup.department')
@include('admin.classes.popup.level')
@include('admin.classes.popup.shift')
@include('admin.classes.popup.course_types')
@include('admin.classes.popup.batch')
@include('admin.classes.popup.group')
@section('styles')
@endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> أدارة السنوات الدراسية </h1>
            <p>تمكنك هذخ الواجه من أدارة السنوات الدراسية في النظام من خلال فتح سنة دراسية لكل قسم ومرحله عن بداية العام الدراسي.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="add_class_card">
                <div class="card-header">
                    أدارة السنوات الدراسية
                </div>
                <form method="post" action="{{route('class.insert')}}" id="form_create_class" class="my-0">
                    <div class="card-body">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="academic_year_id" class="control-label">السنة الدراسية</label>
                                <div class="input-group input-group-sm">
                                    <select autocomplete="off" name="academic_year_id"
                                            class="form-control"
                                            id="academic_year_id"
                                    >
                                        <option value="">أختر السنة</option>
                                        @foreach($academics as $academic)
                                            <option value="{{$academic->id}}">{{$academic->start_year}}
                                                -{{$academic->end_year}}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#academic-year-show"
                                                class="btn btn-outline-secondary" type="button"
                                                id="academic_year_id_btn"><i class="fa fa-plus"></i></button>
                                    </div>
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
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#college-show"
                                                class="btn btn-outline-secondary" type="button"
                                                id="college_id_btn"><i class="fa fa-plus"></i></button>
                                    </div>
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
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#department-show"
                                                class="btn btn-outline-secondary" type="button"
                                                id="department_id_btn"><i class="fa fa-plus"></i></button>
                                    </div>
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
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#shift-show"
                                                class="btn btn-outline-secondary" type="button" id="shift_id_btn"><i
                                                    class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="type_id" class="control-label">نظام الدراسة</label>
                                <div class="input-group input-group-sm">
                                    <select name="type_id" type="text"
                                            class="form-control @if ($errors->has('type_id')) is-invalid @endif"
                                            id="type_id"
                                    >
                                        <option value="">أختر</option>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}">{{$type->type}}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#type-show"
                                                class="btn btn-outline-secondary" type="button" id="type_id_btn"><i
                                                    class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="level_id" class="control-label">المرحلة/المستوى</label>
                                <div class="input-group input-group-sm">
                                    <select name="level_id" type="text"
                                            class="form-control @if ($errors->has('level_id')) is-invalid @endif"
                                            id="level_id"
                                    >
                                        <option value="">أختر</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#level-show"
                                                class="btn btn-outline-secondary" type="button" id="level_id_btn"><i
                                                    class="fa fa-plus"></i></button>
                                    </div>
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
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#time-show"
                                                class="btn btn-outline-secondary" type="button" id="time_id_btn"><i
                                                    class="fa fa-plus"></i></button>
                                    </div>
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
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#batch-show"
                                                class="btn btn-outline-secondary" type="button" id="batch_id_btn"><i
                                                    class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">

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
                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#group-show"
                                                class="btn btn-outline-secondary" type="button" id="group_id_btn"><i
                                                    class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="start_date_txt" class="control-label">بداية الكورس</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control d_date" name="start_date"
                                           id="start_date_txt">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="end_date_txt" class="control-label">نهاية الكورس</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control d_date" name="end_date" id="end_date_txt">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button id="submit_form" class="btn btn-primary" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>فتح سنة دراسية
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card mt-3" id="add_class_card">
                <div class="card-header">
                    أضافة كورس جديد الى النظام
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable" style="width: 100%">
                            <thead>
                            <tr>
                                <th>ت</th>
                                <th>السنة الاكاديمية</th>
                                <th>رقم الكورس</th>
                                <th>الكلية/القسم</th>
                                <th>المرحلة/الكروب</th>
                                <th>نوع الدراسة</th>
                                <th>نوع الكورس</th>
                                <th>الدفعة</th>
                                <th>التأريخ والوقت</th>
                                <th>الاعدادات</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/underscore-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/validate.js')}}"></script>
    <script>

        function fill_datatable() {
            var formData = $('#form_create_class').serializeArray();
            var data = [];
            for (var i = 0; i < formData.length; i++) {
                data[formData[i]["name"]] = formData[i]["value"];
            }
            data = Object.assign({}, data);
            console.log(data);
            var url = "{{route('class.show.info')}}";

            var table = $('#sampleTable').DataTable({
                paging: true,
                bFilter: false,
                dom: '<<"d-flex justify-content-between"<l><i>>t<"d-flex justify-content-start"<p>>r>',
                language: {
                    "sProcessing": "جارٍ التحميل...",
                    "sLengthMenu": "أظهر _MENU_ مدخلات",
                    "sZeroRecords": "لم يعثر على أية سجلات",
                    "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                    "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                    "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                    "sInfoPostFix": "",
                    "sSearch": "ابحث:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "الأول",
                        "sPrevious": "السابق",
                        "sNext": "التالي",
                        "sLast": "الأخير"
                    },
                },
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: {
                    url: url,
                    type: "POST",
                    data: data
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: "datatable_td",
                        searchable: false,
                        "orderable": false
                    }, {data: "academic_year", name: 'academic_year', className: "datatable_td"},
                    {data: "class_id", name: 'class_id', className: "datatable_td"},
                    {data: "college_department", name: 'college_department', className: "datatable_td"},
                    {data: "level_group", name: 'level', className: "datatable_td"},
                    {data: "shift", name: 'shift', className: "datatable_td"},
                    {data: "type", name: 'type', className: "datatable_td"},
                    {data: "batch", name: 'batch', className: "datatable_td"},
                    {data: "time", name: 'date_time', className: "datatable_td"},
                    {data: "delete_action", name: 'delete_action', className: "datatable_td"},

                ],
            });
        }

        function notify(message) {
            swal.fire({
                type: 'success',
                title: message,
                showConfirmButton: false,
                timer: 5000
            })
        }

        $(document).ready(function () {
            fill_datatable();
            $('.d_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            }).on('changeDate', function () {
                $(this).trigger("change");
            });
            $('#form_academic_year_create :input[type=text]').each(function () {
                $(this).datepicker({
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years"
                });
            });
            $('#form_academic_year_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $("#academic_year_id option:first").after(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.start_year + "-" + response.data.end_year
                            })
                        );
                        $('#form_academic_year_create :input[type=text]').val("");
                        $('#form_academic_year_create :input[type=text]').removeClass("is-invalid");
                        $('#form_academic_year_create div.is-invalid').remove();
                        $('#academic-year-show .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>')
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_academic_year_create input[type=text]").removeClass('is-invalid');
                        $("#form_academic_year_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_academic_year_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_academic_year_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_college_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $("#college_id option:last, #form_department_create #college_id option:last").after(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.name
                            })
                        );
                        $('#form_college_create :input[type=text]').val("");
                        $('#form_college_create :input[type=text]').removeClass("is-invalid");
                        $('#form_college_create div.invalid-feedback').remove();
                        $('#form_college_create .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>');
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_college_create input[type=text]").removeClass('is-invalid');
                        $("#form_college_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_college_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_college_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_create_class #college_id, #form_level_create #college_id').on('change', function (e) {
                var form = $(this).parents('form').get(0);
                var form_id = $(form).attr('id');
                var url = "{{route('class.show.departments')}}";
                var college_id = $(this).val();
                var data = {college_id: college_id};
                var department = $('#' + form_id + ' #department_id');
                $(department).find('option').not(':first').remove();
                axios.post(url, data)
                    .then(function (response) {
                        $.each(response.data, function (i, val) {
                            $(department).append($("<option>", {
                                value: val.id,
                                text: val.name
                            }));
                        });
                    })
                    .catch(function (error) {

                    });
            });
            $('#form_department_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        var selected_college = $("#form_department_create #college_id").val();
                        $("#form_create_class #college_id").val(selected_college);
                        $("#form_create_class #college_id").trigger("change");

                        $('#form_department_create :input[type=text], #form_department_create select').val("");
                        $('#form_department_create :input[type=text], #form_department_create select').removeClass("is-invalid");
                        $('#form_department_create div.invalid-feedback').remove();
                        $('#form_department_create .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>');
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_department_create :input[type=text], #form_department_create select").removeClass('is-invalid');
                        $("#form_department_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_department_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_department_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                                $("#form_department_create select[name=" + key + "]").addClass('is-invalid');
                                $("#form_department_create select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_create_class #department_id, #form_create_class #college_id').on('change', function (e) {
                var url = "{{route('class.show.level')}}";
                console.log(e.target.id);
                var college_id = $("#form_create_class #college_id").val();
                var department_id = $("#form_create_class #department_id").val();
                var data = {
                    college_id: college_id,
                    department_id: department_id
                };
                var level_id = $('#form_create_class #level_id');
                $(level_id).find('option').not(':first').remove();
                axios.post(url, data)
                    .then(function (response) {
                        $.each(response.data, function (i, val) {
                            $(level_id).append($("<option>", {
                                value: val.id,
                                text: val.level
                            }));
                        });
                        showClassInfo();
                    })
                    .catch(function (error) {
                    });
            });
            $('#form_level_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $('#form_level_create :input[type=text], #form_level_create select').val("");
                        $('#form_level_create :input[type=text], #form_level_create select').removeClass("is-invalid");
                        $('#form_level_create div.invalid-feedback').remove();
                        $('#form_level_create .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>');
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_level_create :input[type=text], #form_level_create select").removeClass('is-invalid');
                        $("#form_level_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_level_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_level_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                                $("#form_level_create select[name=" + key + "]").addClass('is-invalid');
                                $("#form_level_create select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_shift_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $("#shift_id option:last").after(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.shift
                            })
                        );
                        $('#form_shift_create :input[type=text]').val("");
                        $('#form_shift_create :input[type=text]').removeClass("is-invalid");
                        $('#form_shift_create div.is-invalid').remove();
                        $('#shift-show .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>');
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_shift_create input[type=text]").removeClass('is-invalid');
                        $("#form_shift_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_shift_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_shift_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_type_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $("#type_id option:last").after(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.type
                            })
                        );
                        $('#form_type_create :input[type=text]').val("");
                        $('#form_type_create :input[type=text]').removeClass("is-invalid");
                        $('#form_type_create div.is-invalid').remove();
                        $('#type-show .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>');
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_type_create input[type=text]").removeClass('is-invalid');
                        $("#form_type_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_type_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_type_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_time_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $("#time_id option:last").after(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.time
                            })
                        );
                        $('#form_time_create :input[type=text]').val("");
                        $('#form_time_create :input[type=text]').removeClass("is-invalid");
                        $('#form_time_create div.is-invalid').remove();
                        $('#time-show .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>');
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_time_create input[type=text]").removeClass('is-invalid');
                        $("#form_time_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_time_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_time_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_batch_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $("#batch_id option:last").after(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.batch
                            })
                        );
                        $('#form_batch_create :input[type=text]').val("");
                        $('#form_batch_create :input[type=text]').removeClass("is-invalid");
                        $('#form_batch_create div.is-invalid').remove();
                        $('#batch-show .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>');
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_batch_create input[type=text]").removeClass('is-invalid');
                        $("#form_batch_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_batch_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_batch_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_group_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $("#group_id option:last").after(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.group
                            })
                        );
                        $('#form_group_create :input[type=text]').val("");
                        $('#form_group_create :input[type=text]').removeClass("is-invalid");
                        $('#form_group_create div.is-invalid').remove();
                        $('#group-show .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>');
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_group_create input[type=text]").removeClass('is-invalid');
                        $("#form_group_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_group_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_group_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });
            $('#form_create_class').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {

                        $('#form_create_class :input[type=text], #form_create_class select').removeClass("is-invalid");
                        $('#form_create_class div.invalid_text_message').remove();
                        notify("تم أضافة الكورس بنجاح!")
                        $('#sampleTable').DataTable().destroy();
                        fill_datatable();
                    })
                    .catch(function (error) {
                        $('#form_create_class :input[type=text], #form_create_class select').removeClass("is-invalid");
                        $('#form_create_class div.invalid_text_message').remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_create_class select[name=" + key + "]").addClass('is-invalid');
                                var x = $("#form_create_class select[name=" + key + "]").parents('div.input-group').get(0);

                                $(x).after('<div class="invalid_text_message text-danger"><small>' + val + '</small></div>');
                                $("#form_create_class input[name=" + key + "]").addClass('is-invalid');
                                var y = $("#form_create_class input[name=" + key + "]").parents('div.input-group').get(0);
                                $(y).after('<div class="invalid_text_message text-danger"><small>' + val + '</small></div>');
                            });

                            if(errors.hasOwnProperty('course_existed')){
                                swal.fire({
                                    type: 'error',
                                    title: "الكورس مضاف مسبقا!",
                                    showConfirmButton: false,
                                    timer: 5000
                                })
                            }
                        }
                    });
            });

            $('#academic_year_id,#college_id,#department_id, #level_id,#type_id, #shift_id, #time_id, #batch_id, #group_id').on('change', function () {
                $('#sampleTable').DataTable().destroy();
                fill_datatable()
            });
        });
    </script>
@endsection
