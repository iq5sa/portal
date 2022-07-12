@extends('admin.layouts.app')
@include('admin.student.popup.enrolmment_select')
@section('styles')
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> الاوامر الادارية </h1>
            <p>ادارة الاوامر الاداية الخاصة بالطلبة.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>جميع الاوامر الادارية</span>
                    <div>
                        <button data-toggle="modal" data-target="#enrollment-show" type="button"
                                class="btn btn-success"><i class="fa fa-plus"></i>اضافة امر اداري
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="student_table" class="table table-bordered table-hover display">
                            <thead>
                            <tr>

                                <th class="text-nowrap">رقم الامر</th>
                                <th class="text-nowrap">تاريخ الامر</th>
                                <th class="text-nowrap">السنة الاكاديمية</th>
                                <th class="text-nowrap">نوع الامر</th>
                                <th class="text-nowrap">رابط الملف</th>
                                <th class="text-nowrap">الوصف</th>
                                <th class="text-nowrap">ادارة</th>
                                <th class="text-nowrap">حالة الاظهار</th>

                            </tr>
                            </thead>
                            <tbody>


                            @foreach($orders as $order)

                                <tr>
                                    <td> {{$order->number}}</td>
                                    <td> {{$order->date}}</td>
                                    <td> {{$order->start_year}}-{{$order->end_year}}</td>
                                    <td>{{$order->name}}</td>
                                    <td><a href="{{asset('storage/' . $order->path)}}" target="_blank">عرض الملف</a>
                                    </td>

                                    <td> {{$order->description}}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                            <a href="{{route('orders.edit',$order->admin_id)}}" class="btn btn-info"
                                               type="button">تعديل</a>
                                            <a href="{{route('orders.destroy',$order->admin_id)}}"
                                               class="btn btn-danger"
                                               type="button">حذف</a>
                                        </div>
                                    </td>

                                    <td>
                                        @if($order->o_is_active==1)
                                            <a href="{{route('orders.setactive',$order->admin_id)}}"
                                               class="btn btn-success btn-sm">مفعل</a>
                                        @else
                                            <a href="{{route('orders.setactive',$order->admin_id)}}" class="btn btn-secondary btn-sm">
                                                غير مفعل</a>
                                        @endif
                                        {{--<form class="submit_form" method="POST" action="{{route('orders.setactive')}}">
                                            @csrf
                                            <input type="hidden" name="hidden_id" value="{{$order->admin_id}}">

                                            <div class="toggle lg">
                                                <label>
                                                    <input type="checkbox" class="cheo"
                                                           value="" {{$order->o_is_active==1?'checked':''}} ><span
                                                        class="button-indecator"></span>
                                                </label>
                                            </div>
                                        </form>--}}

                                    </td>
                                </tr>
                            @endforeach
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
    <script type="text/javascript">
        $(document).ready(function () {
            var student_table = $('#student_table').DataTable({
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


                'order': [[1, 'asc']],
            });

        });

        /*$(document).ready(function () {
            $('.cheo').change(function () {
                var form = $(this).parents('form.submit_form').get(0);
                form.submit();
            });

        });*/
    </script>
@endsection
