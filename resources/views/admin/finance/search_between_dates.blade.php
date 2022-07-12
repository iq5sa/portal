@extends('admin.layouts.app')
@section('styles')
    <style>
        th {
            white-space: nowrap;
        }
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
            <h1><i class="fa fa-wpforms"></i> كشف الوصلات بين تأريخين </h1>
            <p>يمكنك من خلال هذه الواجه الحصول على كشف حسب التأريخ.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="{{route('payments.search_between_dates')}}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="payment_date_start">ادخال التأريخ البدايه</label>
                        <input
                            value="{{request('payment_date_start') != null ? request('payment_date_start') : old('payment_date_start')}}"
                            id="payment_date_start" name="payment_date_start" type="text"
                            class="form-control @error('payment_date_start') is-invalid @enderror">
                        @error('payment_date_start')
                        <div class="invalid-feedback">{{ $errors->first('payment_date_start') }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="payment_date_end">ادخال التأريخ النهاية</label>
                        <input
                            value="{{request('payment_date_end') != null ? request('payment_date_end') : old('payment_date_end')}}"
                            id="payment_date_end" name="payment_date_end" type="text"
                            class="form-control @error('payment_date_end') is-invalid @enderror">
                        @error('payment_date_end')
                        <div class="invalid-feedback">{{ $errors->first('payment_date_end') }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <button id="search" class="btn btn-primary btn-block"><i class="fa fa-search"></i> البحث بين
                            تأريخين
                        </button>
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
                                        <td><a href="{{route('fees.details',$payment->student_id)}}"
                                               class="btn btn-sm  btn-success"><i class="fa fa-eye"></i></a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="2">المبلغ المدفوع الكلي:</th>
                                <th colspan="7">{{number_format($total)}}</th>
                            </tr>
                            </tfoot>
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
            $('#payment_date_start').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
            });
            $('#payment_date_end').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
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


















