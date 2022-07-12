<!-- Modal -->
<div class="modal fade" id="add_id_card_fees_show">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">أضافة مبلغ الهوية</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="mb-0" action="{{route('fees.add_id_card_fees')}}" method="POST"
                  id="add_id_card_fee_form">
                <input name="student_id" type="hidden" value="{{$student_id}}">
                <input name="fees_id" type="hidden" value="">

                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="academic_year_id">اختر العام الدراسي</label>
                                <select name="academic_year_id" id="academic_year_id" class="form-control">
                                    <option value="">أختر</option>
                                    @foreach($academics as $year)
                                        <option value="{{$year->id}}">{{$year->end_year}}-{{$year->start_year}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="amount">مبلغ الهوية</label>
                                <input type="number" name="amount" value="10000" id="amount" class="form-control">
                            </div>
                        </div>
                    </div>


                    <h5 class="modal-title">معلومات الوصل..</h5>


                    <!-- payment card -->

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="payment_date" class="control-label">تأريخ الوصل</label>
                                <input value="{{today()->format('Y-m-d')}}" name="payment_date" type="text" class="form-control " id="payment_date" placeholder="">
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="payment_id" class="control-label">رقم الوصل</label>
                                <input value="{{$last_payment_number}}" name="payment_id" type="number" class="form-control " id="payment_id" placeholder="">
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="payment_amount" class="control-label">مبلغ الوصل</label>
                                <input value="" name="payment_amount" type="number" class="form-control " id="payment_amount" placeholder="">
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="payment_method" class="control-label">رقم الوصل</label>
                                <select id="payment_method" name="payment_method" class="form-control">
                                    <option value="">نوع الدفع</option>
                                    <option value="1">كاش</option>
                                    <option value="2">فيشه</option>
                                    <option value="3">صك سفتجه</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="cheque_number" class="control-label">رقم الصك</label>
                                <input name="cheque_number" type="number" class="form-control " id="cheque_number">
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="cheque_date" class="control-label">تأريخ الصك</label>
                                <input value="{{today()->format('Y-m-d')}}" name="cheque_date" type="text" class="form-control " id="cheque_date">
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="description" class="control-label">الملاحضات</label>
                        <textarea rows="3" id="description" name="description" class="form-control"></textarea>
                    </div>


                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-secondary" type="button">الغاء</button>
                    <button class="btn btn-primary btn-save-academic" type="submit">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

