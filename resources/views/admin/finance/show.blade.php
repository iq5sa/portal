@extends('admin.layouts.app')
@include('admin.finance.popup.add_payment')
@include('admin.finance.popup.add_discount')
@include('admin.finance.popup.edit_discount')
@include('admin.finance.popup.add_fee')
@include('admin.finance.popup.edit_fee')
@include('admin.finance.popup.edit_payment')
@include('admin.finance.popup.add_id_card_fees')
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
            <h1><i class="fa fa-wpforms"></i> أدخال ايراد جديد </h1>
            <p>يمكنك من خلال هذه الواجه التعرف على بيانات الطلبة.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow" id="add_class_card">
                <div class="card-header d-flex justify-content-between">
                    <h4>ايرادات الطالب</h4>
                  {{--@can('delete_payment_record')--}}
                    <button data-toggle="modal" data-target="#add_fee" @cannot('اضافة مبلغ قسط') disabled @endcannot
                        class="btn btn-outline-success add_fee"><i class="fa fa-plus"></i>اضافة مبلغ القسط
                    </button>
                    {{--@endcan--}}

                    @can("اضافة هوية")
                    <button data-toggle="modal" data-target="#add_id_card_fees_show" @cannot('اضافة مبلغ قسط') disabled @endcannot
                        class="btn btn-outline-primary"><i class="fa fa-plus"></i>اضافة مبلغ الهوية
                    </button>

                    @endcan
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <img width="115" height="115" class="round5"
                             src="{{$std->photo != null ? asset('storage/'.$std->photo) : ($std->gender == 0 ? asset('images/student.svg') : asset('images/woman.svg'))}}"
                             alt="No Image">
                        <table class="table table-hover  table-sm ml-3">
                            <tbody>
                            <tr>
                                <th class="bozero">الاسم</th>
                                <td class="bozero">{{$std->first_name!= null?$std->first_name.' '.$std->father_name.' '.$std->middle_name.' '.$std->last_name.' '.$std->surname:$std->full_name}}</td>

                                <th class="bozero">الكلية</th>
                                <td class="bozero">{{$std->college_name}}</td>
                            </tr>
                            <tr>
                                <th>الرقم الجامعي</th>
                                <td>{{$std->student_id}}</td>
                                <th>القسم</th>
                                <td>{{$std->department_name != null ? $std->department_name : "-"}}</td>
                            </tr>
                            <tr>
                                <th>الجنس</th>
                                <td>{{$std->gender == 0 ? "ذكر":"أنثى"}}</td>
                                <th>المرحلة</th>
                                <td> {{$std->level}}</td>
                            </tr>
                            <tr>
                                <th>رقم الهاتف</th>
                                <td>
                                    {{$std->phone}}
                                </td>
                                <th>نوع الدراسة</th>
                                <td>{{$std->shift}}
                                </td>
                            </tr>
                            <tr class="bg-dark text-white">
                                <th>الحالة الاكاديمية للطالب</th>
                                <td colspan="3">
                                    @if($std->has_hold_subject == 1)
                                        {{$std->academic_status_name}} <span class="badge badge-danger">عبور من العام الماضي</span>
                                    @elseif($std->has_fail == 1)
                                        {{$std->academic_status_name}} <span class="badge badge-danger">راسب من العام الماضي</span>
                                    @else
                                        {{$std->academic_status_name}}
                                    @endif
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mt-3">
                            <thead>
                            <tr>
                                <th>الاختيارات</th>
                                <th>العنوان</th>
                                <th> السنة الدراسية</th>
                                <th>المرحلة</th>
                                <th>المبلغ المطلوب</th>
                                <th>مبلغ التخفيض</th>
                                <th>المدفوع الكلي</th>
                                <th>المبلغ المتبقي</th>
                                <th>رقم الوصل</th>
                                <th>تأريخ الوصل</th>
                                <th>مبلغ الوصل</th>
                                <th>نوع الدفع</th>
                                <th>الملاحضات</th>
                                <th>الاعدادات</th>
                            </tr>
                            </thead>
                            <tbody>


                            @foreach($fees   as $value)

                                <?php if ($value['fee_name']=='القسط السنوي'): ?>
                                  <tr @if (($value['total_paid'] + $value['all_discount']) < $value['required_amount'])
                                      style="background-color:#f4433642"
                                      @elseif (($value['total_paid'] + $value['all_discount']) >= $value['required_amount'])
                                      style="background-color:rgba(36,244,0,0.26)"
                                      @endif>
                                <?php else: ?>
                                  <tr @if (($value['total_paid'] + $value['all_discount']) < $value['required_amount'])
                                      style="background-color:#ff11007a"
                                      @elseif (($value['total_paid'] + $value['all_discount']) >= $value['required_amount'])
                                      style="background-color:rgb(36, 244, 0)"
                                      @endif>
                                <?php endif; ?>

                                    <td class="d-flex">
                                        @if (($value['total_paid'] + $value['all_discount']) < $value['required_amount'])
                                            <button class="btn btn-outline-success btn-sm add_payment_btn"
                                                    @cannot('أدخال ايراد') disabled @endcannot title="اضافة قسط"
                                                    data-fees="{{$value['fees_id']}}"
                                            ><i class="fa fa-plus"></i></button>
                                        @endif
                                        <?php if ($value['fee_name']=='القسط السنوي'): ?>
                                          <button class="btn btn-outline-warning btn-sm add_dis_btn ml-1"
                                                  @cannot('تخفيض الاقساط') disabled @endcannot title="تخفيض الاقساط"
                                                  data-fees="{{$value['fees_id']}}" data-fullfees="{{$value['required_amount']}}"
                                          ><i class="fa fa-usd"></i></button>
                                        <?php endif; ?>

                                        <?php if ($value['fee_name']=='القسط السنوي'): ?>
                                            @can("تعديل مبلغ القسط")
                                          <button class="btn btn-outline-primary edit_fee btn-sm ml-1"
                                                  @cannot('اضافة مبلغ قسط') disabled @endcannot title="تعديل المبلغ المطلوب"
                                                  data-fees="{{$value['fees_id']}}" data-fullfees="{{$value['required_amount']}}"
                                          ><i class="fa fa-pencil"></i></button>
                                            @endcan
                                        <?php endif; ?>

                                    </td>

                                    <td>{{$value['fee_name']}}</td>
                                    <td>{{$value['academic_year']}}</td>
                                    <td>{{$value['level']}}</td>
                                    <td>{{number_format($value['required_amount'])}}</td>
                                    <td>
                                        <a href="#" @can('تعديل التخفيض') class="detail_discounts" @endcan
                                           data-discounts='{{json_encode($value["discount_details"])}}'>{{number_format($value['all_discount'])}}</a>
                                    </td>
                                    <td>{{number_format($value['total_paid'])}}</td>
                                    <td>{{number_format($value['total_due'])}}</td>
                                    <td colspan="7"></td>
                                </tr>

                                @foreach($value['payments'] as $payment)
                                    @if ($payment->payment_id != null)
                                        <tr>
                                            <td colspan="7"></td>
                                            <td><i class="fa fa-long-arrow-left"></i></td>
                                            <td>
                                                <a href="{{route("receipt.print2",["payment_id"=>$payment->payment_id])}}" data-toggle="tooltip" data-placement="top"
                                                   title="{{$payment->name}}">{{$payment->payment_id}}</a>
                                            </td>
                                            <td>{{$payment->payment_date}}</td>
                                            <td>
                                                @if($payment->revert == 1)
                                                    <span
                                                        class="outer">{{number_format($payment->payment_amount)}}</span>
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
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                       title="{{$payment->cheque_date}}--{{$payment->cheque_number}}">صك
                                                        سفتجة</a>
                                                @endif
                                            </td>
                                            <td>{{$payment->description}}</td>
                                            <td>
                                                <div class="btn-group btn-sm">
                                                    <button class="btn btn-outline-warning revert-btn"
                                                            @cannot('ابطال الايراد') disabled @endcannot
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="ابطال الوصل"
                                                            data-id="{{$payment->payment_id}}"
                                                            data-revert="{{$payment->revert == 1 ? 0: 1}}"><i
                                                            class="fa fa-refresh"></i>
                                                    </button>
                                                    @can('تعديل وصل قبض الاقساط')
                                                        <button
                                                            data-toggle="tooltip" data-placement="top" title="تعديل الوصل"
                                                            data-payment="{{json_encode($payment)}}"
                                                            class="btn btn-outline-primary edit_payment"
                                                            @cannot('تعديل ايراد') disabled @endcannot><i
                                                                class="fa fa-pencil"></i>
                                                        </button>
                                                    @endcan
                                                    @can('حذف وصولات القسط')
                                                    <button type="button" data-payment_id="{{$payment->programing_id}}"
                                                            data-toggle="tooltip" data-placement="top" title="حذف ايراد"
                                                            class="delete_payment btn btn-outline-danger"
                                                            @cannot('حذف ايراد') disabled @endcannot><i
                                                            class="fa fa-trash"></i>
                                                    </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach

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
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.serializejson.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.number.min.js')}}"></script>
<script type="text/javascript">

    function editDiscount(id){
        let getDetailsUrl = "/admin/ajax/fees/details/"+id;
        let discountNameField = $("#discount_name2");
        let discountAmountField = $("#discount_amount2");
        let discountDescriptionField = $("#discount_description2");
        let discountIdField = $("#discount_id");
        swal.close();
        $("#dis-edit").modal("show");

        axios.get(getDetailsUrl,{})
        .then(function (response){

            let data = response.data;

            let dis_amount = data[0].discount_amount;
            let dis_name = data[0].discount_name;
            let dis_desc = data[0].discount_description;

            discountNameField.val(dis_name);
            discountAmountField.val(dis_amount);
            discountDescriptionField.val(dis_desc);
            discountIdField.val(id);

        });

    }
