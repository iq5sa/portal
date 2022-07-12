<?php

namespace App\Http\Controllers;

use App\College;
use App\Department;
use App\Fee;
use App\Level;
use App\Payment;
use App\Student;
use App\StudentClass;
use App\StudentSearch;
use App\TempPayment;
use App\User;
use DB;
use http\Env\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReceiptController extends Controller
{
    //


    public function show(Request $request){

        return view("admin.receipt",[
            "title"=>"وصل استلام",
            "payment_id"=>time() - (10000 * 24 * 60 * 60),
            "student_name"=>"",
            "payment_amount"=>"",
            "level"=>'',
            "type"=>''
        ]);
    }



    public function search(Request $request){

        $name = $request->name;
        $col = new Collection();

        $students = DB::table('students')->where('first_name', 'like', "%$name%")->limit(5)->get();
        if ($students->count() !=0){
            for ($i = 0 ; $i < $students->count();$i++){
                $std = StudentSearch::student_record($students->get($i)->student_id)->get()->first();


                $col->add($std);

            }
            return $col;

        }


    }


    public function getStudentFees($student_id){

        $fees = DB::table("fees")->where("student_id","=",$student_id)
            ->join("academic_years","fees.academic_year_id","=","academic_years.id")
            ->orderByDesc('academic_years.id')

            ->select(["fees.id","fees.fee_name","fees.required_amount","academic_years.start_year","academic_years.end_year"])->get();
        return $fees;


    }




    public function savePayment(Request $request){


//        return $request->all();

        $validator = Validator::make($request->all(),[
            "student_name"=>"required",
            "payment_date"=>"required",
            "payment_amount"=>"required",
            "collage_name"=>"required",
            "collage_level"=>"required",
            "shift"=>"required",
        ]);


        $payment_id = intval($request->input("payment_id"));
        $student_id = intval($request->input("student_id"));
        $student_name = trim($request->input("student_name"));
        $payment_date = trim($request->input("payment_date"));
        $payment_amount = trim($request->input("payment_amount"));

        //cheque
        $cheque_number = trim($request->input("cheque_number"));
        $bank_name = $request->input("bank_name");
        $cheque_date = $request->input("cheque_date");

        $notes = $request->input("note");
        $collage_name = $request->input("collage_name");
        $collage_level= intval($request->input("collage_level"));
        $shift= intval($request->input("shift"));
        $fee_id = intval($request->input("fee_id"));

        if ($validator->fails()){
            return response()->json(["status"=>false,"message"=>"جميع الحقول مطلوبة"],500);
        }

        $payments = Payment::all()->where("payment_id","=",$request->input("payment_id"));
        $temp_payments = TempPayment::all()->where("payment_id","=",$request->input("payment_id"));

//        return $temp_payments;
        if ($payments->count() !=0 || $temp_payments->count() !=0){
            return response()->json(["status"=>false,"message"=>"رقم الوصل مكرر في قاعدة البيانات"],500);

        }

        if (empty($student_id)){
            $temp = new TempPayment();
            $temp->payment_id = $payment_id;
            $temp->student_name = $student_name;
            $temp->student_name = $student_name;
            $temp->collage_name = $collage_name;
            $temp->collage_level = $collage_level;
            $temp->shift = $shift;
            $temp->payment_date = $payment_date;
            $temp->payment_amount = $payment_amount;
            $temp->note = $notes;
            $temp->cheque_number = $cheque_number;
            $temp->bank_name = $bank_name;
            $temp->cheque_date = $cheque_date;
            $temp->save();

            return response()->json(["status"=>true,"message"=>"تم اضافة وصل لطالب جديد"]);

        }

        //getting fee id

        $payment = new Payment();
        $payment->payment_id = $payment_id;
        $payment->payment_date = $payment_date;
        $payment->payment_amount = $payment_amount;
        $payment->payment_method = 1;
        $payment->fees_id = $fee_id;
        $payment->student_id = $student_id;
        $payment->user_id = auth()->user()->id;
        $payment->cheque_number = $request->input('cheque_number');
        $payment->cheque_date = $request->input('cheque_date');
        $payment->description = $request->input('note');
        $payment->save();


        //here id exist student you should add this payment to his profile

        return response()->json(["status"=>true,"message"=>"تم اضافة وصل للطالب"]);

    }


    public static function student_record()
    {
        $columns = [
            '*',
            \Illuminate\Support\Facades\DB::raw('students.id as std_id'),
            "first_name",
            "father_name",
            "full_name",
            "students.student_id",
            "level",
            "shift",
            "start_year",
            "end_year",
            "college_name",
            "department_name",
            "academic_status_name",
            'has_hold_subject',
        ];
        $column2 = [
            DB::raw('academic_statuses.name AS academic_status_name'),
            'student_id', 'student_class_id',
            'has_hold_subject',
            'has_fail'
        ];

        $column3 = [
            "class_id",
            "level",
            "shift",
            DB::raw("academic_years.start_year AS start_year"),
            DB::raw("academic_years.end_year AS end_year"),
            DB::raw("colleges.name AS college_name"),
            DB::raw("departments.name AS department_name"),
            DB::raw("departments.id AS department_id"),
            'group'
        ];


        $statuses = DB::table('statuses')->select($column2)
            ->join('academic_statuses', 'statuses.academic_status_id', '=', 'academic_statuses.id')
            ->where('statuses.is_active', '=', 1)
            ->where('academic_statuses.id', '=', 1);


        $st = DB::table('students')->select($columns);

        $st->joinSub($statuses, 'st', function ($join) {
            $join->on('st.student_id', '=', 'students.student_id');
        });




        $studentClass = DB::table('student_classes')->select($column3)->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->leftJoin('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id')
            ->where('colleges.id',  '=', 3)
            ->where('shifts.id',  '=', 2)
            ->where('levels.level',  '=', "1");

        $studentClass->join('groups', 'groups.id', '=', 'student_classes.group_id');
        $st->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'st.student_class_id');
        });


        $student =  $st->limit(1)->get();


        $fee = new Fee();
        $fee->academic_year_id = 5;
        $fee->college_id = $student[0]->college_id;
        $fee->college_id = $student[0]->college_id;
    }
}
