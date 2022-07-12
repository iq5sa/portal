@extends('admin.layouts.app')
@section('styles')
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> تقرير بأجمالية الطلبة المخفضه اجورهم مصنفة حسب الكليات والاعوام الدراسية
            </h1>
            <p>يمكنك من خلال هذه الواجه الحصول على تقرير الطلبة المحفضه اجورهم.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header">
                    اختر معلومات التقرير
                </div>
                <form method="POST" id="filter_report_form" action="{{route('payments.report.discount.download')}}">
                    <div class="card-body">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <select name="college_id" class="form-control" id="college_id">
                                    <option value="">الكلية</option>
                                    @foreach($colleges as $college)
                                        <option value="{{$college->id}}">{{$college->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <select name="department_id" class="form-control" id="department_id">
                                    <option value="">القسم</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <select name="level_id" class="form-control" id="level_id">
                                    <option value="">المرحلة</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <select name="shift_id" class="form-control" id="shift_id">
                                    <option value="" selected>نوع الدراسة</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{$shift->id}}">{{$shift->shift}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <select name="academic_year_id"
                                        class="form-control @error('academic_year_id') is-invalid @enderror"
                                        id="academic_year_id">
                                    <option value="" selected>العام الدراسي</option>
                                    @foreach($academics as $academic)
                                        <option value="{{$academic->id}}">{{$academic->end_year}}
                                            -{{$academic->start_year}}</option>
                                    @endforeach
                                </select>
                                @error('academic_year_id')
                                <div class="invalid-feedback">{{ $errors->first('academic_year_id') }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <select name="discount_id"
                                        class="form-control @error('discount_id') is-invalid @enderror" id="amount">
                                    <option value="" selected>جميع التخفيضات</option>
                                    @foreach($discounts as $discount)
                                        <option value="{{$discount->id}}">{{$discount->discount_name}}</option>
                                    @endforeach
                                </select>
                                @error('discount_id')
                                <div class="invalid-feedback">{{ $errors->first('discount_id') }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-danger">تحميل التقرير بصيغة PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#college_id').on('change', function (e) {
                var url = "{{route('class.show.departments')}}";
                var college_id = $(this).val();
                var data = {college_id: college_id};
                var department = $('#department_id');
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
            $('#department_id, #college_id').on('change', function (e) {
                var url = "{{route('class.show.level')}}";
                var college_id = $("#college_id").val();
                var department_id = $("#department_id").val();
                var data = {
                    college_id: college_id,
                    department_id: department_id
                };
                var level_id = $('#level_id');
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
        });
    </script>
@endsection


















