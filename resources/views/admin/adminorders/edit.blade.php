<!-- Modal -->
@extends('admin.layouts.app')


@section('styles')
    <style>
        #sampleTable tbody tr td.datatable_td {
            padding: 5px !important;
            line-height: 100% !important;
            vertical-align: middle !important;
        }
    </style>
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
            <div class="card">
                <div class="card-header">
تعديل الامر الاداري
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('orders.update',$order->id)}}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="text" value="{{$order->number}}" name="number"
                                       class="{{$errors->has('number') ? 'is-invalid': ''}} form-control"
                                       placeholder="رقم الامر">
                                @if($errors->has('number'))
                                    <div class="invalid-feedback">{{$errors->first('number')}}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="text" value="{{$order->date}}" name="date" class="{{$errors->has('date') ? 'is-invalid': ''}} form-control d_date"
                                       placeholder="تأريخ الامر">
                                @if($errors->has('date'))
                                    <div class="invalid-feedback">{{$errors->first('date')}}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="file" value="{{old('file')}}" name="file" class="{{$errors->has('file') ? 'is-invalid': ''}} form-control">
                                @if($errors->has('file'))
                                    <div class="invalid-feedback">{{$errors->first('file')}}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <select name="academic_years_id" class="{{$errors->has('academic_years_id') ? 'is-invalid': ''}} form-control">
                                    <option value="">أختر</option>
                                    @foreach($academics as $academic)
                                        <option {{$academic->id == $order->academic_years_id ? 'selected' :""	}} value="{{$academic->id}}">{{$academic->start_year}}-{{$academic->end_year}}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('academic_years_id'))
                                    <div class="invalid-feedback">{{$errors->first('academic_years_id')}}</div>
                                @endif
                            </div>
                            <textarea name="description" id="description" rows="3" placeholder="بعض الملاحضات حول الكتاب"
                                      class="{{$errors->has('description') ? 'is-invalid': ''}} form-control">{{$order->description}}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">{{$errors->first('description')}}</div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">حفظ</button>
                            <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <!-- Data table plugin-->
    <script type="text/javascript" src="{{asset('js/plugins/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/plugins/dataTables.checkboxes.min.js')}}"></script>

    <script type="text/javascript">


    </script>
@endsection






