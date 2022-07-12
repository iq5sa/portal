<!-- Modal -->
<div class="modal fade" id="enrollment-show" tabindex="-1">
    <div class="modal-dialog dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة امر أداري جديد</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('orders.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" value="{{old('number')}}" name="number"
                               class="{{$errors->has('number') ? 'is-invalid': ''}} form-control"
                               placeholder="رقم الامر">
                        @if($errors->has('number'))
                            <div class="invalid-feedback">{{$errors->first('number')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="text" value="{{old('date')}}" name="date" class="{{$errors->has('date') ? 'is-invalid': ''}} form-control d_date"
                               placeholder="تأريخ الامر مثال: yyyy-mm-dd"
                        >
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
                            <option value="">أختر سنة الامر</option>
                            @foreach($academics as $academic)
                                <option value="{{$academic->id}}">{{$academic->start_year}}
                                    -{{$academic->end_year}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('academic_years_id'))
                            <div class="invalid-feedback">{{$errors->first('academic_years_id')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <select name="academic_status_id" class="{{$errors->has('academic_status_id') ? 'is-invalid': ''}} form-control">
                            <option value="">أختر نوع الامر</option>
                            @foreach($academic_statuses as $order)
                                <option value="{{$order->id}}">{{$order->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('academic_status_id'))
                            <div class="invalid-feedback">{{$errors->first('academic_status_id')}}</div>
                        @endif
                    </div>
                    <textarea name="description" id="description" rows="3" placeholder="بعض الملاحضات حول الكتاب"
                              class="{{$errors->has('description') ? 'is-invalid': ''}} form-control"></textarea>
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
