@extends('admin.layouts.app')
@include('admin.student.tarheel.popup.select_previous_course')
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
            <h1><i class="fa fa-wpforms"></i> واجهة الترحيل </h1>
            <p>يمكنك من خلال هذه الواجه ترحيل الطلبة الى المراحل الدراسية او المستويات القادمة.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    أختيار معلومات الترحيل
                </div>
                <div class="card-body">
                    <button id="prv_course_btn" type="button"
                            class="btn btn-danger"><i class="fa fa-plus"></i>اختيار العام الدراسي المراد الترحيل منه
                    </button>
                    <button id="nxt_course_btn" type="button"
                            class="btn btn-success"><i class="fa fa-plus"></i>اختيار العام الدراسي المراد الترحيل اليه
                    </button>
                    <div id="selected_course_container_prev" class="mt-2">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                            <tr>
                                <th>السنة الاكاديمية</th>
                                <th>الكلية والقسم</th>
                                <th>المرحلة والكروب</th>
                                <th>نوع الدراسة</th>
                                <th>نوع الكورس</th>
                                <th>الدفعة</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="6" class="text-center">يجب اختيار الكورس للطالب</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="alert alert-danger my-2" id="prv_c_cont" style="display: none"></div>
                    </div>
                    <div id="selected_course_container_nxt" class="mt-2">
                        <table class="table table-bordered table-sm table-success mb-0">
                            <thead>
                            <tr>
                                <th>السنة الاكاديمية</th>
                                <th>الكلية والقسم</th>
                                <th>المرحلة والكروب</th>
                                <th>نوع الدراسة</th>
                                <th>نوع الكورس</th>
                                <th>الدفعة</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="6" class="text-center">يجب اختيار الكورس للطالب</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="alert alert-danger my-2" id="nxt_c_cont" style="display: none"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    أختر الطلبة المراد ترحيلهم
                </div>
                <div class="card-body">
                    <form id="tarheel_form" action="{{route('tarheel.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="tarhel_page" id="tarhel_page" value="1">
                        <input type="hidden" name="class_id" id="class_id">
                        <input type="hidden" name="nxt_class_id" id="nxt_class_id">
                        <input type="hidden" name="department_id_input" id="department_id_input">
                        <input type="hidden" name="college_id_input" id="college_id_input">
                        <input type="hidden" name="shift_id_input" id="shift_id_input">
                        <div class="table-responsive">
                            <div class="row ">
                                <div class="col-md-4">
                                    <input type="text" class="form-control mb-2" name="search_all" id="search_all"
                                           placeholder="ابحث عن اسم الطالب">
                                </div>
                            </div>
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
                                    <th class="text-nowrap">المرحلة/المستوى</th>
                                    <th class="text-nowrap">نوع الدراسة</th>
                                    <th class="text-nowrap">الحالة</th>
                                    <th class="text-nowrap">سنة القبول</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="alert alert-danger my-2" id="student_ids_alert" style="display: none"></div>
                        <div class="form-row mt-4">
                            <div class="form-group col-md-4">
                                <select id="academic_status_id" name="academic_status_id"
                                        class="form-control {{$errors->has('academic_status_id') ? 'is-invalid':''}}"
                                >
                                    <option value="">أختر حالة الترحيل</option>
                                    @foreach($special_statues as $special_statue)
                                        <option value="{{$special_statue->id}}">{{$special_statue->name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('academic_status_id'))
                                    <div class="invalid-feedback">{{$errors->first('academic_status_id')}}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <select id="administrative_order_id" name="administrative_order_id"
                                        class="form-control {{$errors->has('administrative_order_id') ? 'is-invalid':''}}"
                                >
                                    <option value="">أختر رقم الامر الاداري</option>
                                    @foreach($orders as $order)
                                        <option value="{{$order->admin_id}}">{{$order->admin_number}}-{{$order->academic_name}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('administrative_order_id'))
                                    <div class="invalid-feedback">{{$errors->first('administrative_order_id')}}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-12">
                                <label for="nationality_id_text"></label>
                                <button class="btn btn-primary" type="submit" id="send_tarheel_btn"><i
                                        class="fa fa-fw fa-lg fa-check-circle"></i>بدء عملية الترحيل
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
                dropdownCssClass: 'dbselect'
            });

            var selected_button = "";
            $('#select_previous_course_modal').on('hidden.bs.modal', function () {
                $('#previous_course_table').DataTable().destroy();
            });

            $('#card-data').css('display', 'none');
            $('.loader').css('display', 'none');

            var prev_table;
            var students_table;

            function fill_classes_datatable() {
                var formData = $('#course_select_previous_form').serializeArray();
                var data = [];
                for (var i = 0; i < formData.length; i++) {
                    data[formData[i]["name"]] = formData[i]["value"];
                }
                data['department_id_input'] = $('input#department_id_input').val();
                data['college_id_input'] = $('input#college_id_input').val();
                //data['shift_id_input'] = $('input#shift_id_input').val();
                data = Object.assign({}, data);

                var url = "{{route('class.show.info')}}";
                prev_table = $('#previous_course_table').DataTable({
                    paging: true,
                    bFilter: false,
                    dom: 't<"d-flex justify-content-start"<p>>r',
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
                        url: url,
                        type: "POST",
                        data: data
                    },
                    columns: [
                        {data: "academic_year", name: 'academic_year', className: "datatable_td"},
                        {data: "college_department", name: 'college_department', className: "datatable_td"},
                        {data: "level_group", name: 'level', className: "datatable_td"},
                        {data: "shift", name: 'shift', className: "datatable_td"},
                        {data: "type", name: 'type', className: "datatable_td"},
                        {data: "batch", name: 'batch', className: "datatable_td"},
                        {data: "action", name: 'action', className: "datatable_td"},

                    ],
                });
            }

            function fill_student_datatable() {
                students_table = $('#student_table').DataTable({
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
                        url: "{{route('studentstarheel.show.info')}}",
                        type: "POST",
                        data: function (d) {
                            d['_token'] = "{{csrf_token()}}";
                            console.log($('input[name=search_all]').val());
                            console.log($('#tarheel_form input[name=class_id]').val());
                            d.search_all = $('input[name=search_all]').val();
                            d.class_id = $('#tarheel_form input[name=class_id]').val();
                            d.tarhel_page = $('#tarheel_form input[name=tarhel_page]').val();
                        }
                    },
                    columns: [
                        {
                            data: 'student_id',
                            name: 'student_id',
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
                    students_table.draw();
                }

            }

            fill_student_datatable();


            $('#previous_course_table tbody').on('click', '#btn-select-class', function (e) {
                e.preventDefault();
                var data_prev = prev_table.row($(this).parents('tr')).data();

                if (selected_button === 1) {
                    $('#tarheel_form input[name=class_id]').val();
                    $('#tarheel_form input[name=department_id_input]').val();
                    $('#tarheel_form input[name=college_id_input]').val();
                    $('#tarheel_form input[name=shift_id_input]').val();

                    $('#tarheel_form input[name=class_id]').val(data_prev.class_id);
                    $('#tarheel_form input[name=department_id_input]').val(data_prev.department_id);
                    $('#tarheel_form input[name=college_id_input]').val(data_prev.college_id);
                    $('#tarheel_form input[name=shift_id_input]').val(data_prev.shift_id);
                }


                var tr = "<td>" + data_prev.academic_year + "</td>" +
                    "<td>" + data_prev.college_department + "</td>" +
                    "<td>" + data_prev.level_group + "</td>" +
                    "<td>" + data_prev.shift + "</td>" +
                    "<td>" + data_prev.type + "</td>" +
                    "<td>" + data_prev.batch + "</td>";

                if (selected_button === 1) {
                    $('#selected_course_container_prev table tbody tr').empty();
                    $('#selected_course_container_prev table tbody tr').append(tr);
                    $('#select_previous_course_modal').modal('hide');
                    students_table.draw();
                } else if (selected_button === 2) {
                    $('#selected_course_container_nxt table tbody tr').empty();
                    $('#selected_course_container_nxt table tbody tr').append(tr);
                    $('#tarheel_form input[name=nxt_class_id]').val(data_prev.class_id);
                    $('#select_previous_course_modal').modal('hide');
                }

            });

            $('#academic_year_id,#college_id,#department_id, #level_id,#type_id, #shift_id, #time_id, #batch_id, #group_id').on('change', function (e) {
                $('#previous_course_table').DataTable().destroy();
                fill_classes_datatable();
                e.preventDefault();
            });

            $('#course_select_previous_form #college_id').on('change', function (e) {
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
            $('#department_id, #college_id').on('change', function (e) {
                var form = $(this).parents('form').get(0);
                var form_id = $(form).attr('id');

                var url = "{{route('class.show.level')}}";
                var department_id = $('#' + form_id + ' #department_id').val();
                var college_id = $('#' + form_id + ' #college_id').val();
                var data = {
                    college_id: college_id,
                    department_id: department_id
                };
                var level_id = $('#level_id');
                level_id.prop('selectedIndex', 0);
                level_id.find('option').not(':first').remove();
                axios.post(url, data)
                    .then(function (response) {
                        $.each(response.data, function (i, val) {
                            level_id.append($("<option>", {
                                value: val.id,
                                text: val.level
                            }));
                        });
                    })
                    .catch(function (error) {
                    });
            });

            $('#prv_course_btn').on('click', function (e) {
                e.preventDefault();

                selected_button = 1;
                $("#nxt_class_id").val("");
                $('#selected_course_container_nxt table tbody tr:first-child').remove();
                $('#selected_course_container_nxt table tbody').append('<tr><td colspan="6" class="text-center">يجب اختيار الكورس القادم</td></tr>');

                $('#tarheel_form input[name=class_id]').val("");
                $('#tarheel_form input[name=department_id_input]').val("");
                $('#tarheel_form input[name=college_id_input]').val("");
                $('#tarheel_form input[name=shift_id_input]').val("");
                $('.loader').css('display', 'block');
                $("#select_previous_course_modal").modal({
                    backdrop: "static",
                    keyboard: false,
                    show: true
                });

                $('#department_id').find('option').not(':first').remove();
                $('#level_id').find('option').not(':first').remove();
                var data = {};
                var url = "{{route('academic_years.active')}}";
                axios.post(url, data)
                    .then(function (response) {
                        var ac_year = $('#course_select_previous_form select[name=academic_year_id]');
                        ac_year.empty();

                        $.each(response.data, function (i, val) {
                            ac_year.append(
                                $("<option>", {
                                    value: val.id,
                                    text: val.start_year + "-" + val.end_year
                                })
                            );
                        });

                        $("#course_select_previous_form").trigger("reset");
                        fill_classes_datatable();
                        $('.loader').css('display', 'none');
                        $('#card-data').css('display', 'block');
                        //$("#loadMe").modal("hide");
                    })
                    .catch(function (error) {

                    });
            });

            $('#nxt_course_btn').on('click', function (e) {
                e.preventDefault();
                selected_button = 2;
                var class_id = $('#class_id').val();

                if (class_id !== "") {
                    $('.loader').css('display', 'block');
                    $("#select_previous_course_modal").modal({
                        backdrop: "static",
                        keyboard: false,
                        show: true
                    });
                    $('#department_id').find('option').not(':first').remove();
                    $('#level_id').find('option').not(':first').remove();

                    var data = {
                        active_year: $('#course_select_previous_form select[name=academic_year_id]').val()
                    };
                    var url = "{{route('academic_years.new_year')}}";
                    axios.post(url, data)
                        .then(function (response) {
                            var ac_year = $('#course_select_previous_form select[name=academic_year_id]');
                            console.log(response.data);
                            /*ac_year.empty();
                            $.each(response.data, function (i, val) {
                                ac_year.append(
                                    $("<option>", {
                                        value: val.id,
                                        text: val.start_year + "-" + val.end_year
                                    })
                                );
                            });
*/

                            $("#course_select_previous_form").trigger("reset");
                            fill_classes_datatable();
                            $('.loader').css('display', 'none');
                            $('#card-data').css('display', 'block');
                            //$("#loadMe").modal("hide");
                        })
                        .catch(function (error) {

                        });
                } else {
                    swal.fire({
                        text: 'أختر الكورس السابق اولاً!',
                        type: 'error',
                        confirmButtonText: 'تم'
                    });
                }
            });


            // Handle form submission event
            $('#tarheel_form').on('submit', function (e) {
                e.preventDefault();

                var form = $("form#tarheel_form");

                $('.st_ids').remove();
                var rows_selected = students_table.column(0).checkboxes.selected();

                // Iterate over all selected checkboxes
                $.each(rows_selected, function (index, rowId) {
                    // Create a hidden element
                    $(form).append(
                        $('<input>')
                            .attr('class', 'st_ids')
                            .attr('type', 'hidden')
                            .attr('name', 'id[]')
                            .val(rowId)
                    );
                });

                var formData = $(form).serializeJSON();

                var url = "{{route('tarheel.store')}}";

                axios.post(url, formData)
                    .then(function (response) {
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
                        $("#tarheel_form :input[type=text], #tarheel_form select").removeClass('is-invalid');
                        $("#tarheel_form div.invalid-feedback").remove();
                        var cont = $('#tarheel_form div.alert');
                        cont.text("");
                        cont.css('display', 'none');

                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#tarheel_form select[name=" + key + "]").addClass('is-invalid');
                                $("#tarheel_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                            if (errors.hasOwnProperty('id')) {
                                cont = $('#student_ids_alert');
                                cont.text(errors.id[0]);
                                cont.css('display', 'block');
                            }
                            if (errors.hasOwnProperty('class_id')) {
                                cont = $('#prv_c_cont');
                                cont.text(errors.class_id[0]);
                                cont.css('display', 'block');
                            }
                            if (errors.hasOwnProperty('nxt_class_id')) {
                                cont = $('#nxt_c_cont');
                                cont.text(errors.nxt_class_id[0]);
                                cont.css('display', 'block');
                            }
                        }

                    });


            });
            $('#academic_status_id').on('change', function (e) {
                e.preventDefault();
                if ($(this).val() === '14') {
                    $('#administrative_order_id_loading').css('display', 'block');
                } else {
                    $('#administrative_order_id_loading').css('display', 'none');
                }
            })
        });
    </script>


@endsection
