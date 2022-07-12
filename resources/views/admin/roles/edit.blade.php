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
            <h1><i class="fa fa-wpforms"></i>تعديل معلومات الادوار في النظام </h1>
            <p>يمكنك من خلال هذه الواجه تعديل بيانات الدور.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">تعديل بيانات الادوار المستخدمة في النظام</div>
                <div class="card-body">
                    <form method="post" action="{{route('roles.update',$role->id)}}" enctype="multipart/form-data"
                          id="update_role_form">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <div class="form-group">
                                <input type="text" value="{{$role->name}}" name="name"
                                       class="form-control"
                                       placeholder="عنوان الصلاحية">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <select type="text" name="guard_name"
                                        class="form-control">
                                    <option value="">choose Guard Name</option>
                                    <option {{$role->guard_name == 'web' ? 'selected' : ''}} value="web">web
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="border-bottom: 1px solid black; width: 100%" class="pb-1 font-weight-bold">الصلاحيات</label>
                            @foreach($permissions as $permission)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input name="permissions[]" type="checkbox"
                                               {{in_array($permission->id,$role->permissions->pluck('id')->all()) ? 'checked': ''}}
                                               class="form-check-input" value="{{$permission->id}}">
                                        <span>{{$permission->name}}</span>

                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="alert alert-danger" style="display: none"></div>
                        <div class="form-group mb-0">
                            <button class="btn btn-primary" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>تعديل المعلومات
                            </button>
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


            $('#update_role_form').on('submit', function (e) {
                e.preventDefault();

                var form = $("form#update_role_form");

                var formData = $(form).serializeJSON();

                var url = "{{route('roles.update',$role->id)}}";


                axios.post(url, formData)
                    .then(function (response) {
                        if (response.data.hasOwnProperty("message") === true) {
                            swal.fire({
                                title: "تم!",
                                type: 'success',
                                text: response.data.message,
                                confirmButtonColor: '#d33',
                            });
                            $("#update_role_form :input[type=text],#update_role_form select").removeClass('is-invalid');
                            $("#update_role_form div.invalid-feedback").remove();
                            var cont = $('#update_role_form div.alert');
                            cont.text("");
                            cont.css('display', 'none');
                        }
                    })
                    .catch(function (error) {
                        // reset errors
                        $("#update_role_form :input[type=text],#update_role_form select").removeClass('is-invalid');
                        $("#update_role_form div.invalid-feedback").remove();
                        var cont = $('#update_role_form div.alert');
                        cont.text("");
                        cont.css('display', 'none');

                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#update_role_form select[name=" + key + "]").addClass('is-invalid');
                                $("#update_role_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                                $("#update_role_form input[name=" + key + "]").addClass('is-invalid');
                                $("#update_role_form input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                            if (errors.hasOwnProperty('permissions')) {
                                cont = $('#update_role_form div.alert');
                                cont.text(errors.permissions[0]);
                                cont.css('display', 'block');
                            }
                        }

                    });


            });


        });

    </script>
@endsection






