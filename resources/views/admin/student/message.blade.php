@extends('admin.layouts.app')
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> رسالة حول اضافة الطالب </h1>
            <p>يرجى قراءة الرسالة لمعرفة حالة اضافة الطالب.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-center">
                <div class="alert {{$class}} mb-3">تم أضافة الطالب بنجاح</div>
                <a href="{{route('students.create')}}" class="btn btn-primary btn-lg">{{$message}}</a>
            </div>
        </div>

    </div>
@endsection