function myfunction(id) {
      var url = "/admin/fees/delete/" + id;


      swal.fire({
          title: 'هل تريد الاستمرار؟',
          text: "حذف تخفيض الطالب.",
          type: 'question',
          confirmButtonText: 'نعم',
          cancelButtonText: 'لا',
          showConfirmButton: true,
          showCancelButton: true,
          showCloseButton: false,
          showLoaderOnConfirm: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#dc3545',
          allowEnterKey: false,
          allowEscapeKey: false,
          allowOutsideClick: false,
      }).then((result) => {
          if (result.value) {
              swal.fire(
                  {
                      title: 'جار التحميل...',
                      allowEnterKey: false,
                      allowEscapeKey: false,
                      allowOutsideClick: false,
                      onOpen: () => {
                          swal.showLoading();
                          axios.get(url, {})
                              .then(function (response) {
                                  swal.close();
                                  swal.fire({
                                      title: 'تم حذف تخفيض الطالب',
                                      text: "يرجى الانتظار جار اعادة تحميل الصفحة.",
                                      type: 'success',
                                      confirmButtonColor: '#d33',
                                  });
                                  setInterval(function () {
                                      window.location.reload(true);
                                  }, 3000);
                              })
                              .catch(function (error) {
                                  swal.close();
                                  // reset errors
                                  swal.fire({
                                      title: "خطأ",
                                      type: 'error',
                                      text: "عذرا لقد قمت بأدخال معلومات غير صحيحة!",
                                      confirmButtonColor: '#17a2b8',
                                  })

                              });
                      },
                  }
              )
          }
      });
}
</script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('#payment_date').datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true
            });
            $('#edit_payment_form #payment_date').datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true
            });
            $('#cheque_date').datepicker({
                format: "yyyy-mm-dd",
                todayHighlight: true
            });

            $('.add_payment_btn').click(function (event) {
                event.preventDefault();
                var fees_id = $(this).data('fees');
                $('#add_payment_form input[name=fees_id]').val(fees_id);
                $('#payment-show').modal('show');
            });

            $('.add_dis_btn').click(function (event) {
                event.preventDefault();
                var fees_id = $(this).data('fees');
                var fullfees = $(this).data('fullfees');
                $('#add_dis_form input[name=fees_id]').val(fees_id);
                $('#add_dis_form input[name=amount]').val(fullfees);
                $('#dis-show').modal('show');
            });

            $('.add_fee').click(function (event) {
                //event.preventDefault();
                //var fees_id = $(this).data('fees');
                //$('#add_payment_form input[name=fees_id]').val(fees_id);
                $('#add_fee').modal('show');
            });

            $('.edit_fee').click(function (event) {
                event.preventDefault();
                var fees_id = $(this).data('fees');
                var fullfees = $(this).data('fullfees');
                $('#edit_fee_form input[name=fee_id]').val(fees_id);
                $('#edit_fee_form input[name=amount]').val(fullfees);
                $('#edit_fee').modal('show');
            });

            $('#add_payment_btn_form').on('click', function (event) {
                event.preventDefault();
                var data = $('#add_payment_form').serialize();
                var url = $("#add_payment_form").attr('action');

                axios.post(url, data)
                    .then(function (response) {
                        //location.reload();

                        console.log(response.data)
                        let lastPaymentNumber = parseInt(response.data.last_payment_number) - 1;
                        location.href = "/admin/receipt/print/"+ lastPaymentNumber;

                    })
                    .catch(function (error) {
                        $("#add_payment_form select, #add_payment_form input, #add_payment_form textarea").removeClass('is-invalid');
                        $("#add_payment_form div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#add_payment_form input[name=" + key + "]").addClass('is-invalid');
                                $("#add_payment_form input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');

                                $("#add_payment_form select[name=" + key + "]").addClass('is-invalid');
                                $("#add_payment_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');

                                $("#add_payment_form textarea[name=" + key + "]").addClass('is-invalid');
                                $("#add_payment_form textarea[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');

                            });
                        }
                    });
            });

            $('.revert-btn').click(function (event) {
                event.preventDefault();
                var id = $(this).data('id');
                var revert = $(this).data('revert');
                var message = 'هل انت متأكد تريد ابطال الوصل رقم' + id + '؟';
                swal.fire({
                    title: message,
                    text: "هل تريد الاستمرار؟",
                    type: 'warning',
                    customClass: {
                        icon: 'swal2-arabic-question-mark'
                    },
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: 'ابطال الوصل',
                    cancelButtonText: 'الغاء',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        axios.post("{{route('payments.revert')}}", {payment_id: id, revert: revert})
                            .then(function (response) {
                                location.reload();
                            })
                            .catch(function (error) {
                                swal.fire({
                                    title: 'عذرا حصل خطأ ما يرجى اعادة المحاولة',
                                    type: 'error',
                                    customClass: {
                                        icon: 'swal2-arabic-question-mark'
                                    },
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    confirmButtonText: 'تم',
                                    confirmButtonColor: '#3085d6',
                                    reverseButtons: true
                                });
                            });

                    }
                })
            });

            $('.edit_payment').click(function (event) {
                event.preventDefault();
                var payment = $(this).data('payment');


                var programing_id = payment['programing_id'];
                var fees_id = payment['fees_id'];
                var payment_id = payment['payment_id'];
                var payment_date = payment['payment_date'];
                var payment_amount = payment['payment_amount'];
                var payment_method = payment['payment_method'];
                var description = payment['description'];
                var cheque_number = payment['cheque_number'];
                var cheque_date = payment['cheque_date'];

                $('#edit_payment_form input[name=programing_id]').val(programing_id);
                $('#edit_payment_form input[name=fees_id]').val(fees_id);
                $('#edit_payment_form input[name=payment_date]').val(payment_date);
                $('#edit_payment_form input[name=payment_id]').val(payment_id);
                $('#edit_payment_form input[name=payment_amount]').val(payment_amount);
                $('#edit_payment_form select[name=payment_method]').val(payment_method);
                $('#edit_payment_form textarea[name=description]').val(description);
                $('#edit_payment_form input[name=cheque_number]').val(cheque_number);
                $('#edit_payment_form input[name=cheque_date]').val(cheque_date);
                $('#payment-edit-show').modal('show');

            });

            $('#edit_payment_form').on('submit', function (event) {
                event.preventDefault();
                var data = $(this).serializeJSON();
                var url = $(this).attr('action');

                axios.post(url, data)
                    .then(function (response) {
                        location.reload();
                    })
                    .catch(function (error) {
                        $("#edit_payment_form select, #edit_payment_form input, #edit_payment_form textarea").removeClass('is-invalid');
                        $("#edit_payment_form div.invalid-feedback").remove();
                        if (error.response.status === 422) {
                            var errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#edit_payment_form input[name=" + key + "]").addClass('is-invalid');
                                $("#edit_payment_form input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');

                                $("#edit_payment_form select[name=" + key + "]").addClass('is-invalid');
                                $("#edit_payment_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');

                                $("#edit_payment_form textarea[name=" + key + "]").addClass('is-invalid');
                                $("#edit_payment_form textarea[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });

            $('.detail_discounts').on('click', function (event) {
                event.preventDefault();


                var data = $(this).data('discounts');

                var x = '';

                for (var i = 0; i < data.length; i++) {
                    var dis_type = '';
                    if (data[i]['discount_type'] == 1) {
                        dis_type = '%'
                    } else {
                        dis_type = 'دينار عراقي'
                    }
                    x += '<tr>' +
                        '<td style="border: 1px solid #000">' +
                        data[i]['discount_name'] +
                        '</td>' +
                        '<td style="border: 1px solid #000">' +
                        $.number(data[i]['discount_amount']) + ' ' + dis_type +
                        '</td>' +
                        '<td style="border: 1px solid #000">' +
                        data[i]['discount_description'] +
                        '</td>' +
                        '<td style="border: 1px solid #000">' +
                        '{{--@can('delete_payment_record')--}}' +
                        //TODO:: edit discount
                        '<input type="button" class="btn btn-danger" value="حذف" onclick="myfunction('+data[i]['disid']+')" @cannot('حذف التخفيض') disabled @endcannot />' +
                        '<input style="margin-right:3px;" type="button" class="btn btn-primary" value="تعديل" onclick="editDiscount('+data[i]['disid']+')" @cannot('تعديل التخفيض') disabled @endcannot />' +
                        '{{--@endcan--}}' +
                        '</td>' +
                        '</tr>';
                }

                if (data.length == 0) {
                    x += '<tr>' +
                        '<td colspan="2">ليس لديه تخفيض لهذا العام</td></tr>';
                }

                swal.fire({
                    type: 'info',
                    html:
                        '<table style="border: 1px solid #000;text-align: center;width: 100%"><thead><tr><th style="border: 1px solid #000">عنوان التخفيض</th><th style="border: 1px solid #000">قيمه التخفيض</th><th style="border: 1px solid #000">ملاحظات</th></tr></thead>' +
                        '<tbody>' + x + '</tbody>' +
                        '</table>',
                    showCancelButton: false,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'اغلاق',
                    title: "تفاصيل التخفيض للطالب المختار!",
                })
            })

            $('.delete_payment').on('click', function (event) {
                event.preventDefault();
                var id = $(this).data('payment_id');
                var url = "/admin/payments/delete/" + id;


                swal.fire({
                    title: 'هل تريد الاستمرار؟',
                    text: "حذف وصل الطالب.",
                    type: 'question',
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'لا',
                    showConfirmButton: true,
                    showCancelButton: true,
                    showCloseButton: false,
                    showLoaderOnConfirm: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    allowEnterKey: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {
                        swal.fire(
                            {
                                title: 'جار التحميل...',
                                allowEnterKey: false,
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                onOpen: () => {
                                    swal.showLoading();
                                    axios.get(url, {})
                                        .then(function (response) {
                                            swal.close();
                                            swal.fire({
                                                title: 'تم حذف الوصل بنجاح',
                                                text: "يرجى الانتظار جار اعادة تحميل الصفحة.",
                                                type: 'success',
                                                confirmButtonColor: '#d33',
                                            });
                                            setInterval(function () {
                                                window.location.reload(true);
                                            }, 3000);
                                        })
                                        .catch(function (error) {
                                            swal.close();
                                            // reset errors
                                            swal.fire({
                                                title: "خطأ",
                                                type: 'error',
                                                text: "عذرا لقد قمت بأدخال معلومات غير صحيحة!",
                                                confirmButtonColor: '#17a2b8',
                                            })

                                        });
                                },
                            }
                        )
                    }
                });

            })

            $('.delete_dis').on('click', function (event) {
                event.preventDefault();
                alert("dsgf");
                var id = $(this).data('dis_id');
                var url = "/admin/fees/delete/" + id;


                swal.fire({
                    title: 'هل تريد الاستمرار؟',
                    text: "حذف تخفيض الطالب.",
                    type: 'question',
                    confirmButtonText: 'نعم',
                    cancelButtonText: 'لا',
                    showConfirmButton: true,
                    showCancelButton: true,
                    showCloseButton: false,
                    showLoaderOnConfirm: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    allowEnterKey: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {
                        swal.fire(
                            {
                                title: 'جار التحميل...',
                                allowEnterKey: false,
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                onOpen: () => {
                                    swal.showLoading();
                                    axios.get(url, {})
                                        .then(function (response) {
                                            swal.close();
                                            swal.fire({
                                                title: 'تم حذف تخفيض الطالب',
                                                text: "يرجى الانتظار جار اعادة تحميل الصفحة.",
                                                type: 'success',
                                                confirmButtonColor: '#d33',
                                            });
                                            setInterval(function () {
                                                window.location.reload(true);
                                            }, 3000);
                                        })
                                        .catch(function (error) {
                                            swal.close();
                                            // reset errors
                                            swal.fire({
                                                title: "خطأ",
                                                type: 'error',
                                                text: "عذرا لقد قمت بأدخال معلومات غير صحيحة!",
                                                confirmButtonColor: '#17a2b8',
                                            })

                                        });
                                },
                            }
                        )
                    }
                });

            })

            $('#add_id_card_fee_form').on('submit', function (event) {
                event.preventDefault();
                var data = $(this).serializeJSON();
                var url = $(this).attr('action');
                axios.post(url, data)
                    .then(function (response) {
                        $('#add_id_card_fees_show').modal('hide');
                        window.location.reload();
                    })
                    .catch(function (error) {
                        $("#add_id_card_fee_form select, #add_id_card_fee_form input").removeClass('is-invalid');
                        $("#add_id_card_fee_form div.invalid-feedback").remove();
                        $('#add_id_card_fee_form').remove("div.alert-danger");
                        if (error.response.status === 422) {
                            let errors = error.response.data.errors;
                            $.each(errors, function (key, val) {
                                $("#add_id_card_fee_form input[name=" + key + "]").addClass('is-invalid');
                                $("#add_id_card_fee_form input[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');

                                $("#add_id_card_fee_form select[name=" + key + "]").addClass('is-invalid');
                                $("#add_id_card_fee_form select[name=" + key + "]").after('<div class="invalid-feedback">' + val + '</div>');
                            });
                        }
                    });
            });

            $('#add_fee_form').on('submit', function (event) {
              event.preventDefault();
              let data = $(this).serializeJSON();
              console.log(data);

              let url = $(this).attr('action');

              axios.post(url, data)
                  .then(function (response) {
                      $('#add_fee_form').modal('hide');

                      // console.log(response.data());

                      if (response.data.message =="تم اضافة مبلغ القسط"){
                          window.location.reload();

                      }
                  });
            });

        });


    </script>
@endsection
