@extends('admin.layouts.app')
@section('styles')
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> جميع الطلبة </h1>
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
                               placeholder="البحث عن اسم او رقم الطالب">
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
                            <?php if ($type->name=="مستمر"): ?>
                              <option value="{{$type->id}}" selected>{{$type->name}}</option>
                            <?php else: ?>
                              <option value="{{$type->id}}">{{$type->name}}</option>
                            <?php endif; ?>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <select name="academic_year_id" class="form-control" id="academic_year_id">
                            <option value="">السنة الدراسية</option>
                            @foreach($academics as $academic)
                            <?php if ($academic->active_year==1): ?>
                              <option value="{{$academic->id}}" selected>{{$academic->start_year}}
                                  -{{$academic->end_year}}</option>
                              <?php else: ?>
                                <option value="{{$academic->id}}">{{$academic->start_year}}
                                    -{{$academic->end_year}}</option>
                            <?php endif; ?>
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
                                <th class="text-nowrap">السنة الدراسية</th>
                                <th class="text-nowrap">المحافظة</th>
                                <th class="text-nowrap">المواليد</th>
                                <th class="text-nowrap">العنوان</th>
                                <th class="text-nowrap">الفرع</th>
                                <th class="text-nowrap">قناة القبول</th>
                                <th class="text-nowrap">المعدل قبل</th>
                                <th class="text-nowrap">المعدل بعد</th>
                                <th class="text-nowrap">سنة التخرج اعدادية</th>
                                <th class="text-nowrap">الهاتف</th>
                                <th class="text-nowrap">رقم الهوية</th>

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
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.buttons.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jszip.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/buttons.html5.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/buttons.print.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var table;

            function fill_datatable() {
                table = $('#sampleTable').DataTable({
                    paging: true,
                    bFilter: false,
                    dom: '<<"d-flex justify-content-between"<l><"d-flex justify-content-end"<B>>>t<"d-flex justify-content-between"<p><i>>r>',
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
                    buttons: [
                        {extend: 'excel', className: 'btn btn-success btn-sm'},
                        {
                            extend: 'print',
                            text: 'طباعة',
                            className: 'btn btn-danger btn-sm',
                            customize: function (win) {
                                $(win.document.body).find('table').addClass('display').css('font-size', '9px',).css('direction', 'rtl').addClass('text-right');
                                $(win.document.body).find('tr:nth-child(odd) td').each(function (index) {
                                    $(this).css('background-color', '#D0D0D0');
                                });
                                $(win.document.body).find('h1').css('text-align', 'center');
                            }
                        },
                    ],
                    "lengthMenu": [10, 25, 50, 100, 200, 400, 800,1000,1500,2000, "All"],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{route('students.show.info')}}",
                        type: "POST",
                        data: function (d) {
                            d['_token'] = "{{csrf_token()}}";
                            d.acceptance_year = $('#filter_student_form select[name=academic_year_id]').val();
                            d.year_id = $('#filter_student_form select[name=year_id]').val();
                            d.college_id = $('#filter_student_form select[name=college_id]').val();
                            d.department_id = $('#filter_student_form select[name=department_id]').val();
                            d.level_id = $('#filter_student_form select[name=level_id]').val();
                            d.shift_id = $('#filter_student_form select[name=shift_id]').val();
                            d.gender = $('#filter_student_form select[name=gender]').val();
                            d.academic_statuses_id = $('#filter_student_form select[name=academic_statuses_id]').val();
                            d.academic_year_id = $('#filter_student_form select[name=academic_year_id]').val();
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
                        {
                            data: 'student_gender',
                            name: 'student_gender',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'college_department',
                            name: 'college_department',
                            className: "datatable_td text-nowrap",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'level',
                            name: 'level',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'shift',
                            name: 'shift',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'edited_status',
                            name: 'edited_status',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'academic_year',
                            name: 'academic_year',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'town_name',
                            name: 'town_name',
                            className: "datatable_td",
                            searchable: true,
                            "orderable": true
                        },
                        {
                            data: 'date_of_birth',
                            name: 'date_of_birth',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": true
                        },
                        {
                            data: 'town',
                            name: 'town',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'general',
                            name: 'general',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": true
                        },
                        {
                            data: 'enrollment_channel',
                            name: 'enrollment_channel',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'score_average_before',
                            name: 'score_average_before',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'score_average_after',
                            name: 'score_average_after',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'dacademic_year',
                            name: 'dacademic_year',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'phone',
                            name: 'phone',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
                        {
                            data: 'national_id_number',
                            name: 'national_id_number',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": false
                        },
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
            $('#academic_year_id,#department_id, #college_id, #level_id,#shift_id,#academic_statuses_id, #gender, #year_id').on('change', function (e) {
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
