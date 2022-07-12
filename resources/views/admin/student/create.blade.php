@extends('admin.layouts.app')
@include('admin.student.popup.course_select')
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
            <h1><i class="fa fa-wpforms"></i> أضافة قيد طالب جديد </h1>
            <p>اتبع الخطوات الواردة في الاستمارة ادناه لغرض اضافة قيد طالب.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{route('students.exits')}}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <input id="nationality_id_text" type="text" name="student_nationality_id"
                               class="form-control {{$errors->has('student_nationality_id') ? 'is-invalid':''}}"
                               placeholder="أدخل رقم هويه الطالب او البطاقة الوطنية"
                               value="{{old('student_nationality_id')}}">
                        @if($errors->has('student_nationality_id'))
                            <div class="invalid-feedback">{{$errors->first('student_nationality_id')}}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="nationality_id_text"></label>
                        <button class="btn btn-primary" type="submit"><i
                                class="fa fa-fw fa-lg fa-check-circle"></i>استعلام قيد الطالب
                        </button>
                    </div>
                </div>
                @if(session()->has('student_nationality_id'))
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="alert alert-dismissible alert-success">
                                <button class="close" type="button" data-dismiss="alert">×
                                </button>{{session('student_nationality_id')}}</div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">
                    انشاء قيد الطالب جديد
                </div>
                <div class="card-body">
                    @if(session()->has('message') && session()->has('class'))
                        <div class="alert {{ session()->get('class') }}" role="alert">
                            <h4 class="alert-heading">تم</h4>
                            <p>{{ session()->get('message') }}</p>
                            @if(session()->get('student') != null)
                                <hr>
                                <a href="{{route('students.show',session()->get('student')->student_id)}}" class="mb-0">لعرض
                                    قيد الطالب اضغط هنا!</a>
                            @endif
                        </div>
                    @endif


                    <form method="post" action="{{route('students.store')}}" id="add_student" novalidate
                          enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <p class="mb-2 line-head" id="navs">معلومات القبول</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="enrollment_number" class="control-label">أمر القبول الجامعي</label>
                                        <div class="input-group">
                                            <select name="enrollment_number"
                                                    class="form-control @if ($errors->has('enrollment_number')) is-invalid @endif"
                                                    id="enrollment_number"
                                            >
                                                <option value="">أختر</option>
                                                @foreach($orders as $o)
                                                    <option value="{{$o->id}}">{{$o->number}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('enrollment_number'))
                                                <div
                                                    class="invalid-feedback  messages">{{ $errors->first('enrollment_number') }}</div>
                                            @else
                                                <div class="messages"></div>
                                            @endif
                                            {{--<div class="input-group-append">
                                                <button data-toggle="modal" data-target="#enrollment-show"
                                                        class="btn btn-outline-secondary" type="button"
                                                        id="college_id_btn"><i class="fa fa-plus"></i></button>
                                            </div>--}}
                                        </div>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="student_id" class="control-label">رقم الطالب الجامعي</label>
                                        <input value="{{old('student_id')}}" name="student_id" type="text"
                                               class="form-control @if ($errors->has('student_id')) is-invalid @endif
                                                   " id="title"
                                               placeholder="">
                                        @if ($errors->has('student_id'))
                                            <div class="invalid-feedback">{{ $errors->first('student_id') }}</div>
                                        @endif

                                    </div>
                                </div>
                                <button data-toggle="modal" data-target="#select_course_modal" type="button"
                                        class="btn btn-danger"><i class="fa fa-plus"></i>اختر المعلومات الاكاديمية
                                </button>
                            </div>
                            <div class="col-md-3">
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type='file' id="imageUpload" name="photo" accept=".png, .jpg, .jpeg"/>
                                        <label for="imageUpload"></label>
                                    </div>
                                    <div class="avatar-preview">
                                        <div id="imagePreview"
                                             style="background-image: url({{asset('images/specialist-user.svg')}});">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="class_id" id="class_id">
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
                                            <td colspan="6" class="text-center">يجب اختيار الكورس للطالب</td>
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
                                <input value="{{old('exam_number')}}" name="exam_number" type="text"
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
                                        <option value="{{$pg->id}}">{{$pg->general}}</option>
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
                                    <option value="1">الاول</option>
                                    <option value="2">الثاني</option>
                                    <option value="3">الثالث</option>
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
                                    <option value="شهادة اعدادية">شهادة اعدادية</option>
                                    <option value="شهادة معهد">شهادة معهد</option>
                                    <option value="شهادة دورة تأهيليه">شهادة دورة تأهيليه</option>


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
                                            <option value="{{$grad_year->id}}">{{$grad_year->start_year}}
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
                                <input value="{{old('primary_school_name')}}" name="primary_school_name" type="text"
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
                                    <option value="0">كلا</option>
                                    <option value="1">نعم</option>
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
                                    <option value="0">كلا</option>
                                    <option value="1">نعم</option>
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
                                        <option value="{{$channel->id}}">{{$channel->enrollment_channel}}</option>
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
                                <input value="{{old('total_score')}}" name="total_score" type="text"
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
                                    @for($i = 6; $i<=15; $i++)
                                        <option
                                            {{$i == 7 ? "selected": ""}} value="{{$i}}" {{old('number_of_modules_studied')}}>{{$i}}</option>
                                    @endfor
                                </select>
                                @if ($errors->has('number_of_modules_studied'))
                                    <div
                                        class="invalid-feedback">{{ $errors->first('number_of_modules_studied') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-2">
                                <label for="english_module_score" class="control-label">درجة اللغة الاجنبية</label>
                                <input type="text" class="form-control" name="english_module_score"
                                       id="english_module_score">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="score_average_before" class="control-label">المعدل قبل الاضافة</label>
                                <input value="{{old('score_average_before')}}" name="score_average_before"
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
                                <input value="{{old('score_average_after')}}" name="score_average_after"
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
                                <input value="{{old('first_name')}}" name="first_name" type="text"
                                       class="form-control @if ($errors->has('first_name')) is-invalid @endif
                                           " id="title"
                                       placeholder="">
                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="father_name" class="control-label">أسم الاب</label>
                                <input value="{{old('father_name')}}" name="father_name" type="text"
                                       class="form-control @if ($errors->has('father_name')) is-invalid @endif"
                                       id="father_name"
                                       placeholder="">
                                @if ($errors->has('father_name'))
                                    <div class="invalid-feedback">{{ $errors->first('father_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="middle_name" class="control-label">أسم الجد</label>
                                <input value="{{old('middle_name')}}" name="middle_name" type="text"
                                       class="form-control @if ($errors->has('middle_name')) is-invalid @endif"
                                       id="middle_name"
                                       placeholder="">
                                @if ($errors->has('middle_name'))
                                    <div class="invalid-feedback">{{ $errors->first('middle_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="last_name" class="control-label">أسم أب الجد</label>
                                <input value="{{old('last_name')}}" name="last_name" type="text"
                                       class="form-control @if ($errors->has('last_name')) is-invalid @endif"
                                       id="last_name"
                                       placeholder="">
                                @if ($errors->has('last_name'))
                                    <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="surname" class="control-label">اللقب</label>
                                <input value="{{old('surname')}}" name="surname" type="text"
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
                                <input value="{{old('date_of_birth')}}" name="date_of_birth" type="text"
                                       class="d_date form-control @if ($errors->has('date_of_birth')) is-invalid @endif"
                                       id="date_of_birth"
                                       placeholder="">
                                @if ($errors->has('date_of_birth'))
                                    <div class="invalid-feedback">{{ $errors->first('date_of_birth') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="place_of_birth" class="control-label">محل الولادة</label>
                                <input value="{{old('place_of_birth')}}" name="place_of_birth" type="text"
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
                                    <option value="0">ذكر</option>
                                    <option value="1">انثى</option>
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
                                    <option value="0">اعزب</option>
                                    <option value="1">متزوج</option>
                                </select>
                                @if ($errors->has('social_status'))
                                    <div class="invalid-feedback">{{ $errors->first('social_status') }}</div>
                                @endif
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="national_id_number" class="control-label">رقم الهوية</label>
                                <input value="{{old('national_id_number')}}" name="national_id_number" type="text"
                                       class="form-control @if ($errors->has('national_id_number')) is-invalid @endif"
                                       id="national_id_number"
                                       placeholder="">
                                @if ($errors->has('national_id_number'))
                                    <div class="invalid-feedback">{{ $errors->first('national_id_number') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="national_id_issue_date" class="control-label">تأريخ الاصدار</label>
                                <input value="{{old('national_id_issue_date')}}" name="national_id_issue_date"
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
                                <input value="{{old('national_id_issuer')}}" name="national_id_issuer" type="text"
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
                                <input value="{{old('certificate_of_iraqi_nationality')}}"
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
                                <input value="{{old('certificate_of_iraqi_nationality_issue_date')}}"
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
                                <input value="{{old('certificate_of_iraqi_nationality_issuer')}}"
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
                                        <option value="{{$town->id}}">{{$town->town_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('city'))
                                    <div class="invalid-feedback">{{ $errors->first('city') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="town" class="control-label">القضاء</label>
                                <input value="{{old('town')}}"
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
                                <input value="{{old('township')}}"
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
                                <input value="{{old('neighbor')}}"
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
                                <input value="{{old('district_no')}}"
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
                                <input value="{{old('side_street_no')}}"
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
                                <input value="{{old('house_number')}}"
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
                                <input value="{{old('phone')}}"
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
                                <input value="{{old('house_phone_no')}}"
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
                                <input value="{{old('email')}}"
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
                                <input value="{{old('near_point')}}"
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
                                <input value="{{old('ministry_name')}}"
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
                                <input value="{{old('department')}}"
                                       name="department" type="text"
                                       class="form-control @if ($errors->has('department')) is-invalid @endif"
                                       id="ministry_name"
                                       placeholder="">
                                @if ($errors->has('department'))
                                    <div class="invalid-feedback">{{ $errors->first('department') }}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="work_place" class="control-label">محل العمل</label>
                                <input value="{{old('work_place')}}"
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
                                <input value="{{old('career_title')}}"
                                       name="career_title" type="text"
                                       class="form-control @if ($errors->has('career_title')) is-invalid @endif"
                                       id="career_title"
                                       placeholder="">
                                @if ($errors->has('career_title'))
                                    <div class="invalid-feedback">{{ $errors->first('career_title') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="tile-footer">
                                <button id="submit_form" class="btn btn-success" type="button"><i
                                        class="fa fa-fw fa-lg fa-check-circle"></i>أضافة قيد الطالب
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
    <script type="text/javascript" src="{{asset('js/plugins/select2.min.js')}}"></script>
    <!-- select class modal -->
    <script>
        $(document).ready(function () {

            $('#enrollment_number').select2({
                dir: "rtl",
                dropdownCssClass: 'dbselect'
            });

            $('#score_average_after, #score_average_before').prop('', true);
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

                var total = parseInt($('#total_score').val());
                var english_module_score = parseInt($('#english_module_score').val());
                var number = parseInt($('#number_of_modules_studied').children("option:selected").val());
                var has_institution_certificate = $('#has_institution_certificate').children("option:selected").val();
                var has_english_module = $('#has_english_module').children("option:selected").val();
                var exam_attempt_number = $('#exam_attempt_number').children("option:selected").val();
                var score_average_before = 0;
                var score_average_after = 0;

                if (has_english_module === '1') {
                    if (english_module_score >= 50 && english_module_score <= 100) {
                        var en = english_module_score * 0.08;
                        total = parseInt(total) + en;
                    }
                }

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
                'student_id': {
                    presence: true,
                    numericality: true
                },
                'enrollment_number': {
                    presence: true,
                },
                'class_id': {
                    presence: true,
                },
                'exam_number': {
                    presence: false,
                },
                'exam_attempt_number': {
                    presence: false,
                },
                'primary_school_specialty_general_id': {
                    presence: true,
                },
                'application_certificate': {
                    presence: false,
                },
                'primary_school_graduation_year': {
                    presence: false,
                },
                'primary_school_name': {
                    presence: true,
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
                        notGreaterThanOrEqualTo: "\^ يجب ان يكون المجموع من 0 الى 800.",
                    }
                },
                'number_of_modules_studied': {
                    presence: false,
                    numericality: true
                },
                'english_module_score': {
                    numericality: {
                        onlyInteger: false,
                        greaterThanOrEqualTo: 50,
                        lessThanOrEqualTo: 100,
                        notGreaterThanOrEqualTo: "\^ الدرجة اقل من 50.",
                        notLessThanOrEqualTo: "\^ الدرجة اعلى من 100."
                    }
                },
                'score_average_before': {
                    presence: false,
                    numericality: {
                        onlyInteger: false,
                        greaterThanOrEqualTo: 50,
                        lessThanOrEqualTo: 100,
                        notGreaterThanOrEqualTo: "\^ معدل الطالب لا يسمح له بالتسجيل.",
                        notLessThanOrEqualTo: "\^ يجب ان لايتجاوز المعدل 100%."
                    }
                },
                'score_average_after': {
                    presence: true,
                    numericality: {
                        onlyInteger: false,
                        greaterThanOrEqualTo: 50,
                        lessThanOrEqualTo: 100,
                        notGreaterThanOrEqualTo: "\^ معدل الطالب لا يسمح له بالتسجيل.",
                        notLessThanOrEqualTo: "\^ يجب ان لايتجاوز المعدل 100%."
                    }
                },
                'has_institution_certificate': {
                    presence: false,
                },
                'has_english_module': {
                    presence: false,
                },
                'enrollment_channel_id': {
                    presence: false,
                },
                'first_name': {
                    presence: true,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'father_name': {
                    presence: true,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'middle_name': {
                    presence: true,
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
                    presence: true,
                    datetime: {dateOnly: true, notValid: '\^ يجب ادخال تأريخ صحيح.'},
                },
                'place_of_birth': {
                    presence: false,
                },
                'gender': {
                    presence: false,
                },
                'national_id_number': {
                    presence: true,
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
                    numericality: true
                },
                'certificate_of_iraqi_nationality_issue_date': {
                    datetime: {dateOnly: true, notValid: '\^ يجب ادخال تأريخ صحيح.'},
                },
                'certificate_of_iraqi_nationality_issuer': {
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'city': {
                    presence: true,
                },
                'town': {
                    presence: false,
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
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
                    numericality: true,
                    format: {
                        pattern: "^[0][7][789]{1}[0-9]{8}$",
                        flags: "i",
                        message: '\^ يجب ادخال رقم موبايل صحيح'
                    }
                },
                'house_phone_no': {
                    numericality: true
                },
                'email': {
                    presence: false,
                    email: true
                },
                'near_point': {
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'ministry_name': {
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'department': {
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'work_place': {
                    format: {
                        pattern: "^[a-zA-Zأ-ي_ ء]*$",
                        flags: "i",
                        message: "\^ يجب ادخال نصوص فقط."
                    }
                },
                'career_title': {
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
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imageUpload").change(function () {
                readURL(this);
            });

            function fill_datatable() {
                var formData = $('#course_select_form').serializeArray();
                var data = [];
                for (var i = 0; i < formData.length; i++) {
                    data[formData[i]["name"]] = formData[i]["value"];
                }
                data = Object.assign({}, data);
                var url = "{{route('class.show.info')}}";

                var table = $('#sampleTable').DataTable({
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
                    select: true,
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
                $('#sampleTable tbody').on('click', '#btn-select-class', function () {
                    var data = table.row($(this).parents('tr')).data();
                    $('#class_id').val(data.class_id);

                    var tr = "<td>" + data.academic_year + "</td>" +
                        "<td>" + data.college_department + "</td>" +
                        "<td>" + data.level_group + "</td>" +
                        "<td>" + data.shift + "</td>" +
                        "<td>" + data.type + "</td>" +
                        "<td>" + data.batch + "</td>";

                    $('#selected_course_container table tbody tr').empty();
                    $('#selected_course_container table tbody tr').append(tr);
                    $('#select_course_modal').modal('hide');
                });
            }

            function notify(message) {
                swal.fire({
                    type: 'success',
                    title: message,
                    showConfirmButton: true,
                    timer: 5000
                })
            }

            @if(Session::has('success'))
            notify("{{Session::get('success')}}");
            @php
                Session::forget('success');
            @endphp
            @endif
            fill_datatable();
            $('#course_select_form #college_id').on('change', function (e) {
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
            $('#course_select_form #department_id, #course_select_form #college_id').on('change', function (e) {
                var url = "{{route('class.show.level')}}";
                var college_id = $("#course_select_form #college_id").val();
                var department_id = $("#course_select_form #department_id").val();
                var data = {
                    college_id: college_id,
                    department_id: department_id
                };
                var level_id = $('#course_select_form #level_id');
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
            $('#academic_year_id,#college_id,#department_id, #level_id,#type_id, #shift_id, #time_id, #batch_id, #group_id').on('change', function () {
                $('#sampleTable').DataTable().destroy();
                fill_datatable()
            });
            $('#primary_school_specialty_general_id').on('change', function (event) {
                event.preventDefault();
                var id = $(this).val();
                var url = "{{route('specialty.index')}}";
                var data = {primary_school_specialty_general_id: id};
                $('#primary_school_specialty_special_id').find('option').not(':first').remove();
                axios.post(url, data)
                    .then(function (response) {
                        $.each(response.data, function (i, val) {
                            $('#primary_school_specialty_special_id').append($("<option>", {
                                value: val.id,
                                text: val.special
                            }));
                        });
                    })
                    .catch(function (error) {

                    });
            })
        });
    </script>


@endsection
