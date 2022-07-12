<?php


namespace App;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSearch
{
    public static function apply(Request $filters)
    {
        $columns = [
            "student_classes.class_id",
            "level",
            "shift",
            DB::raw("academic_years.start_year AS start_year"),
            DB::raw("academic_years.end_year AS end_year"),
            DB::raw("colleges.name AS college_name"),
            DB::raw("departments.name AS department_name"),

        ];

        $studentClass = DB::table('student_classes')->select($columns)
            ->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->join('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id');


        $fees = DB::table('fees')->select(['fees.id as fees_id', 'level', 'shift', 'start_year', 'end_year', 'college_name', 'department_name'])
            ->joinSub($studentClass, 'fees_with_class', 'fees_with_class.class_id', '=', 'fees.class_id');

        $payments = DB::table('payments')->select([
            'payment_id',
            'payment_date',
            'revert',
            'students.student_id',
            'students.first_name', 'students.father_name', 'students.middle_name', 'students.last_name', 'students.surname', 'students.full_name',
            'level', 'shift', 'start_year', 'end_year', 'college_name', 'department_name',
            'payment_amount',
            'payment_method',
        ])->join('students', 'students.student_id', 'payments.student_id')
            ->joinSub($fees, 'fee', 'fee.fees_id', '=', 'payments.fees_id');

        if ($filters->input('payment_id') != null) {
            $payments->where('payment_id', '=', $filters->input('payment_id'));
        }

        if ($filters->input('payment_date') != null) {
            $payments->where('payment_date', '=', $filters->input('payment_date'));
        }

        if ($filters->input('payment_method') != null) {
            $payments->where('payment_method', '=', $filters->input('payment_method'));
        }

        if ($filters->input('student_name') != null) {
            $payments->where('students.first_name', 'like', '%' . $filters->input('student_name') . '%')
                ->orWhere('students.father_name', 'like', '%' . $filters->input('student_name') . '%')
                ->orWhere('students.middle_name', 'like', '%' . $filters->input('student_name') . '%')
                ->orWhere('students.last_name', 'like', '%' . $filters->input('student_name') . '%')
                ->orWhere('students.surname', 'like', '%' . $filters->input('student_name') . '%')
                ->orWhere('students.full_name', 'like', '%' . $filters->input('student_name') . '%');
        }

        if ($filters->input('payment_date_end') != null & $filters->input('payment_date_start') != null){
            $payments->whereBetween('payment_date',[$filters->input('payment_date_start'),$filters->input('payment_date_end')]);
        }
        return $payments;
    }
}
