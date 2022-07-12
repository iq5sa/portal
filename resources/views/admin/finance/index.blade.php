@extends('admin.layouts.app')
@section('styles')
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> أدخال ايراد جديد </h1>
            <p>يمكنك من خلال هذه الواجه التعرف على بيانات الطلبة.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" id="filter_student_form">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <input type="text" name="search_all" class="form-control" id="search_all"
                               placeholder="بماذا تفكر؟">
                    </div>
                    <div class="form-group col-md-3">
                        <select name="college_id" class="form-control" id="college_id">
                            <option value="">الكلية</option>
                            @foreach($colleges as $college)
                                <option value="{{$college->id}}">{{$college->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <select name="department_id" class="form-control" id="department_id">
                            <option value="">القسم</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <select name="level_id" class="form-control" id="level_id">
                            <option value="">المرحلة</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <select name="shift_id" class="form-control" id="shift_id">
                            <option value="" selected>نوع الدراسة</option>
                            @foreach($shifts as $shift)
                                <option value="{{$shift->id}}">{{$shift->shift}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <select name="gender" class="form-control" id="gender">
                            <option value="">الجنس</option>
                            <option value="0">ذكر</option>
                            <option value="1">انثى</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <select name="academic_statuses_id" class="form-control" id="academic_statuses_id">
                            <option value="">حالة الطالب</option>
                            @foreach($types as $type)
                                <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </form>
        </div>
        <div class="col-md-12">
            <div class="card shadow" id="add_class_card">
                <div class="card-header">
                    قائمة بيانات الطلبة
                </div>
                <div class="card-body">
                    <div class="table-responsive table-responsive-md">
                        <table class="table table-bordered table-hover" id="sampleTable" style="width: 100%">
                            <thead>
                            <tr>
                                <th class="text-nowrap">ت</th>
                                <th class="text-nowrap">الصورة</th>
                                <th class="text-nowrap">رقم الطالب</th>
                                <th class="text-nowrap">الاسم</th>
                                <th class="text-nowrap">الجنس</th>
                                <th class="text-nowrap">الكلية/القسم</th>
                                <th class="text-nowrap">المرحلة/المستوى</th>
                                <th class="text-nowrap">نوع الدراسة</th>
                                <th class="text-nowrap">الحالة</th>
                                <th class="text-nowrap">الايراد</th>
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
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var table;

            function fill_datatable() {
                table = $('#sampleTable').DataTable({
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
                    ajax: {
                        url: "{{route('fees.students.info')}}",
                        type: "POST",
                        data: function (d) {
                          console.log(d);
                            d['_token'] = "{{csrf_token()}}";
                            d.college_id = $('#filter_student_form select[name=college_id]').val();
                            d.department_id = $('#filter_student_form select[name=department_id]').val();
                            d.level_id = $('#filter_student_form select[name=level_id]').val();
                            d.shift_id = $('#filter_student_form select[name=shift_id]').val();
                            d.gender = $('#filter_student_form select[name=gender]').val();
                            d.academic_statuses_id = $('#filter_student_form select[name=academic_statuses_id]').val();
                            d.search_all = $('#filter_student_form input[name=search_all]').val();
                        }
                    },
                    columns: [
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'image',
                            name: 'image',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'student_id',
                            name: 'student_id',
                            className: "datatable_td",
                            searchable: true,
                            "orderable": true
                        },
                        {
                            data: 'fullname',
                            name: 'fullname',
                            className: "datatable_td text-nowrap",
                            searchable: true,
                            "orderable": true
                        },
                        {data: 'student_gender', name: 'student_gender', className: "datatable_td", searchable: false},
                        {
                            data: 'college_department',
                            name: 'college_department',
                            className: "datatable_td text-nowrap",
                            searchable: false
                        },
                        {data: 'level', name: 'level', className: "datatable_td", searchable: false},
                        {data: 'shift', name: 'shift', className: "datatable_td", searchable: false},
                        {
                            data: 'edited_status',
                            name: 'edited_status',
                            className: "datatable_td",
                            searchable: false
                        },
                        {data: 'collect_fees_btn', name: 'collect_fees_btn', className: "datatable_td", searchable: false},
                    ],
                });


            }

            fill_datatable();


            $('#college_id').on('change', function (e) {
                var url = "{{route('class.show.departments')}}";
                var college_id = $(this).val();
                var data = {college_id: college_id};
                var department = $('#department_id');
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
            $('#department_id, #college_id').on('change', function (e) {
                var url = "{{route('class.show.level')}}";
                var college_id = $("#college_id").val();
                var department_id = $("#department_id").val();
                var data = {
                    college_id: college_id,
                    department_id: department_id
                };
                var level_id = $('#level_id');
                $(level_id).find('option').not(':first').remove();
                axios.post(url, data)
                    .then(function (response) {
                        $.each(response.data, function (i, val) {
                            $(level_id).append($("<option>", {
                                value: val.id,
                                text: val.level
                            }));
                        });
                    })
                    .catch(function (error) {
                    });
            });
            $('#academic_year_id,#department_id, #college_id, #level_id,#shift_id, #gender, #academic_statuses_id').on('change', function (e) {
                table.draw();
                e.preventDefault();
            });
            var typingTimer;
            var doneTypingInterval = 200;
            var $input = $('#search_all');

            $input.on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
            });

            $input.on('keydown', function () {
                clearTimeout(typingTimer);
            });

            function doneTyping() {
                table.draw();
            }

        });

    </script>
@endsection
