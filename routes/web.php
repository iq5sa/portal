<?php



Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('admin')
    ->middleware('auth')
    ->group(function () {

        Route::resource('students','StudentController');
        Route::post('primary_school_specialty','StudentController@primary_school_specialty')->name('specialty.index');
        Route::post('showStudentsInfo','StudentController@showStudentsinfo')->name('students.show.info');
        Route::post('showStudentstarheelInfo','StudentController@showStudentstarheelinfo')->name('studentstarheel.show.info');
        Route::post('students/exists','StudentController@student_exists')->name('students.exits');
        Route::post('primary_school_graduation_years','StudentController@add_primary_school_graduation_year')->name('primary_school_graduation_year.store');
        Route::get('tarheel','TarheelController@index')->name('tarheel.index');
        Route::post('tarheel','TarheelController@store')->name('tarheel.store');

        Route::get('studentClasses/index','StudentClassController@index')->name('class.index');
        Route::post('studentClasses/postInsertAcademics','StudentClassController@postInsertAcademic')->name('class.insert.academics');
        Route::post('studentClasses/postInsertCollege','StudentClassController@postInsertCollege')->name('class.insert.college');
        Route::post('studentClasses/postInsertDepartment','StudentClassController@postInsertDepartment')->name('class.insert.departments');
        Route::post('studentClasses/showDepartments','StudentClassController@showDepartment')->name('class.show.departments');
        Route::post('studentClasses/postInsertLevel','StudentClassController@postInsertLevel')->name('class.insert.level');
        Route::post('studentClasses/showLevel','StudentClassController@showLevel')->name('class.show.level');
        Route::post('studentClasses/postInsertShift','StudentClassController@postInsertShift')->name('class.insert.shift');
        Route::post('studentClasses/postInsertTypes','StudentClassController@postInsertTypes')->name('class.insert.types');
        Route::post('studentClasses/postInsertTime','StudentClassController@postInsertTime')->name('class.insert.time');
        Route::post('studentClasses/postInsertBatch','StudentClassController@postInsertBatch')->name('class.insert.batch');
        Route::post('studentClasses/postInsertGroup','StudentClassController@postInsertGroup')->name('class.insert.group');
        Route::post('studentClasses/postCreateClass','StudentClassController@postCreateClass')->name('class.insert');
        Route::post('studentClasses/showClassInfo','StudentClassController@showClassInfo')->name('class.show.info');
        Route::delete('studentClasses','StudentClassController@destroy')->name('class.destroy');
        Route::post('academic_years/active','TarheelController@active_year')->name('academic_years.active');
        Route::post('academic_years/new_year','TarheelController@new_year')->name('academic_years.new_year');

        Route::post('administrative_orders','AdministrativeOrdersController@store')->name('orders.store');
        Route::get('administrative_orders','AdministrativeOrdersController@index')->name('orders.index');
        Route::get('administrative_orders/{id}/edit','AdministrativeOrdersController@edit')->name('orders.edit');
        Route::get('administrative_orders/{id}/delete','AdministrativeOrdersController@destroy')->name('orders.destroy');
        Route::put('administrative_orders/{id}','AdministrativeOrdersController@update')->name('orders.update');
        Route::get('administrative_orders/set_active/{id}','AdministrativeOrdersController@setactive')->name('orders.setactive');
        Route::get('users','UsersController@index')->name('users.index');
        Route::post('users','UsersController@store')->name('users.store');
        Route::get('users/set_active/{id}','UsersController@setactive')->name('users.setactive');
        Route::get('users/{id}/edit','UsersController@edit')->name('users.edit');
        Route::put('users/{id}','UsersController@update')->name('users.update');
        Route::put('users/reset/password/{id}','UsersController@reset_password')->name('users.reset.password');
        Route::get('users/{id}/delete','UsersController@destroy')->name('users.destroy');



        Route::get('cases','StudentCasesController@index')->name('cases.index');
        Route::post('cases','StudentCasesController@store')->name('cases.store');

        Route::get('timetables','TimeTableController@index')->name('timetable.index');

        Route::get('fees','StudentFeesController@index')->name('fees.index');
        Route::post('fees/students_info','StudentFeesController@show_students_info')->name('fees.students.info');
        Route::get('fees/details/{id}','StudentFeesController@show')->name('fees.details');
        Route::post('fees/add_id_card_fees','StudentFeesController@add_id_card_fees')->name('fees.add_id_card_fees');
        Route::post('fees/add_fee','StudentFeesController@add_fees')->name('fees.add_fee');
        Route::post('fees/edit_fee','StudentFeesController@edit_fee')->name('fees.edit_fee');
        Route::post('fees/add_dis','StudentFeesController@add_dis')->name('fees.add_dis');
        Route::post('fees/getdis','StudentFeesController@getdis')->name('fees.getdis');
        Route::get('fees/delete/{id}','StudentFeesController@destroy')->name('fees.destroy');

        //ajax show discount details by id
        Route::get('ajax/fees/details/{id}','DiscountController@showDiscountById')->name('discount.details.ajax');
        Route::post('discounts/edit','DiscountController@editDiscount')->name('discounts.edit');


        Route::resource('discount','DiscountController');
        Route::post('discount/{discount}','DiscountController@show')->name('discount.show.post');
        Route::post('assign/discount','DiscountController@assign')->name('discount.assign');

        Route::post('payments','PaymentController@store')->name('payments.store');
        Route::post('payments/revert','PaymentController@revert')->name('payments.revert');
        Route::put('payments/update','PaymentController@update')->name('payments.update');
        Route::get('payments/delete/{id}','PaymentController@destroy')->name('payments.destroy');
        Route::get('payments/search','PaymentController@show_search_form')->name('payments.search.index');
        Route::post('payments/search','PaymentController@search')->name('payments.search');
        Route::get('payments/search_between_dates','PaymentController@show_search_between_dates_form')->name('payments.search_between_dates.index');
        Route::post('payments/search_between_dates','PaymentController@search_between_dates')->name('payments.search_between_dates');

        // payments reports
        Route::get('payments/report/paid','PaymentsReportController@paid_form')->name('payments.report.paid.index');
        Route::post('payments/report/paid','PaymentsReportController@paid_download')->name('payments.report.paid.download');

        Route::get('payments/report/discount','PaymentsReportController@discount_form')->name('payments.report.discount.index');
        Route::post('payments/report/discount','PaymentsReportController@discount_download')->name('payments.report.discount.download');

        Route::get('payments/expected_payments','PaymentController@expected_payments_form')->name('payments.expected_payments.index');
        Route::post('payments/expected_payments','PaymentController@expected_payments_download')->name('payments.expected_payments.download');



        // reports web routes
        Route::get('studentReports/iraqi_enrolled','StudentReportController@iraqi_students_enrolled')->name('report.iraqi.enrolled');
        Route::post('studentReports/iraqi_enrolled','StudentReportController@iraqi_students_enrolled_download')->name('report.iraqi.enrolled.download');

        Route::get('studentReports','StudentReportController@index')->name('student.report.index');
        Route::post('studentReports/download','StudentReportController@downloadReport')->name('student.report.download');

        Route::get('studentReports/iraqi_students_by_stage','StudentReportController@iraqi_students_by_stages')->name('report.iraqi.by_stages');
        Route::post('studentReports/iraqi_students_by_stage','StudentReportController@iraqi_students_by_stages_download')->name('report.iraqi.by_stages.download');
        Route::get('studentReports/students_by_town','StudentReportController@students_report_by_town')->name('report.students.by_town');
        Route::post('studentReports/students_by_town','StudentReportController@students_report_by_town_download')->name('report.students.by_town.download');
        Route::get('studentReports/failed_students','StudentReportController@failed_students')->name('report.students.failed');
        Route::post('studentReports/failed_students','StudentReportController@failed_students_download')->name('report.students.failed.download');
        Route::get('studentReports/students_by_date_of_birth','StudentReportController@student_by_birth_date_form')->name('report.students.students_by_date_of_birth');
        Route::post('studentReports/students_by_date_of_birth','StudentReportController@student_by_birth_date_form_download')->name('report.students.students_by_date_of_birth.download');
        Route::get('studentReports/table1','StudentReportController@table1')->name('report.students.table1');
        Route::get('studentReports/table2','StudentReportController@table2')->name('report.students.table2');
        Route::get('studentReports/table3','StudentReportController@table3')->name('report.students.table3');

        Route::get('roles', 'RoleController@index')->name('roles.index');
        Route::post('roles', 'RoleController@store')->name('roles.store');
        Route::get('roles/{id}/edit', 'RoleController@edit')->name('roles.edit');
        Route::put('roles/{id}', 'RoleController@update')->name('roles.update');
        Route::get('roles/{id}/delete', 'RoleController@destroy')->name('roles.destroy');

        Route::get('permissions', 'PermissionController@index')->name('permissions.index');
        Route::post('permissions', 'PermissionController@store')->name('permissions.store');
        Route::get('permissions/{id}/edit', 'PermissionController@edit')->name('permissions.edit');
        Route::put('permissions/{id}', 'PermissionController@update')->name('permissions.update');
        Route::get('permissions/{id}/delete', 'PermissionController@destroy')->name('permissions.destroy');

        //print receipt
        Route::get('receipt/print',"ReceiptController@show")->name("receipt.print");
        Route::get('receipt/print/{payment_id}',"ReceiptController@show2")->name("receipt.print2");
        Route::get('receipt/student/search',"ReceiptController@search")->name("ajax.student.search");
        Route::post('receipt/submit',"ReceiptController@savePayment")->name("ajax.receipt.submit");
        Route::get('receipt/student/fees/{student_id}',"ReceiptController@getStudentFees")->name("ajax.getStudentFees");





    });

