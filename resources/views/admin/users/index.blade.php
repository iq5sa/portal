@extends('admin.layouts.app')
@section('styles')
    <style>
        #sampleTable tbody tr td.datatable_td {
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

        #sampleTable_wrapper {
            padding-left: 0;
            padding-right: 0;
        }

        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            padding: 0;
        }

        .departments_list, .departments_list ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
    </style>
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> ادارة المستخدمين </h1>
            <p>ادارة المستخدمين لهذا النظام</p>
        </div>
    </div>
    <div class="row">

        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header">
                    اضافة مستخدم جديد
                </div>
                <div class="card-body">
                    <form method="get"  enctype="multipart/form-data"
                          id="create_user_form">
                        @csrf
                        <div class="form-group">
                            <select name="role_id" id="role_id"
                                    class="form-control">
                                <option value="">أختر دور المستخدم</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label style="border-bottom: 1px solid black; width: 100%" class="pb-1 font-weight-bold">أختر
                                الاقسام التي يمكن الوصول اليها</label>

                            <ul class="departments_list form-check">
                                <li class="form-check-label">
                                    <input type="checkbox" class="form-check-input">
                                    <span class="font-weight-bold text-muted">تحديد الكل</span>
                                    <ul style="margin-right: -1.25rem">
                                        @foreach($colleges as $college)
                                            @foreach($college->departments as $department)
                                                <li class="form-check-label">
                                                    <input name="assigned_departments[]" type="checkbox"
                                                           class="form-check-input" value="{{$department->id}}">
                                                    <span>{{$department->name}}</span>
                                                </li>
                                            @endforeach
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>

                        </div>
                        <div class="alert alert-danger" style="display: none"></div>

                        <div class="form-group">
                            <input type="text" value="{{old('name')}}" name="name"
                                   class="form-control"
                                   placeholder="اسم المستخدم">
                        </div>
                        <div class="form-group">
                            <input type="email" value="{{old('email')}}" name="email"
                                   class="form-control"
                                   placeholder="البريد الالكتروني">
                        </div>
                        <div class="form-group">
                            <input type="password" value="" name="password"
                                   class="{{$errors->has('password') ? 'is-invalid': ''}} form-control d_date"
                                   placeholder="الباسورد">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">{{$errors->first('password')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <input id="password-confirm" type="password" value="" name="password_confirmation"
                                   class="form-control d_date"
                                   placeholder="اعد كتابة كلمة المرور" autocomplete="new-password">
                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>أنشاء
                                حساب مستخدم
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header">
                    جميع المستخدمين
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sampleTable" class="table table-bordered table-striped table-hover text-center" style="width: 100%">
                            <thead>
                            <tr>
                                <th class="text-nowrap">ت</th>
                                <th class="text-nowrap">اسم المستخدم</th>
                                <th class="text-nowrap">البريد الالكتروني</th>
                                <th class="text-nowrap">الصلاحيات</th>
                                <th class="text-nowrap">حالة الحساب</th>
                                <th class="text-nowrap">تعديل</th>
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
    <script type="text/javascript" src="{{asset('js/plugins/jquery.serializejson.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var table;
            function fill_datatable() {
                table = $('#sampleTable').DataTable({
                    paging: true,
                    bFilter: true,
                    dom: '<<"d-flex justify-content-between"<l><"d-flex justify-content-end"<f>>>t<"d-flex justify-content-between"<p><i>>r>',
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
                    "lengthMenu": [10, 25, 50, 100, 200, 400, 1000],
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{route('users.index')}}",
                        type: "GET",
                        data: function (d) {
                            d['_token'] = "{{csrf_token()}}";
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
                            data: 'name',
                            name: 'name',
                            className: "datatable_td",
                            searchable: true,
                            "orderable": true
                        },
                        {
                            data: 'email',
                            name: 'email',
                            className: "datatable_td text-nowrap",
                            searchable: true,
                            "orderable": true
                        },
                        {data: 'role', name: 'role', className: "datatable_td", searchable: true},
                        {
                            data: 'active_account',
                            name: 'active_account',
                            className: "datatable_td text-nowrap",
                            searchable: false
                        },
                        {data: 'actions', name: 'actions', className: "datatable_td text-nowrap", searchable: false},

                    ],
                });


            }

            fill_datatable();

            $('input[type="checkbox"]').change(function (e) {

                var checked = $(this).prop("checked"),
                    container = $(this).parent(),
                    siblings = container.siblings();

                container.find('input[type="checkbox"]').prop({
                    indeterminate: false,
                    checked: checked
                });

                function checkSiblings(el) {

                    var parent = el.parent().parent(),
                        all = true;

                    el.siblings().each(function () {
                        let returnValue = all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
                        return returnValue;
                    });

                    if (all && checked) {

                        parent.children('input[type="checkbox"]').prop({
                            indeterminate: false,
                            checked: checked
                        });

                        checkSiblings(parent);

                    } else if (all && !checked) {

                        parent.children('input[type="checkbox"]').prop("checked", checked);
                        parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
                        checkSiblings(parent);

                    } else {

                        el.parents("li").children('input[type="checkbox"]').prop({
                            indeterminate: true,
                            checked: false
                        });

                    }

                }

                checkSiblings(container);
            });
            $('#create_user_form').on('submit', function (e) {
                e.preventDefault();

                var form = $("form#create_user_form");

                var formData = $(form).serializeJSON();

                var url = "{{route('users.store')}}";


                axios.post(url, formData)
                    .then(function (response) {
                        if (response.data.hasOwnProperty("message") === true) {
                            swal.fire({
                                title: "تم!",
                                type: 'success',
                                text: response.data.message,
                                confirmButtonColor: '#d33',
                            });
                            $("#create_user_form :input[type=text],#create_user_form :input[type=password],#create_user_form :input[type=email], #create_user_form select").removeClass('is-invalid');
                            $("#create_user_form div.invalid-feedback").remove();
                            var cont = $('#create_user_form div.alert');
                            cont.text("");
                            cont.css('display', 'none');
                            table.draw();
                        }
                    })
                    .catch(function (error) {
                        // reset errors
                        $("#create_user_form :input[type=text],#create_user_form :input[type=password],#create_user_form :input[type=email], #create_user_form select").removeClass('is-invalid');
                        $("#create_user_form div.invalid-feedback").remove();
                        var cont = $('#create_user_form div.alert');
                        cont.text("");
                        cont.css('display', 'none');

                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#create_user_form input[name=" + key + "]").addClass('is-invalid');
                                $("#create_user_form input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                                $("#create_user_form select[name=" + key + "]").addClass('is-invalid');
                                $("#create_user_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                            if (errors.hasOwnProperty('assigned_departments')) {
                                cont = $('#create_user_form div.alert');
                                cont.text(errors.assigned_departments[0]);
                                cont.css('display', 'block');
                            }
                        }

                    });


            });


        });

    </script>
@endsection
