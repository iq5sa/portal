@extends('admin.layouts.app')
@section('styles')
    <style>
        span.inner {
            color: green;
        }

        span.outer {
            color: red;
            text-decoration: line-through;
        }
    </style>
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> البحث عن الوصولات المدخلة </h1>
            <p>يمكنك من خلال هذه الواجه البحث عن وصل عن طريق اسم الطالب او رقم الوصل.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('payments.search')}}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="payment_id">رقم الوصل</label>
                        <input value="{{request('payment_id')}}" id="payment_id" name="payment_id" type="number" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="payment_date">تأريخ الوصل</label>
                        <input value="{{request('payment_date') != null ? request('payment_date'): Carbon\Carbon::now()->format('Y-m-d')}}" id="payment_date" name="payment_date" type="text" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="payment_method">طريقة الدفع</label>
                        <select id="payment_method" name="payment_method" class="form-control">
                            <option value="">اختر</option>
                            <option {{request('payment_method') == 1 ? 'selected':''}} value="1">كاش</option>
                            <option {{request('payment_method') == 2 ? 'selected':''}} value="2">فيشه</option>
                            <option {{request('payment_method') == 3 ? 'selected':''}} value="3">صك سفجته</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="student_name">اسم الطالب</label>
                        <input value="{{request('student_name')}}" id="student_name" name="student_name" type="text" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <button id="search" class="btn btn-primary btn-block"><i class="fa fa-search"></i> بحث</button>
                    </div>
                </div>
            </form>

        </div>


            <div class="col-md-12">
                <div class="card shadow" id="add_class_card">
                    <div class="card-header">
                        البحث عن الوصولات
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table-responsive-md">
                            <table class="table table-bordered table-hover" id="sampleTable" style="width: 100%">
                                <thead>
                                <tr>
                                    <th class="text-nowrap">رقم الوصل</th>
                                    <th class="text-nowrap">تأريخ الوصل</th>
                                    <th class="text-nowrap">أسم الطالب</th>
                                    <th class="text-nowrap">المبلغ</th>
                                    <th class="text-nowrap">نوع الوصل</th>
                                    <th class="text-nowrap">الكلية</th>
                                    <th class="text-nowrap">القسم</th>
{{--                                    <th class="text-nowrap">المرحلة</th>--}}
                                    <th class="text-nowrap">نوع الدراسة</th>
                                    <th class="text-nowrap">عرض</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (!$payments->isEmpty())
                                    @foreach($payments as $payment)
                                        <tr
                                            @if ($payment->revert == 1)
                                            style="background-color:#f4433642"
                                            @endif
                                        >
                                            <td>{{$payment->payment_id}}</td>
                                            <td>{{$payment->payment_date}}</td>
                                            <td>{{$payment->first_name!= null ? $payment->first_name.' '.$payment->father_name.' '.$payment->middle_name.' '.$payment->last_name.' '.$payment->surname : $payment->full_name}}</td>
                                            <td>

                                                @if ($payment->revert == 1)
                                                    <span class="outer">{{number_format($payment->payment_amount)}}</span>
                                                @else
                                                    {{number_format($payment->payment_amount)}}
                                                @endif
                                                </td>
                                            <td>
                                                @if ($payment->payment_method == 1)
                                                    كاش
                                                @elseif ($payment->payment_method == 2)
                                                    فيشة
                                                @elseif ($payment->payment_method == 3)
                                                    صك سفتجة
                                                @endif
                                            </td>
                                            <td>{{$payment->college_name}}</td>
                                            <td>{{$payment->department_name}}</td>
{{--                                            <td>{{$payment->level}}</td>--}}
                                            <td>{{$payment->shift}}</td>
                                            <td><a href="{{route('fees.details',$payment->student_id)}}" class="btn btn-sm  btn-success"><i class="fa fa-eye"></i></a> </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                                <tr>
                                    <th colspan="2">المبلغ المدفوع الكلي:</th>
                                    <th colspan="7">{{number_format($total)}}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.number.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.buttons.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jszip.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/buttons.html5.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/buttons.print.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#payment_date').datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true
            });


            $('#sampleTable').DataTable({
                paging: true,
                bFilter: true,
                dom: '<<"d-flex justify-content-between"<l><f><"d-flex justify-content-end"<B>>>t<"d-flex justify-content-between"<p><i>>r>',
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
                    { extend: 'excel', className: 'btn btn-success btn-sm' },
                    {
                        extend: 'print',
                        text: 'طباعة',
                        className: 'btn btn-danger btn-sm',
                        customize: function (win) {
                            $(win.document.body).find('table').addClass('display').css('font-size', '9px',).css('direction','rtl').addClass('text-right');
                            $(win.document.body).find('tr:nth-child(odd) td').each(function(index){
                                $(this).css('background-color','#D0D0D0');
                            });
                            $(win.document.body).find('h1').css('text-align','center');
                        }
                    },
                ],
            });



        });
    </script>
@endsection


















