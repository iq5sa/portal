@extends('admin.layouts.app')
@section('styles')
    <style>
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
            <h1><i class="fa fa-wpforms"></i>ادارة المستخدمين </h1>
            <p>تعديل المستخدم</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">تعديل بيانات المستخدم</div>
                <div class="card-body">
                    <form method="post" action="{{route('users.update',$user->id)}}" enctype="multipart/form-data"
                          id="update_user_form">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <select name="role_id" id="role_id"
                                    class="form-control">
                                <option value="">أختر دور المستخدم</option>
                                @foreach($roles as $role)
                                    <option {{$role->id == $user_role ? 'selected' : ''}} value="{{$role->id}}">{{$role->name}}</option>
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
                                                           {{in_array($department->id,$user_departments_ids) ? 'checked':''}}
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
                            <input type="text" value="{{$user->name}}" name="name"
                                   class="form-control"
                                   placeholder="اسم المستخدم">
                        </div>
                        <div class="form-group">
                            <input type="email" value="{{$user->email}}" name="email"
                                   class="form-control"
                                   placeholder="البريد الالكتروني">
                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>تعديل معلومات حساب المستخدم
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-2">
            <div class="card">
                <div class="card-header">أعادة تعيين كلمة المرور</div>
                <div class="card-body">
                    @if(session()->has('reset_message'))
                        <div class="alert alert-success">
                            {{ session()->get('reset_message') }}
                        </div>
                    @endif
                    <form method="post" action="{{route('users.reset.password',$user->id)}}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input type="password" value="" name="password"
                                   class="{{$errors->has('password') ? 'is-invalid': ''}} form-control d_date"
                                   placeholder="كلمة المرور">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">{{$errors->first('password')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <input id="password-confirm" type="password" value="" name="password_confirmation"
                                   class="{{$errors->has('password_confirmation') ? 'is-invalid': ''}} form-control d_date"
                                   placeholder="اعد كتابة كلمة المرور">

                        </div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>تعديل كلمة المرور</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection
@section('scripts')
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset('js/plugins/jquery.serializejson.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {


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
            $('#update_user_form').on('submit', function (e) {
                e.preventDefault();

                var form = $("form#update_user_form");

                var formData = $(form).serializeJSON();

                var url = "{{route('users.update',$user->id)}}";


                axios.post(url, formData)
                    .then(function (response) {
                        if (response.data.hasOwnProperty("message") === true) {
                            swal.fire({
                                title: "تم!",
                                type: 'success',
                                text: response.data.message,
                                confirmButtonColor: '#d33',
                            });
                            $("#update_user_form :input[type=text],#update_user_form :input[type=password],#update_user_form :input[type=email], #update_user_form select").removeClass('is-invalid');
                            $("#update_user_form div.invalid-feedback").remove();
                            var cont = $('#update_user_form div.alert');
                            cont.text("");
                            cont.css('display', 'none');
                        }
                    })
                    .catch(function (error) {
                        // reset errors
                        $("#update_user_form :input[type=text],#update_user_form :input[type=password],#update_user_form :input[type=email], #update_user_form select").removeClass('is-invalid');
                        $("#update_user_form div.invalid-feedback").remove();
                        var cont = $('#update_user_form div.alert');
                        cont.text("");
                        cont.css('display', 'none');

                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#update_user_form input[name=" + key + "]").addClass('is-invalid');
                                $("#update_user_form input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                                $("#update_user_form select[name=" + key + "]").addClass('is-invalid');
                                $("#update_user_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                            if (errors.hasOwnProperty('assigned_departments')) {
                                cont = $('#update_user_form div.alert');
                                cont.text(errors.assigned_departments[0]);
                                cont.css('display', 'block');
                            }
                        }

                    });


            });


        });

    </script>
@endsection






