<?php

namespace App\Http\Controllers;

use App\AcademicStatus;
use App\AcademicYear;
use App\AdministrativeOrder;
use App\Batch;
use App\College;
use App\CourseType;
use App\Fee;
use App\Group;
use App\Shift;
use App\Status;
use App\Student;
use App\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentCasesController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:تعديل معالجة حالات الطلبة');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active_end_year = AcademicYear::where('active_year', '=', 1)->get()->first()->end_year;
        $academics = AcademicYear::orderBy('start_year', "DESC")
            ->where('start_year', '=', $active_end_year)
            ->orwhere('end_year', '=', $active_end_year)
            ->get();
        if (auth()->user()->hasRole('موظف تسجيل')) {
            $colleges = auth()->user()->colleges;
        } else {
            $colleges = College::all();
        }
        $shifts = Shift::all();
        $types = CourseType::all();
        $times = Time::all();
        $batches = Batch::all();
        $groups = Group::all();
        $academic_years = AcademicYear::orderBy('start_year','ASC')->get();
        //$orders = AdministrativeOrder::where('is_active','=',1)->get();

        $orders = DB::table('administrative_orders')->select(['administrative_orders.id as admin_id', 'administrative_orders.number as admin_number', 'academic_statuses.name as academic_name'])
            ->join('academic_statuses', 'academic_statuses.id', '=', 'administrative_orders.academic_status_id')
            ->where('administrative_orders.is_active', '=', 1)
            ->get();

        $special_statues = AcademicStatus::where('special_statues', '=', 0)->orwhere('special_statues', '=', 2)->orderBy('id', 'desc')->get();
        return view('admin.student.studentcase.index', compact('academic_years','special_statues', 'orders', 'colleges', 'academics', 'shifts', 'types', 'times', 'batches', 'groups'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'administrative_order_id' => [
                function ($attribute, $value, $fail) {
                    $status_id = \request()->input('academic_status_id');
                    if ($status_id != 1 && $value == null) {
                        $fail('يجب تحديد رقم الامر الاداري');
                    }
                }
            ],
            'academic_status_id' => 'required',
            'class_id' => 'required',
            'id' => 'array|required',
            'description' => 'required',
            'academic_year_id_status' => [function($attribute, $value, $fail){
                if (\request('academic_status_id') == 21 & \request('academic_year_id_status') == null){
                    $fail('يجب اختيار سنة العودة الى الدراسة.');
                }
            }],
        ];


        $message = [
            'class_id.required' => "يجب اختيار العام الدراسي.",
            'academic_status_id.required' => 'يجب تحديد احد الاختيارات.',
            'id.required' => 'يجب تحديد قيد طالب واحد على الاقل.',
            'description.required' => 'يجب كتابة ملاحضه مختصره عن حالة الطالب.',
        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $ids = $request->input('id');
        $not_mustamer_students = [];
        $same_status = [];
        $taajeel_array = [];
        $has_not_failed_from_last_year_array = [];
        foreach ($ids as $id) {

            $current_status = Status::where('student_id', '=', $id)
                ->where('is_active', '=', 1)
                ->get()
                ->first();

            $previous_status = DB::table('statuses')->select(['*'])
                ->where('is_active', '=', 0)
                ->where('id', '<', $current_status->id)
                ->where('student_id', '=', $id)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->first();

            $student_record = DB::table('students')->select(['statuses.id as status_id', 'statuses.academic_status_id', 'students.student_id', 'first_name', 'father_name', 'middle_name', 'last_name', 'full_name'])
                ->join('statuses', 'statuses.student_id', '=', 'students.student_id')
                ->where('students.student_id', '=', $id)
                ->where('statuses.is_active', '=', 0)
                ->where('statuses.id', '=', $previous_status->id)
                ->orderBy('statuses.id', 'desc')
                ->get()
                ->first();


            if ($request->input('academic_status_id') == 15){
                if ($previous_status->academic_status_id == 14 & $previous_status->aboor_passing_status == 1){
                    DB::table('statuses')->where('id','=',$previous_status->id)
                        ->update(['aboor_passing_status' => 2]);
                }
            }
            elseif ($request->input('academic_status_id')== 22){
                if ($previous_status->academic_status_id == 14 & $previous_status->aboor_passing_status == 1){
                    Status::where('student_id', '=', $id)
                        ->where('is_active', '=', 1)
                        ->update([
                            'academic_status_id' => 2,
                            'administrative_order_id' => $request->input('administrative_order_id'),
                            'description' => $request->input('description'),
                            'is_active' => 1,
                            'has_fail' => 0,
                        ]);
                }
            }
             elseif ($request->input('academic_status_id') == 2) {
                if (in_array($current_status->academic_status_id, [4, 5, 6, 19])) {
                    Status::where('student_id', '=', $id)
                        ->where('is_active', '=', 1)
                        ->update(['is_active' => 0
                        ]);
                    Status::create([
                        'student_id' => $id,
                        'student_class_id' => $request->input('class_id'),
                        'academic_status_id' => 2,
                        'administrative_order_id' => $request->input('administrative_order_id'),
                        'description' => $request->input('description'),
                        'is_active' => 1,
                        'has_hold_subject' => 0,
                        'has_fail' => 0,
                    ]);
                } elseif ($current_status->academic_status_id == 17 & $previous_status->academic_status_id == 17) {
                    $class = $this->get_next_class($current_status);
                    if ($class != null) {
                        Status::where('student_id', '=', $id)
                            ->where('is_active', '=', 1)
                            ->update(['is_active' => 0
                            ]);
                        Status::create([
                            'student_id' => $id,
                            'student_class_id' => $class->class_id,
                            'academic_status_id' => 2,
                            'administrative_order_id' => $request->input('administrative_order_id'),
                            'description' => $request->input('description'),
                            'is_active' => 1,
                            'has_hold_subject' => 0,
                            'has_fail' => 0,
                        ]);
                    }

                }
            } else if ($request->input('academic_status_id') == 16) {
                if (in_array($previous_status->academic_status_id, [4, 5, 6, 19])) {
                    if ($current_status->academic_status_id == 1) {
                        Status::where('student_id', '=', $id)
                            ->where('is_active', '=', 1)
                            ->update([
                                'student_id' => $id,
                                'student_class_id' => $request->input('class_id'),
                                'academic_status_id' => 16,
                                'administrative_order_id' => $request->input('administrative_order_id'),
                                'description' => $request->input('description'),
                                'is_active' => 0,
                                'has_hold_subject' => 0,
                                'has_fail' => 0,
                            ]);
                    } else {
                        Status::where('student_id', '=', $id)
                            ->where('is_active', '=', 1)
                            ->update([
                                'is_active' => 0,
                                'has_hold_subject' => 0,
                                'has_fail' => 0,
                            ]);
                        Status::create([
                            'student_id' => $id,
                            'student_class_id' => $request->input('class_id'),
                            'academic_status_id' => 16,
                            'administrative_order_id' => $request->input('administrative_order_id'),
                            'description' => $request->input('description'),
                            'is_active' => 0,
                            'has_hold_subject' => 0,
                            'has_fail' => 0,
                        ]);

                    }
                    Status::create([
                        'student_id' => $id,
                        'student_class_id' => $request->input('class_id'),
                        'academic_status_id' => 1,
                        'is_active' => 1,
                        'has_hold_subject' => 0,
                        'has_fail' => $current_status->has_fail,
                    ]);

                    $this->init_fees($current_status);



                } else {
                    if ($student_record != null) {
                        // show error message
                        $full_name = "";
                        if ($student_record->first_name != null) {
                            $full_name = $student_record->first_name . ' ' . $student_record->father_name . ' ' . $student_record->middle_name . ' ' . $student_record->last_name;
                        } else {
                            $full_name = $student_record->full_name;
                        }
                        array_push($has_not_failed_from_last_year_array, $full_name);

                    }
                }
            } elseif (in_array($request->input('academic_status_id'), [3, 7, 8, 10, 12])) {
                if ($current_status->academic_status_id == 1) {
                    Status::where('student_id', '=', $id)
                        ->where('is_active', '=', 1)
                        ->update([
                            'student_id' => $id,
                            'student_class_id' => $request->input('class_id'),
                            'academic_status_id' => $request->input('academic_status_id'),
                            'administrative_order_id' => $request->input('administrative_order_id'),
                            'description' => $request->input('description'),
                            'is_active' => 1,
                            'has_hold_subject' => 0,
                            'has_fail' => 0,
                        ]);
                }
            } elseif ($request->input('academic_status_id') == 1) {
                if ($current_status->academic_status_id == 17) {
                    Status::where('student_id', '=', $id)
                        ->where('is_active', '=', 1)
                        ->update([
                            'is_active' => 0,
                        ]);
                    Status::create([
                        'student_id' => $id,
                        'is_active' => 1,
                        'has_hold_subject' => $current_status->has_hold_subject,
                        'academic_status_id' => 1,
                        'student_class_id' => $this->get_next_class($current_status)->class_id
                    ]);
                    $this->init_fees($current_status);
                }
            } elseif ($request->input('academic_status_id') == 21) {
                if ($current_status->academic_status_id == 2 & $current_status->has_hold_subject != 1 & $previous_status->academic_status_id != 14) {
                    $class = $this->get_class($previous_status,$request->input('academic_year_id_status'));
                    Status::where('student_id', '=', $id)
                        ->where('is_active', '=', 1)
                        ->update([
                            'is_active' => 0,
                        ]);
                    Status::create([
                        'student_id' => $id,
                        'is_active' => 0,
                        'has_hold_subject' => 0,
                        'administrative_order_id' => $request->input('administrative_order_id'),
                        'academic_status_id' => 21,
                        'student_class_id' => $class->class_id
                    ]);
                    Status::create([
                        'student_id' => $id,
                        'is_active' => 1,
                        'has_hold_subject' => 0,
                        'academic_status_id' => 1,
                        'student_class_id' => $class->class_id
                    ]);
                    $this->init_fees($current_status);
                }elseif ($current_status->academic_status_id == 2 & $current_status->has_hold_subject == 1 & $previous_status->academic_status_id == 14){
                    $class = $this->get_class($previous_status,$request->input('academic_year_id_status'));

                    Status::where('student_id', '=', $id)
                        ->where('is_active', '=', 1)
                        ->update([
                            'is_active' => 0,
                        ]);
                    Status::create([
                        'student_id' => $id,
                        'is_active' => 0,
                        'has_hold_subject' => 0,
                        'administrative_order_id' => $request->input('administrative_order_id'),
                        'academic_status_id' => 21,
                        'student_class_id' => $current_status->student_class_id
                    ]);
                    Status::create([
                        'student_id' => $id,
                        'is_active' => 1,
                        'has_hold_subject' => 0,
                        'academic_status_id' => 1,
                        'student_class_id' => $class->class_id
                    ]);
                    $this->init_fees($current_status);
                }
            } elseif ($request->input('academic_status_id') == 20) {
                if ($current_status->academic_status_id == 1) {
                    Status::where('student_id', '=', $id)
                        ->where('is_active', '=', 1)
                        ->where('academic_status_id', '=', 1)
                        ->update(['is_active' => 1,
                            'academic_status_id' => 20,
                            'administrative_order_id' => $request->input('administrative_order_id'),
                            'student_class_id' => $current_status->student_class_id,
                            'has_hold_subject' => 0,
                            'has_fail' => 0,
                        ]);
                }
            }


        }
        if (sizeof($not_mustamer_students) > 0) {
            return response()->json(['student_info' => $not_mustamer_students, 'title' => "تمت الاضافة ما عدا الطلبة ادناه كونهم غير مستمرين بالدراسة."], 200);
        }

        if (sizeof($same_status) > 0) {
            return response()->json(['student_info' => $same_status, 'title' => "تمت الاضافة ما عدا الطلبة ادناه كون لديهم الحالة التي قمت بتحديدها."], 200);
        }
        if (sizeof($taajeel_array) > 0) {
            return response()->json(['student_info' => $taajeel_array, 'title' => "تمت الاضافة ما عدا الطلبة ادناه مؤجلين لعامين متتاليين."], 200);
        }
        if (sizeof($has_not_failed_from_last_year_array) > 0) {
            return response()->json(['student_info' => $has_not_failed_from_last_year_array, 'title' => "تمت الاضافة ما عدا الطلبة ادناه كونهم غير راسبين من العام الماضي."], 200);
        }

        return response()->json(['message' => 'تم معالجة حالة الطلبة بنجاح.'], 200);
    }

    public function init_fees($status)
    {
        $class = DB::table('student_classes')->where('class_id','=',$status->student_class_id)->first();
        $count = DB::table('fees')->where('student_id', '=', $status->student_id)
            ->where('class_id', '=', $class->class_id)
            ->where('academic_year_id', '=', $class->academic_year_id)
            ->count();

        if ($count == 0){
            $required_amount = $class->required_annual_fees;
            if (in_array($status->academic_status_id,[11,9])){
                $required_amount = $required_amount * 0.5;
            }
            elseif (in_array($status->academic_status_id,[4, 5, 6, 19])){
                $required_amount = 0;
            }
            $fee = new Fee();
            $fee->academic_year_id = $class->academic_year_id;
            $fee->college_id = $class->college_id;
            $fee->department_id = $class->department_id;
            $fee->required_amount = $required_amount;
            $fee->student_id = $status->student_id;
            $fee->class_id = $class->class_id;
            $fee->level_id = $class->level_id;
            return $fee->save();
        }

        return null;



    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_next_class($current_status)
    {
        $last_class = DB::table('student_classes')->select(['*'])
            ->where('class_id', '=', $current_status->student_class_id)
            ->get()
            ->first();

        $last_year = DB::table('academic_years')->select(['*'])
            ->where('id', '=', $last_class->academic_year_id)
            ->get()
            ->first();

        $last_start_year = Carbon::createFromFormat('Y', $last_year->start_year);
        $last_end_year = Carbon::createFromFormat('Y', $last_year->end_year);

        $nxt_start_year = $last_start_year->addYear()->format('Y');
        $nxt_start_end = $last_end_year->addYear()->format('Y');

        $next_academic_year = DB::table('academic_years')->select(['*'])
            ->where('start_year', '=', $nxt_start_year)
            ->where('end_year', '=', $nxt_start_end)
            ->get()
            ->first();

        $class = DB::table('student_classes')->select('class_id')
            ->where('level_id', '=', $last_class->level_id)
            ->where('shift_id', '=', $last_class->shift_id)
            ->where('college_id', '=', $last_class->college_id)
            ->where('department_id', '=', $last_class->department_id)
            ->where('academic_year_id', '=', $next_academic_year->id)
            ->where('active', '=', 1)
            ->get()
            ->first();
        return $class;
    }

    public function get_class($previous_status, $academic_year_id){
        $previous_class = DB::table('student_classes')->select(['*'])
            ->where('class_id', '=', $previous_status->student_class_id)
            ->get()
            ->first();

        $class = DB::table('student_classes')->select('class_id')
            ->where('level_id', '=', $previous_class->level_id)
            ->where('shift_id', '=', $previous_class->shift_id)
            ->where('college_id', '=', $previous_class->college_id)
            ->where('department_id', '=', $previous_class->department_id)
            ->where('academic_year_id', '=', $academic_year_id)
            ->where('active', '=', 1)
            ->get()
            ->first();
        return $class;
    }
}
