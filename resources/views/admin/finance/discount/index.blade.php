@extends('admin.layouts.app')
@section('styles')
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> تخفيض القسط الدراسي </h1>
            <p>يمكنك من خلال هذه الواجه ادارة تخفيض القسط الدراسي للطلبة.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow" id="add_class_card">
                <div class="card-header">
                    {{$edit == true ? 'تعديل تخفيض الاقسام': 'أضافة تخفيض الاقساط'}}
                </div>
                <div class="card-body">
                <form method="POST" action="{{route('discount.store')}}">
                    @csrf
                    <div class="form-group">
                        <label for="discount_name">عنوان التخفيض</label>
                        <input value="{{old('discount_name')}}" type="text" name="discount_name" class="form-control @error('discount_name') is-invalid @enderror" id="discount_name">
                        @error('discount_name')
                        <div class="invalid-feedback">{{ $errors->first('discount_name') }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="discount_type">نوع التخفيض</label>
                        <select name="discount_type" class="form-control @error('discount_type') is-invalid @enderror" id="discount_type">
                            <option value="">اختر</option>
                            <option {{old('discount_type') == 1 ? 'selected':''}} value="1">نسبة مئوية</option>
                            <option {{old('discount_type') == 2 ? 'selected':''}} value="2">مبلغ مالي</option>
                        </select>
                        @error('discount_type')
                        <div class="invalid-feedback">{{ $errors->first('discount_type') }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="discount_amount">قيمة التخفيض (نسبة او مبلغ مالي)</label>
                        <input value="{{old('discount_amount')}}" type="number" name="discount_amount" class="form-control @error('discount_amount') is-invalid @enderror" id="discount_amount">
                        @error('discount_amount')
                        <div class="invalid-feedback">{{ $errors->first('discount_amount') }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="discount_description">الملاحضات</label>
                        <textarea rows="4" name="discount_description" class="form-control @error('discount_description') is-invalid @enderror" id="discount_description">{{old('discount_description')}}</textarea>
                        @error('discount_description')
                        <div class="invalid-feedback">{{ $errors->first('discount_description') }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">حفظ</button>

                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow" id="add_class_card">
            <div class="card-header">
                قائمة تخفيض الاقساط
            </div>
            <div class="card-body">
                <div class="table-responsive table-responsive-md">
                    <table class="table table-bordered table-hover" id="sampleTable" style="width: 100%">
                        <thead>
                        <tr>
                            <th class="text-nowrap">العنوان</th>
                            <th class="text-nowrap">النوع</th>
                            <th class="text-nowrap">القيمة</th>
                            <th class="text-nowrap">الملاحضات</th>
                            <th class="text-nowrap">الاختيارات</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($discounts as $discount)
                                <tr>
                                    <td>{{$discount->discount_name}}</td>
                                    <td>{{$discount->discount_type == 1 ? 'نسبة مئوية': 'مبلغ مالي'}}</td>
                                    <td>{{$discount->discount_amount}}</td>
                                    <td>{{$discount->discount_description}}</td>
                                    <td><a href="{{route('discount.show',$discount->id)}}" class="btn btn-dark btn-sm">تحديد الطلبة</a></td>
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
    <script type="text/javascript" src="{{asset('js/plugins/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/jquery.number.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sampleTable').DataTable({
                paging: true,
                bFilter: true,
                dom: '<<"d-flex justify-content-between"<f><l>>t<"d-flex justify-content-between"<p><i>>r>',
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
            });


        });
    </script>
@endsection
