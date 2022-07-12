<!-- Modal -->
<div class="modal fade" id="enrollment-show" tabindex="-1">
    <div class="modal-dialog dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة مستخدم جديد</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('users.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <select name="role_id" id="role_id"
                                class="{{$errors->has('role_id') ? 'is-invalid': ''}} form-control">
                            <option value="">أختر دور المستخدم</option>
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('role_id'))
                            <div class="invalid-feedback">{{$errors->first('role_id')}}</div>
                        @endif
                    </div>
                    <div class="form-group" id="college_container" style="display: none">
                        @foreach($colleges as $college)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input name="assigned_colleges[]" style="margin: 0 -1.25rem 0 0" class="form-check-input" type="checkbox"
                                           value="{{$college->id}}">
                                    {{$college->name}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <input type="text" value="{{old('name')}}" name="name"
                               class="{{$errors->has('name') ? 'is-invalid': ''}} form-control"
                               placeholder="اسم المستخدم">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">{{$errors->first('name')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="email" value="{{old('email')}}" name="email"
                               class="{{$errors->has('email') ? 'is-invalid': ''}} form-control d_date"
                               placeholder="الايميل">
                        @if($errors->has('email'))
                            <div class="invalid-feedback">{{$errors->first('email')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="password" value="" name="password"
                               class="{{$errors->has('password') ? 'is-invalid': ''}} form-control d_date"
                               placeholder="الباسورد">
                        @if($errors->has('password'))
                            <div class="invalid-feedback">{{$errors->first('password')}}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <input id="password-confirm" type="password" value="" name="password_confirmation" required
                               autocomplete="new-password"
                               class="{{$errors->has('password_confirmation') ? 'is-invalid': ''}} form-control d_date"
                               placeholder="اعد كتابة الباسورد" required autocomplete="new-password">

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">اضافة</button>
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>
