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
use App\Level;
use App\Notifications\TarheelNeedAction;
use App\Shift;
use App\Status;
use App\Student;
use App\StudentClass;
use App\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TarheelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$active_end_year = AcademicYear::where('active_year', '=', 1)->get()->first()->end_year;
        $academics = AcademicYear::orderBy('start_year', "DESC")
            /*->where('start_year', '=', $active_end_year)
            ->orwhere('end_year', '=', $active_end_year)
            */ ->get();
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
        //$orders = AdministrativeOrder::where('is_active', '=', 1)->get();

        /*$orders = DB::table('administrative_orders')->select(['*'])
            ->join('academic_statuses','academic_statuses.id','=','administrative_orders.academic_status_id')
            ->where('administrative_orders.is_active', '=', 1)
            ->get();*/
        $orders = DB::table('administrative_orders')->select(['administrative_orders.id as admin_id', 'administrative_orders.number as admin_number', 'academic_statuses.name as academic_name'])
            ->join('academic_statuses', 'academic_statuses.id', '=', 'administrative_orders.academic_status_id')
            ->where('administrative_orders.is_active', '=', 1)
            ->get();


        $special_statues = AcademicStatus::where('special_statues', '=', 1)->orderBy('id', 'desc')->get();

        return view('admin.student.tarheel.index', compact('special_statues', 'orders', 'colleges', 'academics', 'shifts', 'types', 'times', 'batches', 'groups'));

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
            'class_id' => 'required',
            'nxt_class_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $old_course = StudentClass::where('class_id', '=', \request('class_id'))->firstOrFail();
                    $old_level = Level::find($old_course->level_id)->level;
                    $new_course = StudentClass::where('class_id', '=', $value)->firstOrFail();
                    $new_level = Level::find($new_course->level_id)->level;

                    if (\request('academic_status_id') == 13 || \request('academic_status_id') == 14) {
                        if (($new_level - 1) != $old_level) {
                            $fail('يجب أختيار كورس صحيح.');
                        }
                    }elseif (\request('academic_status_id') == 17) {
                      if ($new_level != $old_level) {
                          $fail('يجب أختيار كورس صحيح.');
                      }
                    }
                    else {
                        if ($new_level != $old_level) {
                            $fail('يجب أختيار كورس صحيح.');
                        }
                    }
                    if (\request('academic_status_id') == 25) {
                      // code...
                    }else{
                      if ($new_course->shift_id != $old_course->shift_id) {
                          $fail('لايمكن تحديث حالة الطالب من الدراسة الصباحية الى المسائية او بالعكس.');
                      }
                    }

                }
            ],
            'academic_status_id' => "required",
            'administrative_order_id' => 'required',
            'id' => 'array|required'
        ];
        $message = [
            'class_id.required' => "يجب اختيار الكورس الحالي.",
            'nxt_class_id.required' => "يجب أختيار الكورس القادم.",
            'academic_status_id.required' => 'يجب تحديد احد الاختيارات.',
            'administrative_order_id.required' => 'يجب تحديد احد الاختيارات.',
            'id.required' => 'يجب تحديد قيد طالب واحد على الاقل.',
        ];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();
        $ids = $request->input('id');
        $array = [];
        $array2 = [];
        $tarqeen_notification_array = [];
        $not_mustamer_students = [];


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
            $student_name = $student_record->full_name;

            if ($current_status->academic_status_id != 1) {
                array_push($not_mustamer_students, $student_name);
            } elseif ($current_status->academic_status_id == 1) {
                // if user selected success status
                if ($request->input('academic_status_id') == 13) {
                    // if has : then show error message
                    if ($current_status->has_hold_subject == 1) {
                        array_push($array, $student_name);
                    } elseif ($current_status->student_class_id != $request->input('nxt_class_id') &
                        $previous_status->aboor_passing_status != 1) {

                        // detective previous academic status
                        Status::where('student_id', '=', $id)
                            ->where('is_active', '=', 1)
                            ->update(['is_active' => 0, 'academic_status_id' => 13,
                                'administrative_order_id' => $request->input('administrative_order_id'),
                                'has_fail' => 0,
                            ]);
                        // register student on the new course

                        $status = Status::create([
                            'student_id' => $id,
                            'student_class_id' => $request->input('nxt_class_id'),
                            'academic_status_id' => 1,
                            'is_active' => 1,
                            'has_fail' => 0,
                            'has_hold_subject' => 0
                        ]);


                        $this->init_fees($status, $id);

                    }
                    // if user select Tahmeel status
                } elseif ($request->input('academic_status_id') == 14) {
                    if ($current_status->has_hold_subject == 1) {
                        array_push($array, $student_name);
                    } elseif ($current_status->student_class_id != $request->input('nxt_class_id')
                        & $previous_status->has_hold_subject == 0) {
                        // detective last status
                        Status::where('student_id', '=', $id)
                            ->where('is_active', '=', 1)
                            ->update(['is_active' => 0,
                                'administrative_order_id' => $request->input('administrative_order_id'),
                                'academic_status_id' => 14,
                                'aboor_passing_status' => 1,
                                'has_fail' => 0,
                            ]);

                        // register student on the new course
                        $status = Status::create([
                            'student_id' => $id,
                            'student_class_id' => $request->input('nxt_class_id'),
                            'academic_status_id' => 1,
                            'is_active' => 1,
                            'has_hold_subject' => 1,
                            'has_fail' => 0,
                        ]);
                        $this->init_fees($status, $id);

                    }
                } else if (in_array($request->input('academic_status_id'), [4, 5, 6, 19])) {

                    if ($student_record != null) {
                        $failed_twice = 0;

                        if (in_array($previous_status->academic_status_id, [4, 5, 6, 19])) {
                            $failed_twice = 1;
                        }

                        if ($previous_status->academic_status_id == 14) {
                            Status::where('student_id', '=', $id)
                                ->where('is_active', '=', 1)
                                ->update(['academic_status_id' => $request->input('academic_status_id'),
                                    'administrative_order_id' => $request->input('administrative_order_id'),
                                    'has_fail' => 0,
                                    'has_hold_subject' => 0,
                                    'is_active' => 1
                                ]);

                        } else if ($failed_twice == 1 & $current_status->academic_status_id = 1 & $previous_status->academic_status_id != 14) {
                            Status::where('student_id', '=', $id)
                                ->where('is_active', '=', 1)
                                ->update(['academic_status_id' => $request->input('academic_status_id'),
                                    'administrative_order_id' => $request->input('administrative_order_id'),
                                    'has_fail' => 0,
                                    'has_hold_subject' => 0,
                                ]);

                        } else if ($failed_twice == 0 & $current_status->student_class_id != $request->input('nxt_class_id') & $current_status->academic_status_id = 1) {
                            Status::where('student_id', '=', $id)
                                ->where('is_active', '=', 1)
                                ->update(['is_active' => 0,
                                    'academic_status_id' => $request->input('academic_status_id'),
                                    'administrative_order_id' => $request->input('administrative_order_id'),
                                    'has_fail' => 0,
                                    'has_hold_subject' => 0,

                                ]);
                            // register student on the new course
                            $status = Status::create([
                                'student_id' => $id,
                                'student_class_id' => $request->input('nxt_class_id'),
                                'academic_status_id' => 1,
                                'has_fail' => 1,
                                'has_hold_subject' => 0,
                                'is_active' => 1
                            ]);
                            $this->init_fees($status, $id);
                        }
                    }

                } elseif ($request->input('academic_status_id') == 17) {
                    if ($current_status->academic_status_id == 17 & $previous_status->academic_status_id == 17) {
                        array_push($taajeel_array, $student_record->full_name);
                    } else{
                        Status::where('student_id', '=', $id)
                            ->where('is_active', '=', 1)
                            ->update(['is_active' => 0,
                                'academic_status_id' => $request->input('academic_status_id'),
                                'administrative_order_id' => $request->input('administrative_order_id'),
                                'has_fail' => 0,
                                'has_hold_subject' => 0,

                            ]);

                        $status = Status::create([
                            'student_id' => $id,
                            'student_class_id' => $request->input('nxt_class_id'),
                            'academic_status_id' => 1,
                            'has_fail' => 0,
                            'has_hold_subject' => 0,
                            'is_active' => 1
                        ]);
                        $this->init_fees($status, $id);
                    }

                }
                elseif ($request->input('academic_status_id') == 25) {

                        Status::where('student_id', '=', $id)
                            ->where('is_active', '=', 1)
                            ->update(['is_active' => 0,
                                'academic_status_id' => $request->input('academic_status_id'),
                                'administrative_order_id' => $request->input('administrative_order_id'),
                                'has_fail' => 0,
                                'has_hold_subject' => 0,

                            ]);

                        $status = Status::create([
                            'student_id' => $id,
                            'student_class_id' => $request->input('nxt_class_id'),
                            'academic_status_id' => 1,
                            'has_fail' => 0,
                            'has_hold_subject' => 0,
                            'is_active' => 1
                        ]);
                        $this->init_fees($status, $id);

                }
            }
        }
        if (sizeof($array) > 0) {
            return response()->json(['student_info' => $array, "title" => "تم الترحيل ماعدا الطلبة ادناه لايمكن (عبور) الطالب لعامين متتاليين."], 200);
        }
        if (sizeof($array2) > 0) {
            return response()->json(['student_info' => $array2, 'title' => "تم الترحيل ماعدا الطلبة ادناه كون لديهم مواد (عبور) من العام الماضي."], 200);
        }
        if (sizeof($tarqeen_notification_array) > 0) {
            return response()->json(['student_info' => $tarqeen_notification_array, 'title' => "يجب ترقين قيد الطلبة ادناه بسبب الرسوب لعاميين متتاليين"], 200);
        }
        if (sizeof($not_mustamer_students) > 0) {
            return response()->json(['student_info' => $not_mustamer_students, 'title' => "تم الترحيل ما عدا الطلبة ادناه كونهم غير مستمرين بالدراسة."], 200);
        }
        return response()->json(['message' => "تمت عملية الترحيل بنجاح"], 200);
    }

    public function init_fees($status, $student_id)
    {
        $class = DB::table('student_classes')->where('class_id', '=', $status->student_class_id)->first();

        $count = DB::table('fees')->where('student_id', '=', $student_id)
            ->where('class_id', '=', $class->class_id)
            ->where('academic_year_id', '=', $class->academic_year_id)
            ->count();


        if ($count == 0) {
            $required_amount = $class->required_annual_fees;
            if (in_array($status->academic_status_id, [11, 9])) {
                $required_amount = $required_amount * 0.5;
            } elseif (in_array($status->academic_status_id, [4, 5, 6, 19])) {
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

    public function active_year(Request $request)
    {
        $active_year = AcademicYear::orderBy('start_year', 'DESC')->get();
        return response()->json($active_year, 200);
    }


    public function new_year(Request $request)
    {

        //$active_year = AcademicYear::where('active_year', '=', 1)->get()->first();
        $active_year = AcademicYear::find($request->input('active_year'));
        $dt = Carbon::createFromFormat('Y', $active_year->end_year);
        $nxt_year_end = $dt->addYear()->year;
        $new_year = AcademicYear::where('start_year', '=', $active_year->end_year)
            ->where('end_year', '=', $nxt_year_end)
            ->get()->first();
        return response()->json($new_year, 200);
    }
}
