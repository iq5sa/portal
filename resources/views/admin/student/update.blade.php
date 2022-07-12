@extends('admin.layouts.app')
@include('admin.student.popup.primary_school_graduation_year')
@section('styles')
    <style>
        #sampleTable tbody tr td.datatable_td {
            padding: 5px !important;
            line-height: 100% !important;
            vertical-align: middle !important;
        }
    </style>
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> تعديل قيد الطالب </h1>
            <p>تمكنك هذه الصفحة من تعديل جميع معلومات الطالب.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    أستمارة تعديل قيد الطالب جديد
                </div>
                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success">تم تعديل قيد الطالب بنجاح</div>
                        @php
                            Session::forget('success');
                        @endphp
                    @endif
                    @if(session()->has('success'))

                    @endif
                    <form method="post" action="{{route('students.update',$std->std_id)}}" id="add_student" novalidate
                          enctype="multipart/form-data">
                        @method('patch')
                        @csrf

                        <div class="row">
                            <div class="col-md-12">
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload" name="photo" accept=".png, .jpg, .jpeg"
                                               value="{{asset('images/logo_new-min.jpg')}}"/>
                                        <label for="imageUpload"></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview"
                                             style="background-image: url({{asset('storage/'.$std->photo)}});">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="mb-2 line-head" id="navs">معلومات القبول</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="admin_order_enrolled" class="control-label">أمر القبول الجامعي</label>
                                    <input class="form-control" id="admin_order_enrolled" type="text" value="{{$std->number}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <input type="hidden" name="class_id" id="class_id" value="{{$std->class_id}}">
                                <div id="selected_course_container" class="mt-3">
                                    @if ($errors->has('class_id'))
                                        <div class="alert alert-danger">{{ $errors->first('class_id') }}</div>
                                    @endif
                                    <table class="table table-striped">
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
                                            <td>{{$std->start_year}}-{{$std->end_year}}</td>
                                            <td>{{$std->college_name}}/{{$std->department_name}}</td>
                                            <td>{{$std->level}}-{{$std->group}}</td>
                                            <td>{{$std->shift}}</td>
                                            <td>{{$std->type}}</td>
                                            <td>{{$std->batch}}</td>

                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="mb-2 line-head" id="navs">معلومات شهادة الاعادادية</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="exam_number" class="control-label">الرقم الامتحاني</label>
                                <input value="{{$std->exam_number}}" name="exam_number" type="text"
                                       class="form-control @if ($errors->has('exam_number')) is-invalid @endif"
                                       id="exam_number"
                                       placeholder="">
                                @if ($errors->has('exam_number'))
                                    <div class="invalid-feedback">{{ $errors->first('exam_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="primary_school_specialty_general_id" class="control-label">
                                    التخصص العام</label>
                                <select name="primary_school_specialty_general_id"
                                        class="form-control @if ($errors->has('primary_school_specialty_general_id')) is-invalid @endif"
                                        id="primary_school_specialty_general_id"
                                >
                                    <option value="">اختار الاختصاص</option>
                                    @foreach($psg as $pg)
                                        <option
                                            value="{{$pg->id}}" {{$pg->id == $std->primary_school_specialty_general_id ? "selected":  ''}}>{{$pg->general}}</option>
                                    @endforeach

                                </select>
                                @if ($errors->has('primary_school_specialty_general_id'))
                                    <div
                                        class="invalid-feedback">{{ $errors->first('primary_school_specialty_general_id') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="primary_school_specialty_general_id" class="control-label">
                                    التخصص الدقيق</label>
                                <select name="primary_school_specialty_special_id"
                                        class="form-control @if ($errors->has('primary_school_specialty_special_id')) is-invalid @endif"
                                        id="primary_school_specialty_special_id"
                                >
                                    <option value="">اختار الاختصاص</option>


                                </select>
                                @if ($errors->has('primary_school_specialty_special_id'))
                                    <div
                                        class="invalid-feedback">{{ $errors->first('primary_school_specialty_special_id') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="exam_attempt_number" class="control-label">الدور</label>
                                <select name="exam_attempt_number" id="exam_attempt_number"
                                        class="form-control @if ($errors->has('exam_attempt_number')) is-invalid @endif">
                                    <option value="">اختر</option>
                                    <option {{$std->exam_attempt_number == 1 ? "selected" : ""}} value="1">الاول
                                    </option>
                                    <option {{$std->exam_attempt_number == 2 ? "selected" : ""}} value="2">الثاني
                                    </option>
                                    <option {{$std->exam_attempt_number == 3 ? "selected" : ""}} value="3">الثالث
                                    </option>
                                </select>
                                @if ($errors->has('exam_attempt_number'))
                                    <div class="invalid-feedback">{{ $errors->first('exam_attempt_number') }}</div>
                                @endif
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="application_certificate" class="control-label">الشهادة التي تم التقديم
                                    بها</label>
                                <select name="application_certificate" type="text"
                                        class="form-control @if ($errors->has('application_certificate')) is-invalid @endif"
                                        id="application_certificate"
                                >
                                    <option value="">-- اختار شهادة --</option>
                                    <option
                                        {{$std->application_certificate == "شهادة اعدادية" ? "selected" : "" }} value="شهادة اعدادية">
                                        شهادة اعدادية
                                    </option>
                                    <option
                                        {{$std->application_certificate == "شهادة معهد" ? "selected" : "" }} value="شهادة معهد">
                                        شهادة معهد
                                    </option>
                                    <option
                                        {{$std->application_certificate == "شهادة دورة تأهيليه" ? "selected" : "" }} value="شهادة دورة تأهيليه">
                                        شهادة دورة تأهيليه
                                    </option>
                                </select>
                                @if ($errors->has('application_certificate'))
                                    <div class="invalid-feedback">{{ $errors->first('application_certificate') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="primary_school_graduation_year" class="control-label">سنة التخرج من
                                    الاعدادية</label>
                                <div class="input-group">
                                    <select name="primary_school_graduation_year" type="text"
                                            class="form-control @if ($errors->has('primary_school_graduation_year')) is-invalid @endif"
                                            id="primary_school_graduation_year"
                                    >
                                        <option value=''>أختر السنة</option>
                                        @foreach($grad_years as $grad_year)
                                            <option
                                                {{$std->primary_school_graduation_year == $grad_year->id ? 'selected': ''}} value="{{$grad_year->id}}">{{$grad_year->start_year}}
                                                -{{$grad_year->end_year}}</option>
                                        @endforeach

                                    </select>

                                    <div class="input-group-append">
                                        <button data-toggle="modal" data-target="#primary_school_graduation_year-show"
                                                class="btn btn-outline-secondary" type="button" id="college_id_btn"><i
                                                class="fa fa-plus"></i></button>
                                    </div>
                                    @if ($errors->has('primary_school_graduation_year'))
                                        <div
                                            class="invalid-feedback">{{ $errors->first('primary_school_graduation_year') }}</div>
                                    @endif
                                </div>
                                <div class="messages"></div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="primary_school_name" class="control-label">اسم المدرسة التي تخرج منها
                                    الطالب</label>
                                <input value="{{$std->primary_school_name}}" name="primary_school_name" type="text"
                                       class="form-control @if ($errors->has('first_name')) is-invalid @endif
                                           " id="primary_school_name"
                                       placeholder="">
                                @if ($errors->has('primary_school_name'))
                                    <div class="invalid-feedback">{{ $errors->first('primary_school_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="has_institution_certificate" class="control-label">هل حصل الطالب على شهادة
                                    معهد؟</label>
                                <select name="has_institution_certificate" id="has_institution_certificate"
                                        class="form-control @if ($errors->has('has_institution_certificate')) is-invalid @endif">
                                    <option value="">اختر</option>
                                    <option {{$std->has_institution_certificate == 0 ? 'selected':''}} value="0">كلا
                                    </option>
                                    <option {{$std->has_institution_certificate == 1 ? 'selected':''}} value="1">نعم
                                    </option>
                                </select>
                                @if ($errors->has('has_institution_certificate'))
                                    <div
                                        class="invalid-feedback">{{ $errors->first('has_institution_certificate') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="has_english_module" class="control-label">هل درس الطالب لغة اجنبية؟</label>
                                <select name="has_english_module" id="has_english_module"
                                        class="form-control @if ($errors->has('has_english_module')) is-invalid @endif">
                                    <option value="">اختر</option>
                                    <option {{$std->has_english_module == 0 ? 'selected':''}} value="0">كلا</option>
                                    <option {{$std->has_english_module == 1 ? 'selected':''}} value="1">نعم</option>
                                </select>
                                @if ($errors->has('has_english_module'))
                                    <div class="invalid-feedback">{{ $errors->first('has_english_module') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="enrollment_channel_id" class="control-label">قناة القبول</label>
                                <select name="enrollment_channel_id" id="enrollment_channel_id"
                                        class="form-control @if ($errors->has('enrollment_channel_id')) is-invalid @endif">
                                    <option value="">اختر</option>
                                    @foreach($channels as $channel)
                                        <option
                                            {{$std->enrollment_channel_id == $channel->id ? 'selected' : ''}} value="{{$channel->id}}">{{$channel->enrollment_channel}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('enrollment_channel_id'))
                                    <div class="invalid-feedback">{{ $errors->first('enrollment_channel_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="total_score" class="control-label">المجموع</label>
                                <input value="{{$std->total_score}}" name="total_score" type="text"
                                       class="form-control @if ($errors->has('total_score')) is-invalid @endif
                                           " id="total_score"
                                       placeholder="">
                                @if ($errors->has('total_score'))
                                    <div class="invalid-feedback">{{ $errors->first('total_score') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2">
                                <label for="number_of_modules_studied" class="control-label">عدد الدروس</label>
                                <select name="number_of_modules_studied" id="number_of_modules_studied"
                                        class="form-control @if ($errors->has('number_of_modules_studied')) is-invalid @endif">
                                    <option value="">أختر عدد الدروس</option>
                                    @if($std->number_of_modules_studied != null)
                                        @for($i = 6; $i<=15; $i++)
                                            <option
                                                {{$i == $std->number_of_modules_studied ? "selected": ""}} value="{{$i}}" {{old('number_of_modules_studied')}}>{{$i}}</option>
                                        @endfor
                                    @else
                                        @for($i = 6; $i<=15; $i++)
                                            <option
                                                {{$i == 7 ? "selected": ""}} value="{{$i}}" {{old('number_of_modules_studied')}}>{{$i}}</option>
                                        @endfor
                                    @endif
                                </select>
                                @if ($errors->has('number_of_modules_studied'))
                                    <div
                                        class="invalid-feedback">{{ $errors->first('number_of_modules_studied') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2">
                                <label for="english_module_score" class="control-label">درجة اللغة الاجنبية</label>
                                <input value="{{$std->english_module_score}}" type="text" class="form-control"
                                       name="english_module_score"
                                       id="english_module_score">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="score_average_before" class="control-label">المعدل قبل الاضافة</label>
                                <input value="{{$std->score_average_before}}" name="score_average_before"
                                       type="text"
                                       class="form-control @if ($errors->has('score_average_before')) is-invalid @endif"
                                       id="score_average_before"
                                       placeholder="">
                                @if ($errors->has('score_average_before'))
                                    <div class="invalid-feedback">{{ $errors->first('score_average_before') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2">
                                <label for="score_average_after" class="control-label">المعدل بعد الاضافة</label>
                                <input value="{{$std->score_average_after}}" name="score_average_after"
                                       type="text"
                                       class="form-control @if ($errors->has('score_average_after')) is-invalid @endif"
                                       id="score_average_after"
                                       placeholder="">
                                @if ($errors->has('score_average_after'))
                                    <div class="invalid-feedback">{{ $errors->first('score_average_after') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="mb-2 line-head" id="navs">المعلومات الشخصية</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="title" class="control-label">الاسم</label>
                                <input value="{{$std->first_name == null ? $std->full_name: $std->first_name}}"
                                       name="first_name" type="text"
                                       class="form-control @if ($errors->has('first_name')) is-invalid @endif
                                           " id="title"
                                       placeholder="">
                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="father_name" class="control-label">أسم الاب</label>
                                <input value="{{$std->father_name}}" name="father_name" type="text"
                                       class="form-control @if ($errors->has('father_name')) is-invalid @endif"
                                       id="father_name"
                                       placeholder="">
                                @if ($errors->has('father_name'))
                                    <div class="invalid-feedback">{{ $errors->first('father_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="middle_name" class="control-label">أسم الجد</label>
                                <input value="{{$std->middle_name}}" name="middle_name" type="text"
                                       class="form-control @if ($errors->has('middle_name')) is-invalid @endif"
                                       id="middle_name"
                                       placeholder="">
                                @if ($errors->has('middle_name'))
                                    <div class="invalid-feedback">{{ $errors->first('middle_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="last_name" class="control-label">أسم أب الجد</label>
                                <input value="{{$std->last_name}}" name="last_name" type="text"
                                       class="form-control @if ($errors->has('last_name')) is-invalid @endif"
                                       id="last_name"
                                       placeholder="">
                                @if ($errors->has('last_name'))
                                    <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="surname" class="control-label">اللقب</label>
                                <input value="{{$std->surname}}" name="surname" type="text"
                                       class="form-control @if ($errors->has('surname')) is-invalid @endif"
                                       id="surname"
                                       placeholder="">
                                @if ($errors->has('surname'))
                                    <div class="invalid-feedback">{{ $errors->first('surname') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="date_of_birth" class="control-label">تأريخ الولادة</label>
                                <input value="{{$std->date_of_birth}}" name="date_of_birth" type="text"
                                       class="d_date form-control @if ($errors->has('date_of_birth')) is-invalid @endif"
                                       id="date_of_birth"
                                       placeholder="">
                                @if ($errors->has('date_of_birth'))
                                    <div class="invalid-feedback">{{ $errors->first('date_of_birth') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="place_of_birth" class="control-label">محل الولادة</label>
                                <input value="{{$std->place_of_birth}}" name="place_of_birth" type="text"
                                       class="form-control @if ($errors->has('place_of_birth')) is-invalid @endif"
                                       id="place_of_birth"
                                       placeholder="">
                                @if ($errors->has('place_of_birth'))
                                    <div class="invalid-feedback">{{ $errors->first('place_of_birth') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="gender" class="control-label">الجنس</label>
                                <select name="gender" type="text"
                                        class="form-control @if ($errors->has('gender')) is-invalid @endif"
                                        id="gender"
                                >
                                    <option value=''>-- اختار الجنس --</option>
                                    <option {{$std->gender == 0 ? 'selected' : ''}} value="0">ذكر</option>
                                    <option {{$std->gender == 1 ? 'selected' : ''}} value="1">انثى</option>
                                </select>
                                @if ($errors->has('gender'))
                                    <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="social_status" class="control-label">الحالة الاجتماعية</label>
                                <select name="social_status" type="text"
                                        class="form-control @if ($errors->has('social_status')) is-invalid @endif"
                                        id="social_status"
                                >
                                    <option value=''>-- اختار الحالة الاجتماعية --</option>
                                    <option value="0" {{$std->social_status==0 ? 'selected':''}}>اعزب</option>
                                    <option value="1" {{$std->social_status==1 ? 'selected':''}}>متزوج</option>
                                </select>
                                @if ($errors->has('social_status'))
                                    <div class="invalid-feedback">{{ $errors->first('social_status') }}</div>
                                @endif
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="national_id_number" class="control-label">رقم الهوية</label>
                                <input value="{{$std->national_id_number}}" name="national_id_number" type="text"
                                       class="form-control @if ($errors->has('national_id_number')) is-invalid @endif"
                                       id="enrollment_number"
                                       placeholder="">
                                @if ($errors->has('national_id_number'))
                                    <div class="invalid-feedback">{{ $errors->first('national_id_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="national_id_issue_date" class="control-label">تأريخ الاصدار</label>
                                <input value="{{$std->national_id_issue_date}}" name="national_id_issue_date"
                                       type="text"
                                       class="d_date form-control @if ($errors->has('national_id_issue_date')) is-invalid @endif"
                                       id="national_id_issue_date"
                                       placeholder="">
                                @if ($errors->has('national_id_issue_date'))
                                    <div class="invalid-feedback">{{ $errors->first('national_id_issue_date') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="national_id_issuer" class="control-label">جهة الاصدار</label>
                                <input value="{{$std->national_id_issuer}}" name="national_id_issuer" type="text"
                                       class="form-control @if ($errors->has('national_id_issuer')) is-invalid @endif"
                                       id="national_id_issuer"
                                       placeholder="">
                                @if ($errors->has('national_id_issuer'))
                                    <div class="invalid-feedback">{{ $errors->first('national_id_issuer') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="certificate_of_iraqi_nationality" class="control-label">رقم شهادة
                                    الجنسية</label>
                                <input value="{{$std->certificate_of_iraqi_nationality}}"
                                       name="certificate_of_iraqi_nationality" type="text"
                                       class="form-control @if ($errors->has('certificate_of_iraqi_nationality')) is-invalid @endif"
                                       id="certificate_of_iraqi_nationality"
                                       placeholder="">
                                @if ($errors->has('certificate_of_iraqi_nationality'))
                                    <div
                                        class="invalid-feedback">{{ $errors->first('certificate_of_iraqi_nationality') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="certificate_of_iraqi_nationality_issue_date" class="control-label">تأريخ
                                    الاصدار</label>
                                <input value="{{$std->certificate_of_iraqi_nationality_issue_date}}"
                                       name="certificate_of_iraqi_nationality_issue_date" type="text"
                                       class="d_date form-control @if ($errors->has('certificate_of_iraqi_nationality_issue_date')) is-invalid @endif"
                                       id="certificate_of_iraqi_nationality_issue_date"
                                       placeholder="">
                                @if ($errors->has('certificate_of_iraqi_nationality_issue_date'))
                                    <div
                                        class="invalid-feedback">{{ $errors->first('certificate_of_iraqi_nationality_issue_date') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="certificate_of_iraqi_nationality_issuer" class="control-label">جهة
                                    الاصدار</label>
                                <input value="{{$std->certificate_of_iraqi_nationality_issuer}}"
                                       name="certificate_of_iraqi_nationality_issuer" type="text"
                                       class="form-control @if ($errors->has('certificate_of_iraqi_nationality_issuer')) is-invalid @endif"
                                       id="certificate_of_iraqi_nationality_issuer"
                                       placeholder="">
                                @if ($errors->has('certificate_of_iraqi_nationality_issuer'))
                                    <div
                                        class="invalid-feedback">{{ $errors->first('certificate_of_iraqi_nationality_issuer') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="mb-2 line-head" id="navs">معلومات الاتصال</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="city" class="control-label">المحافظة</label>
                                <select
                                    name="city"
                                    class="form-control @if ($errors->has('city')) is-invalid @endif"
                                    id="city"
                                >
                                    <option value="">أختر المحافظة</option>
                                    @foreach($towns as $town)
                                        <option
                                            {{$std->city == $town->id ? 'selected':''}} value="{{$town->id}}">{{$town->town_name}}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('city'))
                                    <div class="invalid-feedback">{{ $errors->first('city') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="town" class="control-label">القضاء</label>
                                <input value="{{$std->town}}"
                                       name="town" type="text"
                                       class="form-control @if ($errors->has('town')) is-invalid @endif"
                                       id="town"
                                       placeholder="">
                                @if ($errors->has('town'))
                                    <div class="invalid-feedback">{{ $errors->first('town') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="township" class="control-label">الناحية</label>
                                <input value="{{$std->township}}"
                                       name="township" type="text"
                                       class="form-control @if ($errors->has('township')) is-invalid @endif"
                                       id="township"
                                       placeholder="">
                                @if ($errors->has('township'))
                                    <div class="invalid-feedback">{{ $errors->first('township') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="neighbor" class="control-label">الحي او القرية</label>
                                <input value="{{$std->neighbor}}"
                                       name="neighbor" type="text"
                                       class="form-control @if ($errors->has('neighbor')) is-invalid @endif"
                                       id="neighbor"
                                       placeholder="">
                                @if ($errors->has('neighbor'))
                                    <div class="invalid-feedback">{{ $errors->first('neighbor') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="district_No" class="control-label">رقم المحلة</label>
                                <input value="{{$std->district_no}}"
                                       name="district_no" type="text"
                                       class="form-control @if ($errors->has('district_no')) is-invalid @endif"
                                       id="district_no"
                                       placeholder="">
                                @if ($errors->has('district_no'))
                                    <div class="invalid-feedback">{{ $errors->first('district_no') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="side_street_no" class="control-label">رقم الزقاق</label>
                                <input value="{{$std->side_street_no}}"
                                       name="side_street_no" type="text"
                                       class="form-control @if ($errors->has('side_street_no')) is-invalid @endif"
                                       id="side_street_No"
                                       placeholder="">
                                @if ($errors->has('side_street_no'))
                                    <div class="invalid-feedback">{{ $errors->first('side_street_no') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="house_number" class="control-label">رقم الدار</label>
                                <input value="{{$std->house_number}}"
                                       name="house_number" type="text"
                                       class="form-control @if ($errors->has('house_number')) is-invalid @endif"
                                       id="house_number"
                                       placeholder="">
                                @if ($errors->has('house_number'))
                                    <div class="invalid-feedback">{{ $errors->first('house_number') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="phone" class="control-label">رقم الموبايل</label>
                                <input value="{{$std->phone}}"
                                       name="phone" type="text"
                                       class="form-control @if ($errors->has('phone')) is-invalid @endif"
                                       id="phone"
                                       placeholder="07[7,8,9]xxxxxxxx">
                                @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="house_phone_no" class="control-label">رقم الهاتف الارضي</label>
                                <input value="{{$std->house_phone_no}}"
                                       name="house_phone_no" type="text"
                                       class="form-control @if ($errors->has('house_phone_no')) is-invalid @endif"
                                       id="house_phone_no"
                                       placeholder="">
                                @if ($errors->has('house_phone_no'))
                                    <div class="invalid-feedback">{{ $errors->first('house_phone_no') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email" class="control-label">البريد الالكتروني</label>
                                <input value="{{$std->email}}"
                                       name="email" type="text"
                                       class="form-control @if ($errors->has('email')) is-invalid @endif"
                                       id="email"
                                       placeholder="">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="near_point" class="control-label">اقرب نقطة دالة</label>
                                <input value="{{$std->near_point}}"
                                       name="near_point" type="text"
                                       class="form-control @if ($errors->has('near_point')) is-invalid @endif"
                                       id="near_point"
                                       placeholder="">
                                @if ($errors->has('near_point'))
                                    <div class="invalid-feedback">{{ $errors->first('near_point') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="mb-2 line-head" id="navs">معلومات الوظيفية(للموظفين فقط)</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="ministry_name" class="control-label">الوزارة</label>
                                <input value="{{$std->ministry_name}}"
                                       name="ministry_name" type="text"
                                       class="form-control @if ($errors->has('ministry_name')) is-invalid @endif"
                                       id="ministry_name"
                                       placeholder="">
                                @if ($errors->has('ministry_name'))
                                    <div class="invalid-feedback">{{ $errors->first('ministry_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="department" class="control-label">الدائرة</label>
                                <input value="{{$std->department}}"
                                       name="department" type="text"
                                       class="form-control @if ($errors->has('department')) is-invalid @endif"
                                       id="department"
                                       placeholder="">
                                @if ($errors->has('department'))
                                    <div class="invalid-feedback">{{ $errors->first('department') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="work_place" class="control-label">محل العمل</label>
                                <input value="{{$std->work_place}}"
                                       name="work_place" type="text"
                                       class="form-control @if ($errors->has('work_place')) is-invalid @endif"
                                       id="work_place"
                                       placeholder="">
                                @if ($errors->has('work_place'))
                                    <div class="invalid-feedback">{{ $errors->first('work_place') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="career_title" class="control-label">العنوان الوظيفي او
                                    المنصب</label>
                                <input value="{{$std->career_title}}"
                                       name="career_title" type="text"
                                       class="form-control @if ($errors->has('career_title')) is-invalid @endif"
                                       id="career_title"
                                       placeholder="">
                                @if ($errors->has('career_title'))
                                    <div class="invalid-feedback">{{ $errors->first('career_title') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="mb-2 line-head" id="navs">وثائق الطالب</p>
                            </div>
                        </div>


                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">ت</th>
                                <th scope="col">اسم الملف</th>
                                <th scope="col">رابط الملف</th>
                                <th scope="col">تعديل</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($student_documents as $doc)
                                <tr>
                                    <th scope="row">{{$doc['id']}}</th>
                                    <td>{{$doc['title']}}</td>
                                    <td>
                                        @if($doc['link'] != '')
                                            <a class="btn btn-success btn-sm" href="{{$doc['link']}}">تحميل الملف</a>
                                    </td>
                                    @endif
                                    <td><input
                                            name="{{$doc['input_name']}}" type="file"
                                            class="form-control"
                                            placeholder="">

                                </tr>

                            @endforeach

                            </tbody>
                        </table>


                        <div class="form-group">
                            <div class="tile-footer">
                                <button id="submit_form" class="btn btn-success" type="button"><i
                                        class="fa fa-fw fa-lg fa-check-circle"></i>تعديل قيد الطالب
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
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/underscore-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/validate.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <!-- select class modal -->
    <script>

        $(document).ready(function () {
            $('#college_id').change(function () {
                var dpSelect = $('#department_id');
                dpSelect.children('option:not(:first)').remove();
                var college_id = $(this).children("option:selected").val();
                var data = {
                    college_id: college_id
                };
                axios.get('{{route("students.create")}}', {
                    params: data
                })
                    .then(function (response) {
                        $.each(response.data, function (i, department) {
                            dpSelect.append($('<option>', {
                                value: department.id,
                                text: department.name
                            }))
                        });
                    })
                    .catch(function (error) {

                    })
                    .then(function () {
                        // always executed
                    });


            });
            $('#total_score').on('keyup', function () {
                var total = $(this).val();
                var number = $('#number_of_modules_studied').children("option:selected").val();
                var score_average = 0;
                if (total > 0 && number > 0) {
                    score_average = total / number;
                }
                var score_avg = $('#score_average');
                score_avg.val(Math.round(score_average * 100) / 100);
                score_avg.trigger("change");
            });
            $('#number_of_modules_studied').change(function () {
                var total = $('#total_score').val();
                var number = $(this).children("option:selected").val();
                var score_average = 0;
                if (total > 0 && number > 0) {
                    score_average = total / number;
                }
                var scorr_avg = $('#score_average');
                scorr_avg.val(Math.round(score_average * 100) / 100);
                scorr_avg.trigger("change");

            });

            $('#score_average_after, #score_average_before').prop('readonly', true);
            $('.d_date').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            }).on('changeDate', function () {
                $(this).trigger("change");
            });

            $('#form_primary_school_graduation_year_create :input[type=text]').each(function () {
                $(this).datepicker({
                    autoclose: true,
                    clearBtn: true,
                    todayHighlight: true,
                    format: "yyyy",
                    viewMode: "years",
                    minViewMode: "years"
                });
            });

            $('#form_primary_school_graduation_year_create').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $("#primary_school_graduation_year option:first").after(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.start_year + "-" + response.data.end_year
                            })
                        );
                        $('#form_primary_school_graduation_year_create :input[type=text]').val("");
                        $('#form_primary_school_graduation_year_create :input[type=text]').removeClass("is-invalid");
                        $('#form_primary_school_graduation_year_create div.is-invalid').remove();
                        $('#primary_school_graduation_year-show .modal-body').prepend('<div class="alert alert-success py-1">تم اضافة القيد بنجاح!</div>')
                        $(".alert-success").fadeOut(5000);
                    })
                    .catch(function (error) {
                        $("#form_primary_school_graduation_year_create input[type=text]").removeClass('is-invalid');
                        $("#form_primary_school_graduation_year_create div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#form_primary_school_graduation_year_create input[name=" + key + "]").addClass('is-invalid');
                                $("#form_primary_school_graduation_year_create input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });

            function set_avg() {

                var total = $('#total_score').val();
                var english_module_score = $('#english_module_score').val();
                var number = $('#number_of_modules_studied').children("option:selected").val();
                var has_institution_certificate = $('#has_institution_certificate').children("option:selected").val();
                var has_english_module = $('#has_english_module').children("option:selected").val();
                var exam_attempt_number = $('#exam_attempt_number').children("option:selected").val();
                var score_average_before = 0;
                var score_average_after = 0;
                if (total > 0 && number > 0) {
                    score_average_before = total / number;
                    score_average_after = score_average_before;
                }
                var score_avg_b = $('#score_average_before');
                var score_avg_a = $('#score_average_after');

                if (has_institution_certificate === '1') {
                    if (score_average_after <= 99) {
                        score_average_after += 2;
                    }
                }
                if (has_english_module === '1') {
                    if (score_average_after <= 99) {
                        if (english_module_score >= 50 && english_module_score <= 100) {
                            score_average_after += (english_module_score / 7) * 0.08;
                        }
                    }
                }
                if (exam_attempt_number === '1') {
                    if (score_average_after <= 99) {
                        score_average_after += 1;
                    }
                }
                score_avg_b.val(Math.round(score_average_before * 100) / 100);
                score_avg_a.val(Math.round(score_average_after * 100) / 100);
                score_avg_b.trigger('change');
                score_avg_a.trigger('change');
            }

            $('#english_module_score, #exam_attempt_number, #total_score, #number_of_modules_studied, #has_institution_certificate,#has_english_module').on({
                'change': function () {
                    set_avg()
                }
            }, {
                'keyup': function () {
                    set_avg()
                }
            });

            validate.validators.presence.options = {message: "\^ لايمكن ان يكون الحقل فارغاً."};
            validate.validators.numericality.options = {notValid: "\^ يجب أدخال ارقام فقط."};
            // Before using it we must add the parse and format functions
            // Here is a sample implementation using moment.js
            validate.extend(validate.validators.datetime, {
                // The value is guaranteed not to be null or undefined but otherwise it
                // could be anything.
                parse: function (value, options) {
                    return +moment.utc(value);
                },
                // Input is a unix timestamp
                format: function (value, options) {
                    var format = options.dateOnly ? "YYYY-MM-DD" : "YYYY-MM-DD hh:mm:ss";
                    return moment.utc(value).format(format);
                }
            });

            // These are the constraints used to validate the form
            var constraints = {

                'enrollment_number': {
                    presence: false,
                    numericality: true
                },
                'primary_school_specialty_general_id': {
                    presence: false,
                },
                'exam_attempt_number': {
                    presence: false,
                },
                'enrollment_number_date': {
                    presence: false,
                    datetime: {dateOnly: true, notValid: '\^ يجب ادخال تأريخ صحيح.'},
                },
                'enrollment_year_id': {
                    presence: false,
                    numericality: true
                },
                'exam_number': {
                    presence: false,
                    numericality: false
                },
                'application_certificate': {
                    presence: false,
                },
                'primary_school_graduation_year': {
                    presence: false,
                },
                'primary_school_name': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'total_score': {
                    presence: false,
                    numericality: {
                        onlyInteger: true,
                        greaterThanOrEqualTo: 0,
                        lessThanOrEqualTo: 800,
                        notGreaterThanOrEqualTo: "\^ يجب ان يكون المجموع من 0 الى 800.",
                        notLessThanOrEqualTo: "\^ يجب ان لايتجاوز المجموع 800 درجة."
                    }
                },
                'number_of_modules_studied': {
                    presence: false,
                    numericality: true
                },
                'first_name': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'father_name': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'middle_name': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'last_name': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'surname': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'date_of_birth': {
                    presence: false,
                    datetime: {dateOnly: true, notValid: '\^ يجب ادخال تأريخ صحيح.'},
                },
                'place_of_birth': {
                    presence: false,
                },
                'gender': {
                    presence: false,
                },
                'social_status': {
                    presence: false,
                },
                'national_id_number': {
                    presence: false,
                    numericality: true
                },
                'national_id_issue_date': {
                    presence: false,
                    datetime: {dateOnly: true, notValid: '\^ يجب ادخال تأريخ صحيح.'},
                },
                'national_id_issuer': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'certificate_of_iraqi_nationality': {
                    presence: false,
                    numericality: true
                },
                'certificate_of_iraqi_nationality_issue_date': {
                    presence: false,
                    datetime: {dateOnly: true, notValid: '\^ يجب ادخال تأريخ صحيح.'},
                },
                'certificate_of_iraqi_nationality_issuer': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'city': {
                    presence: false,
                },
                'town': {
                    presence: false
                },
                'township': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'neighbor': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'district_no': {
                    presence: false,
                    numericality: true
                },
                'side_street_no': {
                    presence: false,
                    numericality: true
                },
                'house_number': {
                    presence: false,
                    numericality: true
                },
                'phone': {
                    presence: false,
                    numericality: true
                },
                'house_phone_no': {
                    numericality: true
                },
                'email': {
                    presence: false,
                    email: true
                },
                'near_point': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'ministry_name': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'department': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'work_place': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'career_title': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
            };


            // Hook up the form so we can prevent it from being posted
            var form = $("form#add_student");
            $('#submit_form').on("click", function (ev) {
                ev.preventDefault();
                // validate the form against the constraints
                var errors = validate(form, constraints);
                console.log(errors);
                // then we update the form to reflect the results
                if (!errors) {
                    $(form).submit();
                } else {
                    var inputs = $("#add_student :input:not([type=hidden],[type=submit]), #add_student textarea, #add_student select");
                    inputs.each(function (key, input) {
                        showErrorsForInput(this, errors && errors[$(this).attr("name")])
                    });
                }
            });

            // Hook up the inputs to validate on the fly
            var inputs = $("#add_student :input:not([type=hidden],[type=submit]), #add_student textarea, #add_student select");
            inputs.each(function (key, input) {
                $(input).bind("keyup change focusout", function (ev) {
                    var errors = validate(form, constraints) || {};
                    showErrorsForInput(this, errors[$(this).attr("name")])
                });
            });

            // Shows the errors for a specific input
            function showErrorsForInput(input, errors) {
                var formGroup = $(input).closest("div.form-group");
                if ($(formGroup).find("div.invalid-feedback").length > 0) {
                    $(input).removeClass('is-invalid');
                    var feedbackDiv = $(formGroup).find('div.invalid-feedback');
                    feedbackDiv.remove();
                }
                if (errors) {
                    $(input).addClass('is-invalid');
                    var block = document.createElement("div");
                    $(block).addClass("invalid-feedback");
                    $(block).text(errors);
                    $(formGroup).append(block);
                }
            }

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(650);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function () {
                readURL(this);
            });
            function update_primary_school_options(trigger, id) {
                var url = "{{route('specialty.index')}}";
                var data = {primary_school_specialty_general_id: id, '_token': "{{csrf_token()}}"};

                $('#primary_school_specialty_special_id').find('option').not(':first').remove();
                axios.post(url, data)
                    .then(function (response) {
                        $.each(response.data, function (i, val) {
                            if (trigger === 0) {

                                $('#primary_school_specialty_special_id').append($("<option>", {
                                    value: val.id,
                                    text: val.special,
                                }));

                                $('#primary_school_specialty_special_id option[value=' + id + ']').attr('selected', 'selected');
                            } else if (trigger === 1) {
                                $('#primary_school_specialty_special_id').append($("<option>", {
                                    value: val.id,
                                    text: val.special
                                }));
                            }

                        });
                    })
                    .catch(function (error) {

                    });
            }
            $('#primary_school_specialty_general_id').on('change', function (event) {
                event.preventDefault();
                var id = $(this).val();
                update_primary_school_options(1, id)
            });
            update_primary_school_options(0, "{{$std->primary_school_specialty_general_id}}")

        });
    </script>


@endsection
