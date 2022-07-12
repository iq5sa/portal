@extends('admin.layouts.app')
@section('styles')
    <style>
        .dbselect {
            margin-top: 50px;
        }

        #student_table tbody tr td.datatable_td, #previous_course_table tbody tr td.datatable_td {
            padding: 5px !important;
            line-height: 100% !important;
            vertical-align: middle !important;
            text-align: center;
        }

        div.dataTables_wrapper div.dataTables_length label {
            margin: 0;
        }

        div.dataTables_wrapper div.dataTables_info {
            padding: 0;
        }

        #student_table_wrapper {
            padding-left: 0;
            padding-right: 0;
        }

        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            padding: 0;
        }

        #previous_course_table_wrapper {
            padding-left: 0;
            padding-right: 0;
        }
    </style>
@endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> اضافة تخفيض الى الطلبة </h1>
            <p>يمكنك من خلال هذه الواجه اختيار الطلبة المراد تخفيض قسطهم الدراسي.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('discount.show.post',$discount->id)}}" id="filter_student_form">
                <input type="hidden" name="discount_id" value="{{$discount->id}}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <input type="text" name="search_all" class="form-control" id="search_all"
                               placeholder="البحث عن اسم الطالب">
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
                        <select name="academic_y_id" class="form-control @error('academic_y_id') is-invalid @enderror"
                                id="academic_y_id">
                            @foreach($academics as $academic)
                                <option
                                    value="{{$academic->id}}">{{$academic->start_year}}
                                    -{{$academic->end_year}}</option>
                            @endforeach
                        </select>
                        @error('academic_y_id')
                        <div class="invalid-feedback">{{ $errors->first('academic_y_id') }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    أختر الطلبة
                </div>
                <div class="card-body">
                    <form action="{{route('discount.assign')}}" method="POST" id="assign_form">
                        <input type="hidden" name="discount_id" value="{{$discount->id}}">
                        @csrf
                        <div class="table-responsive-md">
                            <table class="table table-bordered table-hover" style="width: 100%" id="student_table">
                                <thead>
                                <tr>
                                    <th class="text-nowrap"></th>
                                    <th class="text-nowrap">ت</th>
                                    <th class="text-nowrap">الصورة</th>
                                    <th class="text-nowrap">رقم الطالب</th>
                                    <th class="text-nowrap">الاسم</th>
                                    <th class="text-nowrap">الجنس</th>
                                    <th class="text-nowrap">الكلية/القسم</th>
                                    <th class="text-nowrap">المرحلة</th>
                                    <th class="text-nowrap">نوع الدراسة</th>
                                    <th class="text-nowrap">الحالة</th>
                                    <th class="text-nowrap">سنة القبول</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-row mt-4">
                            <div class="form-group col-md-6">
                                <select id="administrative_order_id" name="administrative_order_id"
                                        class="form-control {{$errors->has('administrative_order_id') ? 'is-invalid':''}}"
                                >
                                    <option value="">أختر رقم الامر الاداري</option>
                                    @foreach($orders as $order)
                                        <option value="{{$order->id}}">{{$order->number}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('administrative_order_id'))
                                    <div class="invalid-feedback">{{$errors->first('administrative_order_id')}}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-12">
                                <button class="btn btn-primary " type="submit"><i
                                        class="fa fa-fw fa-lg fa-check-circle"></i>حفظ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.checkboxes.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.serializejson.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/select2.min.js')}}"></script>

    <script>
        $(document).ready(function () {

            $('#administrative_order_id').select2({
                dir: "rtl",
            });
            var table;

            function fill_student_datatable() {
                table = $('#student_table').DataTable({
                    paging: true,
                    bFilter: false,
                    dom: '<<"d-flex justify-content-between"<l>>t<"d-flex justify-content-between"<p><i>>r>',
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
                        url: "{{route('discount.show.post',$discount->id)}}",
                        type: "POST",
                        data: function (d) {
                            d['_token'] = "{{csrf_token()}}";
                            d.college_id = $('#filter_student_form select[name=college_id]').val();
                            d.department_id = $('#filter_student_form select[name=department_id]').val();
                            d.level_id = $('#filter_student_form select[name=level_id]').val();
                            d.shift_id = $('#filter_student_form select[name=shift_id]').val();
                            d.academic_y_id = $('#filter_student_form select[name=academic_y_id]').val();
                            d.search_all = $('#filter_student_form input[name=search_all]').val();
                        }
                    },
                    columns: [
                        {
                            data: 'fees_id',
                            name: 'fees_id',
                            className: "datatable_td",
                            searchable: false,
                            "orderable": true
                        },
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
                        {data: 'academic_year', name: 'academic_year', className: "datatable_td", searchable: false},
                    ],
                    'columnDefs': [
                        {
                            'targets': 0,
                            'checkboxes': {
                                'selectRow': true
                            }
                        }
                    ],
                    'select': {
                        'style': 'multi'
                    },
                    'order': [[1, 'asc']]
                });
            }

            fill_student_datatable()

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

            $('#academic_y_id,#department_id,#college_id, #level_id,#shift_id').on('change', function (e) {
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

            $('#assign_form').on('submit', function (event) {
                event.preventDefault();


                var form = $("form#assign_form");

                $('.fees_id').remove();
                var rows_selected = table.column(0).checkboxes.selected();

                // Iterate over all selected checkboxes
                $.each(rows_selected, function (index, rowId) {
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('class', 'fees_id')
                            .attr('type', 'hidden')
                            .attr('name', 'fees_id[]')
                            .val(rowId)
                    );
                });

                var formData = $(form).serializeJSON();

                var url = "{{route('discount.assign',$discount->id)}}";

                axios.post(url, formData)
                    .then(function (response) {


                        if(response.data.hasOwnProperty("duplicate_message") =='')
                        {
                            swal.fire({
                                title: "تم!",
                                type: 'success',
                                text: 'تمت عملية التخفيض بنجاح!',
                                confirmButtonColor: '#d33',
                            })
                        }
                        else{
                            swal.fire({
                                title: "خطا!",
                                type: 'error',
                                text: response.data.duplicate_message,
                                confirmButtonColor: '#d33',
                            })
                        }

                        if (response.data.hasOwnProperty("student_info") === true) {
                            $('#student_table').DataTable().destroy();
                            fill_student_datatable();

                            var tr = "";
                            for (let i = 0; i < response.data.student_info.length; i++) {
                                tr += '<tr style="border: 1px solid #000;text-align: center;"><td>' + response.data.student_info[i] + '</td></tr>';
                            }
                            swal.fire({
                                type: 'warning',
                                html:
                                    '<table style="border: 1px solid #000;text-align: center;width: 100%"><thead><tr><td>' + response.data.title + '</td></tr></thead>' +
                                    '<tbody>' + tr + '</tbody>' +
                                    '</table>',
                                showCancelButton: false,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'اغلاق',
                                title: "أنتبه!",
                            })
                        } else if (response.data.hasOwnProperty("message") === true) {
                            $('#student_table').DataTable().destroy();
                            fill_student_datatable();
                            swal.fire({
                                title: "تم!",
                                type: 'success',
                                text: response.data.message,
                                confirmButtonColor: '#d33',
                            })
                        }


                    })
                    .catch(function (error) {
                        // reset errors
                        $("#assign_form :input[type=text], #assign_form select").removeClass('is-invalid');
                        $("#assign_form div.invalid-feedback").remove();
                        var cont = $('#assign_form div.alert');
                        cont.text("");
                        cont.css('display', 'none');

                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#assign_form select[name=" + key + "]").addClass('is-invalid');
                                $("#assign_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                            if (errors.hasOwnProperty('id')) {
                                cont = $('#student_ids_alert');
                                cont.text(errors.id[0]);
                                cont.css('display', 'block');
                            }
                        }

                    });

            });


        });


    </script>


@endsection
