@extends('admin.layouts.app')
@section('styles')
@endsection
@section('content')

    <div class="app-title">
        <div>
            <h1><i class="fa fa-wpforms"></i> الجدول الاسبوعي </h1>
            <p>ادارة الجداول الاسبوعية.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>أختر الجدول</span>
                    <div>
                        <button data-toggle="modal" data-target="#enrollment-show" type="button"
                                class="btn btn-success"><i class="fa fa-plus"></i>اضافة جدول جديد
                        </button>
                    </div>
                </div>
                <div class="card-body">

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

        });
    </script>
@endsection
