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
            <h1><i class="fa fa-wpforms"></i> أدارة معلومات الادوار في النظام </h1>
            <p>يمكنك من خلال هذه الواجه أدارة بيانات الدور.</p>
        </div>
    </div>
    @if(Session::has('flash_message'))
        <div class="alert {{session('class')}}"><em> {!! session('flash_message') !!}</em></div>
    @endif
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-lg">
                <div class="card-header">
                    اضافة دور مستخدم جديد
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('roles.store')}}" enctype="multipart/form-data"
                          id="create_role_form">
                        @csrf
                        <div class="form-group">
                            <div class="form-group">
                                <input type="text" value="{{old('name')}}" name="name"
                                       class="form-control"
                                       placeholder="العنوان">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <select type="text" name="guard_name"
                                       class="form-control">
                                    <option value="">choose Guard Name</option>
                                    <option value="web">web</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="border-bottom: 1px solid black; width: 100%" class="pb-1 font-weight-bold">الصلاحيات</label>
                            @foreach($permissions as $permission)
                            <div class="form-check">
                                <label class="form-check-label">
                                        <input name="permissions[]" type="checkbox"
                                               class="form-check-input" value="{{$permission->id}}">
                                        <span>{{$permission->name}}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="alert alert-danger" style="display: none"></div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>حفظ المعلومات
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>جميع الصلاحيات</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sampleTable" class="table table-bordered table-striped table-hover" style="width: 100%">
                            <thead>
                            <tr>
                                <th class="text-nowrap">ت</th>
                                <th class="text-nowrap">الدور</th>
                                <th class="text-nowrap">Guard Name</th>
                                <th>الصلاحيات</th>
                                <th class="text-nowrap">أدارة</th>
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
                        url: "{{route('roles.index')}}",
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
                            data: 'guard_name',
                            name: 'guard_name',
                            className: "datatable_td",
                            searchable: true,
                            "orderable": true
                        },
                        {data: 'permissions', name: 'role', className: "datatable_td", searchable: true},
                        {data: 'actions', name: 'actions', className: "datatable_td text-nowrap", searchable: false},

                    ],
                });


            }

            fill_datatable();

            $('#create_role_form').on('submit', function (e) {
                e.preventDefault();

                var form = $("form#create_role_form");

                var formData = $(form).serializeJSON();

                var url = "{{route('roles.store')}}";


                axios.post(url, formData)
                    .then(function (response) {
                        if (response.data.hasOwnProperty("message") === true) {
                            swal.fire({
                                title: "تم!",
                                type: 'success',
                                text: response.data.message,
                                confirmButtonColor: '#d33',
                            });
                            $("#create_role_form :input[type=text],#create_role_form select").removeClass('is-invalid');
                            $("#create_role_form div.invalid-feedback").remove();
                            var cont = $('#create_role_form div.alert');
                            cont.text("");
                            cont.css('display', 'none');
                            table.draw();
                        }
                    })
                    .catch(function (error) {
                        // reset errors
                        $("#create_role_form :input[type=text], #create_role_form select").removeClass('is-invalid');
                        $("#create_role_form div.invalid-feedback").remove();
                        var cont = $('#create_role_form div.alert');
                        cont.text("");
                        cont.css('display', 'none');

                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#create_role_form select[name=" + key + "]").addClass('is-invalid');
                                $("#create_role_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                                $("#create_role_form input[name=" + key + "]").addClass('is-invalid');
                                $("#create_role_form input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                            if (errors.hasOwnProperty('permissions')) {
                                cont = $('#create_role_form div.alert');
                                cont.text(errors.permissions[0]);
                                cont.css('display', 'block');
                            }
                        }

                    });


            });

        });
    </script>
@endsection
