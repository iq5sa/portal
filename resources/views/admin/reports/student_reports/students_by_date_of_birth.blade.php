@extends('admin.layouts.app')
@section('styles')
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i>  الطلبة العراقيين االموجودين بحسب العمر والجنس للعام الدراسي</h1>
            <p>الطلبة العراقيين االموجودين بحسب العمر والجنس للعام الدراسي</p>
        </div>
    </div>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    اختر العام الدراسي
                </div>
                <form method="POST" id="filter_student_form" action="{{route('report.students.students_by_date_of_birth.download')}}">
                    <div class="card-body">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="academic_year_id">العام الدراسي</label>
                                <select name="academic_year_id"
                                        class="form-control {{$errors->has('academic_year_id') ? 'is-invalid':''}}"
                                        id="academic_year_id">
                                    <option value="">اختر</option>
                                    @foreach($academics as $academic)
                                        <option value="{{$academic->id}}">{{$academic->start_year}}
                                            -{{$academic->end_year}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('academic_year_id'))
                                    <div class="invalid-feedback">{{$errors->first('academic_year_id')}}</div>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="shift_id">نوع الدراسة</label>
                                <select name="shift_id"
                                        class="form-control {{$errors->has('academic_year_id') ? 'is-invalid':''}}"
                                        id="shift_id">
                                    <option value="">كلا الدراستين</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{$shift->id}}">{{$shift->shift}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('academic_year_id'))
                                    <div class="invalid-feedback">{{$errors->first('academic_year_id')}}</div>
                                @endif
                            </div>
                        </div>


                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">تحميل التقرير</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
@endsection

