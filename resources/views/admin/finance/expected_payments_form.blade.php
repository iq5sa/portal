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
            <h1><i class="fa fa-wpforms"></i> الدفوعات المتوقعه والمدفوعه حسب الاعوام الدراسية </h1>
            <p>يمكنك من خلال هذه الواجه الحصول على تقرير الدفوعات المتوقعه والمدفوعه مصنفة حسب الاقسام العلمية.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow" id="add_class_card">
                <div class="card-header">
                    تحميل التقرير
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('payments.expected_payments.download')}}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <select id="academic_year_id" name="academic_year_id"
                                        class="form-control @error('academic_year_id') is-invalid @enderror">
                                    <option value="">اختر العام الدراسي</option>
                                    @foreach($academics as $academic)
                                        <option value="{{$academic->id}}">{{$academic->end_year}}-{{$academic->start_year}}</option>
                                    @endforeach
                                </select>
                                @error('academic_year_id')
                                <div class="invalid-feedback">{{ $errors->first('academic_year_id') }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <button id="search" class="btn btn-danger btn-block"><i class="fa fa-search"></i>تحميل
                                    التقرير بصيغة PDF
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
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


















