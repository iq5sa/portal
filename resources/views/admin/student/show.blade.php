@extends('admin.layouts.app')
@section('styles')
    <style>

    </style>
@endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> عرض قيد الطالب </h1>
            <p>يمكنك من خلال هذه الواجه التعرف على بيانات طالب محدد وتعديل البيانات.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="student_logo">
                        <img class="profile-user-img img-fluid rounded-circle mx-auto d-block"
                             src="{{$std->photo != null ? asset('storage/'.$std->photo) : ($std->gender == 0 ? asset('images/student.svg') : asset('images/woman.svg'))}}"
                             alt="User profile picture">
                        <div class="status">
                            {{$std->academic_status_name}}
                        </div>
                        @can('تعديل قيد الطالب')
                        <?php if ($chk=="yes"): ?>
                          <div class="d-flex justify-content-center align-items-center mt-2">
                              <a href="{{route('students.edit',$std->std_id)}}"
                                 class="btn btn-outline-primary btn-sm">تعديل</a>
                          </div>
                        <?php endif; ?>
                        @endcan
                    </div>
                    <h3 class="profile-username text-center">{{$std->first_name!= null?$std->first_name.' '.$std->father_name.' '.$std->middle_name.' '.$std->last_name.' '.$std->surname:$std->full_name}}</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <b>الرقم الجامعي</b> <a class="pull-left text-aqua">{{$std->student_id}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>أمر القبول</b> <a class="pull-left text-aqua">{{$student_role->number}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>سنة القبول</b> <a
                                class="pull-left text-aqua">{{$std->ac_start_year.'-'.$std->ac_end_year}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>الكلية</b> <a class="pull-left text-aqua">{{$std->college_name}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>القسم</b> <a
                                class="pull-left text-aqua">{{$std->department_name != null ? $std->department_name : "-"}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>المرحلة</b> <a class="pull-left text-aqua">{{$std->level}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>نوع الدراسة</b> <a class="pull-left text-aqua">{{$std->shift}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="nav-tabs-custom" style="position: relative">
                <ul class="nav nav-tabs">
                    <li class="nav-item active">
                        <a class="nav-link active" id="student_statuses-tab" data-toggle="tab" href="#student_statuses"
                           role="tab" aria-controls="student_statuses" aria-selected="false">السيرة الاكاديمية</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" id="home-tab" data-toggle="tab" href="#profile"
                           role="tab" aria-controls="home" aria-selected="true">المعلومات الشخصية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#fees"
                           role="tab" aria-controls="profile" aria-selected="false">الدفوعات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="employee-tab" data-toggle="tab" href="#attendance"
                           role="tab" aria-controls="employee" aria-selected="false">الغيابات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="employee-tab" data-toggle="tab" href="#documents"
                           role="tab" aria-controls="employee" aria-selected="false">الوثائق</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active py-2 px-2" id="student_statuses">
                        <div class="timeline-header no-border">
                            <div id="timeline_list">

                                <ul class="timeline timeline-inverse">
                                    @foreach($statuses as $status)
                                        <li class="time-label">
                                            @php
                                                $date = new \Illuminate\Support\Carbon($status->status_date);
                                                $date_str = $date->format('Y-m-d');
                                            @endphp
                                            <span class="bg-blue text-white">{{$date_str}}</span>
                                        </li>
                                        <li>
                                            <i class="{{$status->color }} {{$status->icon}} text-white"></i>
                                            <div class="timeline-item">
                                                <span class="time">
                                                    @if($status->path != null)
                                                        <a class="defaults-c text-right"
                                                           href="{{asset('storage/'.$status->path)}}"><i
                                                                class="fa fa-download"></i> تحميل الامر الاداري </a>
                                                    @else
                                                        <a class="defaults-c text-right text-danger"
                                                           href="#"><i
                                                                class="fa fa-spinner"></i> يضهر الامر الاداري عند تغيير حالة الطالب </a>
                                                    @endif

                                                </span>
                                                <h3 class="timeline-header text-aqua">
                                                    @if($status->has_hold_subject == 1)
                                                        {{$status->status_name}} <span class="badge badge-danger">عبور من العام الماضي</span>
                                                    @elseif($status->has_fail == 1)
                                                        {{$status->status_name}} <span class="badge badge-danger">راسب من العام الماضي</span>
                                                    @else
                                                        {{$status->status_name}}
                                                    @endif
                                                    @if($status->aboor_passing_status == 1)
                                                        <i class="fa fa-lock"></i>
                                                    @elseif($status->aboor_passing_status == 2)
                                                        <i class="fa fa-unlock"></i>
                                                    @endif
                                                </h3>
                                                <div class="timeline-body">
                                                    <div class="tshadow bozero mb-3">
                                                        <div class="table-responsive-sm px-2">
                                                            <table class="table table-hover table-sm mb-0 user_info">
                                                                <tbody>
                                                                <tr class="border_less_tr">
                                                                    <td>وصف حول حالة الطالب</td>
                                                                    <td>
                                                                        @if($status->number == null)
                                                                            <i class="fa fa-spinner"></i>
                                                                        @elseif($status->number != null && $status->status_description == null)
                                                                            لايوجد
                                                                        @elseif($status->status_description != null)
                                                                            {{$status->status_description}}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>السنة الدراسية</td>
                                                                    <td>{{$status->end_year.'-'.$status->start_year}}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>رقم الامر الاداري</td>
                                                                    <td>{!!$status->number != null ? $status->number : '<i class="fa fa-spinner"></i>'!!}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>تأريح الامر الاداري</td>
                                                                    <td>
                                                                        {!!$status->number != null ? $status->admin_date : '<i class="fa fa-spinner"></i>'!!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>وصف الامر الاداري</td>
                                                                    <td>
                                                                        {!!$status->number != null ? $status->admin_des : '<i class="fa fa-spinner"></i>' !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>كلية</td>
                                                                    <td>
                                                                        {{$status->college_name}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>القسم</td>
                                                                    <td>
                                                                        {{$status->department_name != null ? $status->department_name : 'لايوجد'}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>المرحلة</td>
                                                                    <td>
                                                                        {{$status->level}}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>نوع الدراسة</td>
                                                                    <td>
                                                                        {{$status->shift}}
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </li>
                                    @endforeach
                                    <li><i class="fa fa-clock-o bg-gray"></i></li>
                                </ul>
                            </div>


                            <!-- <h2 class="page-header"> </h2> -->

                        </div>
                    </div>
                    <div class="tab-pane fade show py-2 px-2" id="profile">
                        <div class="tshadow bozero mb-3">
                            <div class="table-responsive-sm px-2">
                                <table class="table table-hover table-sm mb-0 user_info">
                                    <tbody>
                                    <tr class="border_less_tr">
                                        <td>تأريخ التسجيل</td>
                                        <td>{{$std->date_registered}}</td>
                                    </tr>
                                    <tr>
                                        <td>تأريخ الولادة</td>
                                        <td>{{$std->date_of_birth}}</td>
                                    </tr>
                                    <tr>
                                        <td>محل الولادة</td>
                                        <td>
                                            {{$std->place_of_birth}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>الجنس</td>
                                        <td>{{$std->gender == 0 ? "ذكر":"أنثى"}}</td>
                                    </tr>
                                    <tr>
                                        <td>الحالة الاجتامعية</td>
                                        <td>@if($std->social_status == 0)
                                                اعزب
                                            @elseif($std->social_status == 1)
                                                متزوج
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>معلومات الهويه/البطاقة الوطنية</td>
                                        <td>{{$std->national_id_issuer}}-{{$std->national_id_issue_date}}
                                            -<b>{{$std->national_id_number}}</b></td>
                                    </tr>
                                    <tr>
                                        <td>معلومات شهادة الجنسية</td>
                                        <td>{{$std->certificate_of_iraqi_nationality_issuer}}
                                            -{{$std->certificate_of_iraqi_nationality_issue_date}}
                                            -<b>{{$std->certificate_of_iraqi_nationality}}</b></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tshadow bozero mb-3">
                            <h3 class="pagetitleh2">معلومات شهادة الاعدادية</h3>
                            <div class="table-responsive-sm px-2">
                                <table class="table table-hover table-sm mb-0 user_info">
                                    <tbody>
                                    <tr class="border_less_tr">
                                        <td>الرقم الامتحاني</td>
                                        <td>{{$std->exam_number}}</td>
                                    </tr>
                                    <tr>
                                        <td>الدور الذي نجح منه الطالب</td>
                                        <td>{{$std->exam_attempt_number == 0 ? "الاول" : ($std->exam_attempt_number == 1 ? "الثاني" : "الثالث")}}</td>
                                    </tr>
                                    <tr>
                                        <td>الفرع</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>الشهادة التي قدم بها</td>
                                        <td>{{$std->application_certificate}}</td>
                                    </tr>
                                    <tr>
                                        <td>سنة التخرج من الاعدادية</td>
                                        <td>{{$std->primary_school_graduation_year}}</td>
                                    </tr>
                                    <tr>
                                        <td>المدرسة</td>
                                        <td>{{$std->primary_school_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>مجموع الدرجات</td>
                                        <td>{{$std->total_score}}</td>
                                    </tr>
                                    <tr>
                                        <td>عدد المواد التي درسها</td>
                                        <td>{{$std->number_of_modules_studied}}</td>
                                    </tr>
                                    <tr>
                                        <td>عدد المواد التي درسها</td>
                                        <td>{{$std->number_of_modules_studied}}</td>
                                    </tr>
                                    <tr>
                                        <td>المعدل قبل الاضافة</td>
                                        <td>{{$std->score_average_before}}</td>
                                    </tr>
                                    <tr>
                                        <td>المعدل بعد الاضافة</td>
                                        <td>{{$std->score_average_after}}</td>
                                    </tr>
                                    <tr>
                                        <td>هل لديه شهادة معهد؟</td>
                                        <td>{{$std->has_institution_certificate == 0 ? "نعم": "كلا"}}</td>
                                    </tr>
                                    <tr>
                                        <td>هل درس لغة اجنبية؟</td>
                                        <td>{{$std->has_english_module == 0 ? "نعم": "كلا"}}</td>
                                    </tr>
                                    <tr>
                                        <td>قناة القبول</td>
                                        <td>{{$std->enrollment_channel}}</td>
                                    </tr>
                                    <tr>
                                        <td>هل لديه شهادة معهد؟</td>
                                        <td>{{$std->has_institution_certificate == 0 ? "نعم": "كلا"}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tshadow bozero mb-3">
                            <h3 class="pagetitleh2">معلومات السكن والاتصال</h3>
                            <div class="table-responsive-sm px-2">
                                <table class="table table-hover table-sm mb-0 user_info">
                                    <tbody>
                                    <tr class="border_less_tr">
                                        <td>عنوان السكن الحالي</td>
                                        <td>{{'المحافظة: '.$std->city.'/القضاء: '.$std->town.'/الناحية: '.$std->township.'/الحي'.$std->neighbor.'/المحلة: '.$std->district_no.'/الزقاق: '.$std->side_street_no.'/الدار: '.$std->house_number}}</td>
                                    </tr>
                                    <tr>
                                        <td>أقرب نقطة دالة</td>
                                        <td>{{$std->near_point}}</td>
                                    </tr>
                                    <tr>
                                        <td>رقم الموبايل</td>
                                        <td>{{$std->phone}}</td>
                                    </tr>
                                    <tr>
                                        <td>البريد الالكتروني</td>
                                        <td>{{$std->email}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tshadow bozero mb-3">
                            <h3 class="pagetitleh2">معلومات اخرى</h3>
                            <div class="table-responsive-sm px-2">
                                <table class="table table-hover table-sm mb-0 user_info">
                                    <tbody>
                                    <tr class="border_less_tr">
                                        <td>معلومات الوضيفة</td>
                                        <td>{{'الوزارة: '.$std->ministry_name.'/الدائرة: '.$std->department.'/محل العمل: '.$std->work_place.'/المنصب: '.$std->career_title}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show py-2 px-2" id="fees"></div>
                    <div class="tab-pane fade show py-2 px-2" id="attendance"></div>
                    <div class="tab-pane fade show py-2 px-2" id="documents"></div>
                </div>
            </div>
        </div>
        {{--<div class="tab-pane fade" id="profile" role="tabpanel"
                         aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <label>العنوان</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{$std->city}} - {{$std->town}} - {{$std->township}}
                                    - {{$std->neighbor}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>رقم الهاتف</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{$std->phone}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>البريد الالكتروني</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{$std->email}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>أقرب نقطة دالة</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{$std->near_point}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="employee" role="tabpanel"
                         aria-labelledby="employee-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <label>الوزارة</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{$std->ministry_name}} </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>الدائرة</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{$std->department}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>محل العمل</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{$std->work_place}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>العنوان الوظيفي</label>
                            </div>
                            <div class="col-md-6">
                                <p>{{$std->career_title}}</p>
                            </div>
                        </div>
                    </div>
                    --}}{{--Student_Statuses--}}{{--
                    <div class="tab-pane fade" id="student_statuses" role="tabpanel"
                         aria-labelledby="student_statuses-tab">
                        <div class="row" style="font-size: 16px;">
                            <ul class="timeline">
                                @foreach($statuses as $stat)
                                    <li>

                                                        <span class="font-weight-bold text-info">{{$stat->start_year.'-'.$stat->end_year.'  '.$stat->academic_status_name}} <div
                                                                    class="badge badge-success">{{$stat->is_active==1?'حالة الطالب الحالية':''}}</div></span>
                                        <p>رقم امر تغيير الحالة :{{$stat->number.'      '}} <a
                                                    href="{{asset('storage/'.$stat->path)}}"> اضغط هنا
                                                لتحميل الامر</a></p>
                                        <div class="font-weight-bold text-danger">تم تحديث الحالة في
                                            :{{$stat->updated_at}}</div>
                                    </li>
                                @endforeach

                                --}}{{--  <li>
                                      <span class="font-weight-bold text-info">اعلان عن موعد المقابلة</span>
                                      <p>يرجى زيارة الموقع الالكتروني لمعرفة تفاصيل وموعد المقابلة.</p>
                                      <div class="font-weight-bold text-danger">2019-08-01 19:18:39</div>
                                  </li>--}}{{--
                            </ul>
                        </div>
                    </div>--}}
    </div>
@endsection
@section('scripts')
    <script>
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var x = $(e.target).parents('li').get(0);
            $(x).addClass('active');
            var y = $(e.relatedTarget).parents('li').get(0);
            $(y).removeClass('active');
        })
    </script>
@endsection
