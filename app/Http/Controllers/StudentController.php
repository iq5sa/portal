<?php

namespace App\Http\Controllers;

use App\AcademicStatus;
use App\AcademicYear;
use App\Batch;
use App\College;
use App\CourseType;
use App\Department;
use App\DocumentType;
use App\EnrollmentChannel;
use App\Fee;
use App\Group;
use App\primarySchoolGraduationYear;
use App\Rules\Number;
use App\Shift;
use App\Status;
use App\Student;
use App\StudentDocument;
use App\StudentSearch;
use App\Time;
use App\Town;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:أضافة قيد طالب', ['only' => [
            'create',
            'store',
            'student_exists',
            'add_primary_school_graduation_year',
            'primary_school_specialty'
        ]]);
        $this->middleware('permission:عرض جميع الطلبة', ['only' => [
            'index',
            'show',
            'showStudentsinfo',
        ]]);
        $this->middleware('permission:تعديل قيد الطالب', ['only' => [
            'edit',
            'update',
        ]]);
    }


    public function student_exists(Request $request)
    {
        $rules = [
            'student_nationality_id' => [
                'required',
                new Number(),
                function ($attribute, $value, $fail) {
                    $count = DB::table('students')->select('national_id_number')
                        ->where('national_id_number', '=', $value)
                        ->count();
                    if ($count == 0) {
                        \request()->session()->flash('student_nationality_id', 'لم يتم العثور على اي قيد للطالب!');
                    } elseif ($count > 0) {
                        $fail("تم أدخال معلومات الطالب مسبقاً");
                    }
                }

            ]
        ];
        $message = [
            'required' => 'يجب أدخال رقم هوية الطالب او البطاقة الوطنية!',
        ];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();


        return back();
    }

    public function add_primary_school_graduation_year(Request $request)
    {
        $rules = [
            'start_year' => [
                'required',
            ],
            'end_year' => ['required',
                function ($attribute, $value, $fail) {
                    try {
                        $start_year = Carbon::createFromFormat('Y', \request('start_year'));
                        $end_year = Carbon::createFromFormat('Y', $value);

                        if ($end_year->lt($start_year)) {
                            $fail('نهاية السنة اقل من البداية يجب اختيار سنة اعلى.');
                        } elseif ($end_year->eq($start_year)) {
                            $fail('نهاية السنة تساوي البداية يجب اختيار سنة اعلى.');
                        } elseif ($start_year->diffInYears($end_year) != 1) {
                            $fail('يجب ان يكون الفرق بمعدل سنة واحدة!');
                        }
                    } catch (\Exception $e) {
                        $fail('يجب أدخال سنة صحيحة');
                    }
                },],
        ];

        $v = Validator::make($request->all(), $rules);
        $v->validate();


        if ($request->ajax()) {
            $year = new primarySchoolGraduationYear();
            $year->start_year = $request->input('start_year');
            $year->end_year = $request->input('end_year');
            $year->save();
            return response()->json($year, 200);
        }
        return abort(401);
    }

    public function index()
    {


        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
        $colleges = College::all();
        $levels = DB::table('levels')->distinct()->get('level');
        $shifts = Shift::all();
        $types = DB::table('academic_statuses')->whereIn('id', [2, 3, 1, 7, 8, 17, 20])->get();
        $grad_years = AcademicYear::orderBy('start_year', "DESC")->get();
        return view('admin.student.index', compact('grad_years', 'colleges', 'academics', 'shifts', 'types','levels'));

    }

    public function showStudentsinfo(Request $request)
    {


        if ($request->ajax()) {
            $data = StudentSearch::apply($request);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('fullname', function ($row) {
                    $name = "";
                    if ($row->first_name == null) {
                        $name = $row->full_name . " " . $row->surname;
                    } else {
                        $name = $row->first_name . ' ' . $row->father_name . ' ' . $row->middle_name . ' ' . $row->last_name . ' ' . $row->surname;
                    }
                    $url = route('students.show', $row->st_uni_id);
                    return "<a href='$url' target='_blank'>$name</a>";
                })->addColumn('student_gender', function ($row) {
                    return $row->gender == 0 ? 'ذكر' : "انثى";
                })->addColumn('academic_year', function ($row) {
                    return $row->start_years . '-' . $row->end_years;
                })->addColumn('dacademic_year', function ($row) {
                    return $row->dstart_year . '-' . $row->dend_year;
                })->addColumn('college_department', function ($row) {
                    $college_dpt = $row->college_name;
                    if ($row->department_name != null || $row->department_name != "") {
                        $college_dpt .= '/' . $row->department_name;
                    }
                    return $college_dpt;
                })->addColumn('image', function ($row) {
                    if ($row->photo != null) {
                        $url = asset('storage/' . $row->photo);
                        return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                    } else {
                        if ($row->gender == 0) {
                            $url = asset('images/student.svg');
                            return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                        } elseif ($row->gender == 1) {
                            $url = asset('images/woman.svg');
                            return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                        }
                    }
                })
                ->addColumn('edited_status', function ($row) {
                    if ($row->has_hold_subject == 1) {
                        return $row->academic_status_name . ' <span class="badge badge-warning">عبور من العام الماضي</span>';
                    }

                    if ($row->has_fail == 1) {
                        return $row->academic_status_name . ' <span class="badge badge-danger">راسب من العام الماضي</span>';
                    }

                    return $row->academic_status_name;

                })
                ->rawColumns(['image', 'fullname', 'edited_status'])
                ->orderColumn('student_id', "student_id $1")
                ->orderColumn('fullname', "full_name $1")
                ->make(true);
        }

    }

    public function showStudentstarheelinfo(Request $request)
    {



        if ($request->ajax()) {
            $data = StudentSearch::applytarheel($request);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('fullname', function ($row) {
                    $name = "";
                    if ($row->first_name == null) {
                        $name = $row->full_name . " " . $row->surname;
                    } else {
                        $name = $row->first_name . ' ' . $row->father_name . ' ' . $row->middle_name . ' ' . $row->last_name . ' ' . $row->surname;
                    }
                    $url = route('students.show', $row->st_uni_id);
                    return "<a href='$url' target='_blank'>$name</a>";
                })->addColumn('student_gender', function ($row) {
                    return $row->gender == 0 ? 'ذكر' : "انثى";
                })->addColumn('academic_year', function ($row) {
                    return $row->start_years . '-' . $row->end_years;
                })->addColumn('dacademic_year', function ($row) {
                    return $row->dstart_year . '-' . $row->dend_year;
                })->addColumn('college_department', function ($row) {
                    $college_dpt = $row->college_name;
                    if ($row->department_name != null || $row->department_name != "") {
                        $college_dpt .= '/' . $row->department_name;
                    }
                    return $college_dpt;
                })->addColumn('image', function ($row) {
                    if ($row->photo != null) {
                        $url = asset('storage/' . $row->photo);
                        return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                    } else {
                        if ($row->gender == 0) {
                            $url = asset('images/student.svg');
                            return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                        } elseif ($row->gender == 1) {
                            $url = asset('images/woman.svg');
                            return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                        }
                    }
                })
                ->addColumn('edited_status', function ($row) {
                    if ($row->has_hold_subject == 1) {
                        return $row->academic_status_name . ' <span class="badge badge-warning">عبور من العام الماضي</span>';
                    }

                    if ($row->has_fail == 1) {
                        return $row->academic_status_name . ' <span class="badge badge-danger">راسب من العام الماضي</span>';
                    }

                    return $row->academic_status_name;

                })
                ->rawColumns(['image', 'fullname', 'edited_status'])
                ->orderColumn('student_id', "student_id $1")
                ->orderColumn('fullname', "full_name $1")
                ->make(true);
        }

    }

    public function primary_school_specialty(Request $request)
    {
        if ($request->ajax()) {

            if ($request->input('primary_school_specialty_general_id') != null) {
                $data = DB::table('primary_school_specialty_special')
                    ->where('general_id', '=', $request->input('primary_school_specialty_general_id'))
                    ->get();
                return response()->json($data, 200);
            } else {
                return response()->json([], 401);
            }
        }

        return abort(404);


    }

    public function create(Request $request)
    {

        if ($request->ajax()) {

            if ($request->input('college_id') != null) {
                $college_id = $request->input('college_id');
                $departments = Department::where('college_id', '=', $college_id)->get();
                return response()->json($departments, 200);

            } else {
                return response()->json([], 401);
            }
        }
        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
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
        $orders = DB::table('administrative_orders')->where('is_active', '=', 1)
            ->whereIn('academic_status_id', [18, 9, 11])
            ->get();
        $channels = EnrollmentChannel::all();
        $grad_years = primarySchoolGraduationYear::orderBy('start_year', "DESC")->get();
        $psg = DB::table('primary_school_specialty_general')->get();
        $academic_statuses = AcademicStatus::all();
        $towns = Town::all();


        return view('admin.student.create', compact('towns', 'academic_statuses', 'grad_years', 'psg', 'channels', 'colleges', 'academics', 'shifts', 'types', 'times', 'batches', 'groups', 'orders'));
    }

    public function store(Request $request)
    {
        $rules = [
            'student_id' => ['required', new Number(), 'unique:students'],
            'first_name' => 'required|string|min:3',
            'father_name' => 'required|string|min:3',
            'middle_name' => 'required|string|min:3',
            'last_name' => 'nullable|string|min:3',
            'surname' => 'nullable|string|min:3',
            'enrollment_number' => ['required', new Number()],
            'class_id' => ['required', new Number()],
            'exam_number' => "nullable|string",
            'exam_attempt_number' => "nullable|integer|in:0,1,2,3",
            'primary_school_specialty_general_id' => ['required', new Number()],
            'primary_school_specialty_special_id' => ['nullable', new Number()],
            'application_certificate' => 'nullable|string',
            'primary_school_graduation_year' => 'nullable|string',
            'primary_school_name' => 'required|string',
            'total_score' => ['nullable', new Number(),
                function ($attribute, $value, $fail) {
                    $total_limit = \request('number_of_modules_studied') * 100;
                    if (is_numeric($value) && $value > $total_limit) {
                        $fail('يجب ان يكون المجموع اقل من ' . $total_limit . ' درجة.');
                    } elseif (is_numeric($value) && $value < 0) {
                        $fail('قيمة المجموع المدخلة غير صالحة.');
                    } elseif (!is_numeric($value)) {
                        $fail('يجب أدخال ارقام فقط');
                    }
                }
            ],
            'number_of_modules_studied' => ['nullable', new Number()],
            'score_average_before' => ['nullable', new Number()],
            'score_average_after' => ['required', new Number()],
            'has_institution_certificate' => 'nullable|in:0,1',
            'has_english_module' => 'nullable|in:0,1',
            'enrollment_channel_id' => 'nullable|integer',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'nullable|string',
            'gender' => 'nullable|string',
            'social_status' => 'nullable|string',
            'national_id_number' => 'required|string|unique:students',
            'national_id_issue_date' => 'nullable|string',
            'national_id_issuer' => 'nullable|string',
            'certificate_of_iraqi_nationality' => 'nullable|string',
            'certificate_of_iraqi_nationality_issue_date' => 'nullable|date',
            'certificate_of_iraqi_nationality_issuer' => 'nullable|string',
            'city' => ['required', new Number()],
            'town' => 'nullable|string',
            'township' => 'nullable|string',
            'neighbor' => 'nullable|string',
            'district_no' => 'nullable|string',
            'side_street_no' => 'nullable|string',
            'house_number' => 'nullable|string',
            'phone' => 'nullable|string',
            'house_phone_no' => 'nullable|string',
            'email' => 'nullable|email',
            'near_point' => 'nullable|string',
            'ministry_name' => 'nullable|string',
            'department' => 'nullable|string',
            'work_place' => 'nullable|string',
            'career_title' => 'nullable|string',
            'photo' => 'nullable|file',
            'photoFromCamera' => 'nullable|file',
        ];
        $messages = [
            'required' => "لايمكن ان يكون الحقل فارغاً.",
            'email' => "يجب ادخال ايميل بصيغة صحيحة.",
            'phone.string' => "يجب ادخال رقم الموبايل بصيغة صحيحة.",
            'string' => "يجب أدخال نصوص فقط.",
        ];
        $v = Validator::make($request->all(), $rules, $messages);
        $v->validate();
        $student = new Student();
        $student->student_id = $request->input('student_id');
        $student->first_name = $request->input('first_name');
        $student->father_name = $request->input('father_name');
        $student->middle_name = $request->input('middle_name');
        $student->last_name = $request->input('last_name');
        $student->surname = $request->input('surname');
        $student->exam_number = $request->input('exam_number');
        $student->exam_attempt_number = $request->input('exam_attempt_number');
        $student->primary_school_specialty_general_id = $request->input('primary_school_specialty_general_id');
        $student->primary_school_specialty_special_id = $request->input('primary_school_specialty_special_id');
        $student->application_certificate = $request->input('application_certificate');
        $student->primary_school_graduation_year = $request->input('primary_school_graduation_year');
        $student->primary_school_name = $request->input('primary_school_name');
        $student->total_score = $request->input('total_score');
        $student->number_of_modules_studied = $request->input('number_of_modules_studied');
        $student->score_average_before = $request->input('score_average_before');
        $student->score_average_after = $request->input('score_average_after');
        $student->has_institution_certificate = $request->input('has_institution_certificate');
        $student->has_english_module = $request->input('has_english_module');
        $student->enrollment_channel_id = $request->input('enrollment_channel_id');
        $student->date_of_birth = $request->input('date_of_birth');
        $student->place_of_birth = $request->input('place_of_birth');
        $student->gender = $request->input('gender');
        $student->social_status = $request->input('social_status');
        $student->national_id_number = $request->input('national_id_number');
        $student->national_id_issue_date = $request->input('national_id_issue_date');
        $student->national_id_issuer = $request->input('national_id_issuer');
        $student->certificate_of_iraqi_nationality = $request->input('certificate_of_iraqi_nationality');
        $student->certificate_of_iraqi_nationality_issue_date = $request->input('certificate_of_iraqi_nationality_issue_date');
        $student->certificate_of_iraqi_nationality_issuer = $request->input('certificate_of_iraqi_nationality_issuer');
        $student->city = $request->input('city');
        $student->town = $request->input('town');
        $student->township = $request->input('township');
        $student->neighbor = $request->input('neighbor');
        $student->district_no = $request->input('district_no');
        $student->side_street_no = $request->input('side_street_no');
        $student->house_number = $request->input('house_number');
        $student->phone = $request->input('phone');
        $student->house_phone_no = $request->input('house_phone_no');
        $student->email = $request->input('email');
        $student->near_point = $request->input('near_point');
        $student->ministry_name = $request->input('ministry_name');
        $student->department = $request->input('department');
        $student->work_place = $request->input('work_place');
        $student->career_title = $request->input('career_title');
        $student->date_registered = Carbon::today();
        $student->user_id = auth()->user()->id;
        $selected_class = DB::table('student_classes')->where('class_id', '=', $request->input('class_id'))->get()->first();
        $student->acceptance_year = $selected_class->academic_year_id;
        $student->save();

        if ($student != null && $request->has('photo')) {
            $path = Storage::disk('public')->put('student_archive/' . $student->id, $request->file('photo'));
            $student->photo = $path;
            $student->update();
        }/*else if ($student != null && $request->has('photoFromCamera')){
            $path = Storage::disk('public')->put('student_archive/' . $student->id, $request->file('photoFromCamera'));
            $student->photo = $path;
            $student->update();
        }*/

        $enroll = null;
        if ($student != null and $request->has('class_id') && $request->has('enrollment_number')) {
            $admin_order_id = $request->input('enrollment_number');
            $enroll = new Status();
            $enroll->student_id = $student->student_id;
            $enroll->student_class_id = $request->input('class_id');
            $enroll->administrative_order_id = $admin_order_id;
            $status_id = DB::table('administrative_orders')->where('id', '=', $admin_order_id)->first()->academic_status_id;
            $enroll->academic_status_id = $status_id;
            $enroll->is_active = false;
            $enroll->save();

            $pending = new Status();
            $pending->student_id = $student->student_id;
            $pending->student_class_id = $request->input('class_id');
            $pending->academic_status_id = 1;
            $pending->is_active = true;
            $pending->save();
        }


        $fullname = $this->student_fullname($student);
        $student->full_name = $fullname;
        $student->update();
        if ($student != null && $enroll != null && $pending != null) {
            $message = "تم انشاء قيد الطالب بنجاح";
            $class = 'alert-success';
        } else {
            $message = "عفوا لقد حصل خطأ اثناء اضافة الطالب يرجى اعادة المحاولة!";
            $class = 'alert-danger';
        }

        $this->init_fees($enroll);

        return redirect()->back()
            ->with('message', $message)
            ->with('class', $class)
            ->with('student', $student);
    }

    public function student_fullname(Student $student)
    {
        $array = array('fname' => $student->first_name, 'fa_name' => $student->father_name,
            'mname' => $student->middle_name, 'lname' => $student->last_name, 'surname' => $student->surname);
        $name = "";
        foreach ($array as $n) {
            $name .= " " . $n;
        }
        return $name;
    }


    public function init_fees($status)
    {
        $class = DB::table('student_classes')->where('class_id', '=', $status->student_class_id)->first();

        $required_amount = $class->required_annual_fees;
        if (in_array($status->academic_status_id, [11, 9])) {
            $required_amount = $required_amount * 0.5;
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

    public function show($id)
    {

        $std = StudentSearch::student_record($id)->get()->first();

        if ($std == null) {
            abort(404);
        }

        $first_status = DB::table('statuses')
            ->select(['administrative_order_id'])
            ->where('student_id', '=', $id)
            ->orderBy('statuses.id', 'ASC')
            ->limit(1);

        $student_role = DB::table('administrative_orders')
            ->select(['number'])
            ->joinSub($first_status, 'ad', function ($join) {
                $join->on('administrative_orders.id', '=', 'ad.administrative_order_id');
            })
            ->get()->first();


        // all statuses
        $column3 = [
            "class_id",
            "level",
            "shift",
            DB::raw("academic_years.start_year AS start_year"),
            DB::raw("academic_years.end_year AS end_year"),
            DB::raw("colleges.name AS college_name"),
            DB::raw("departments.name AS department_name"),
            DB::raw("departments.id AS department_id"),
            'sta_id',
            'student_class_id',
            'status_name',
            'status_description',
            'status_date',
            'color',
            'icon',
            'has_hold_subject',
            'aboor_passing_status',
            'has_fail',
            'number',
            'admin_date',
            'admin_des',
            'path',
        ];

        $studentClass_statuses = DB::table('student_classes')
            ->select($column3)->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->leftJoin('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id');


        $all_statuses = DB::table('statuses')
            ->select(
                [
                    'statuses.id as sta_id',
                    'statuses.student_class_id',
                    'statuses.created_at as status_date',
                    'statuses.description as status_description',
                    'academic_statuses.name as status_name',
                    'academic_statuses.color',
                    'academic_statuses.icon',
                    'statuses.has_hold_subject',
                    'statuses.has_fail',
                    'statuses.aboor_passing_status',
                    'administrative_orders.number',
                    'administrative_orders.date as admin_date',
                    'administrative_orders.description as admin_des',
                    'administrative_orders.path',
                ]
            )
            ->leftJoin('administrative_orders', 'administrative_orders.id', '=', 'statuses.administrative_order_id')
            ->join('academic_statuses', 'academic_statuses.id', '=', 'statuses.academic_status_id')
            ->where('student_id', '=', $id);


        $studentClass_statuses->joinSub($all_statuses, 'st_with_o', function ($join) {
            $join->on('st_with_o.student_class_id', '=', 'student_classes.class_id');
        })
            ->orderBy('sta_id', 'DESC');

        $statuses = $studentClass_statuses->get();


        $aa = array(1 => 'الاولى', 2 => "الثانية", 3 => "الثالثة", 4 => "الرابعة", 5 => "الخامسه", 6 => "السادسة");
        $user = User::find(session()->get('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'));

if (is_null($user->departments->find($std->department_id))) {
  $chk="no";
}else {
  $chk="yes";
}
        return view('admin.student.show', compact('std', 'aa', 'statuses', 'student_role','chk'));
    }

    public function edit($id)
    {

        $student = (new Student)->newQuery()
            ->join('statuses', 'students.student_id', '=', 'statuses.student_id')
            ->join('student_classes', 'statuses.student_class_id', '=', 'student_classes.class_id')
            ->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->leftJoin('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('times', 'times.id', '=', 'student_classes.time_id')
            ->join('batches', 'batches.id', '=', 'student_classes.batch_id')
            ->join('groups', 'groups.id', '=', 'student_classes.group_id')
            ->join('course_types', 'course_types.id', '=', 'student_classes.type_id')
            ->join('academic_statuses', 'statuses.academic_status_id', '=', 'academic_statuses.id')
            ->join('administrative_orders', 'statuses.administrative_order_id', '=', 'administrative_orders.id');

        $columns = ['*', 'colleges.name as college_name', 'students.id as std_id', 'departments.name as department_name'];
        $student->select($columns);
        $student->where('students.id', '=', $id);
        $std = $student->get()->first();

        $academics = AcademicYear::orderBy('id', "DESC")->get();
        $colleges = College::all();
        $shifts = Shift::all();
        $types = CourseType::all();
        $times = Time::all();
        $batches = Batch::all();
        $groups = Group::all();
        $orders = DB::table('administrative_orders')
            ->where('is_active', '=', 1)
            ->whereIn('academic_status_id', [18, 9, 11])
            ->get();

        $academic_statuses = AcademicStatus::all();
        $student_documents = array();

        $documents = DocumentType::all();

        $student_doc = DB::table('students')->join('student_documents', 'students.student_id', '=',
            'student_documents.student_id')
            ->where('students.id', '=', $std->std_id)
            ->get();

        foreach ($documents as $doc) {
            $a = array('id' => $doc->id, 'title' => $doc->document_type, 'link' => null, 'input_name' => $doc->input_name);
            foreach ($student_doc as $std_doc) {
                if ($doc->id == $std_doc->document_type_id) {
                    $a['link'] = '/storage/' . $std_doc->path;
                }
            }
            array_push($student_documents, $a);
        }
        $grad_years = primarySchoolGraduationYear::orderBy('start_year', "DESC")->get();
        $channels = EnrollmentChannel::all();
        $towns = Town::all();
        $psg = DB::table('primary_school_specialty_general')->get();
        return view("admin.student.update", compact('towns', 'channels', 'grad_years', 'psg', 'academic_statuses', 'student_documents', 'std', 'colleges', 'academics', 'shifts', 'types', 'times', 'batches', 'groups', 'orders'));
    }

    public function update(Request $request, Student $student)
    {
        $rules = [
            'first_name' => 'required|string|min:3',
            'father_name' => 'nullable|string|min:3',
            'middle_name' => 'nullable|string|min:3',
            'last_name' => 'nullable|string|min:3',
            'surname' => 'nullable|string|min:3',
            'enrollment_number' => ['nullable', new Number()],
            'class_id' => ['required', new Number()],
            'exam_number' => "nullable|string",
            'exam_attempt_number' => "nullable|integer|in:0,1,2,3",
            'primary_school_specialty_general_id' => ['required', new Number()],
            'primary_school_specialty_special_id' => ['nullable', new Number()],
            'application_certificate' => 'nullable|string',
            'primary_school_graduation_year' => 'nullable|string',
            'primary_school_name' => 'required|string',
            'total_score' => ['nullable', new Number(),
                function ($attribute, $value, $fail) {
                    $total_limit = \request('number_of_modules_studied') * 100;
                    if (is_numeric($value) && $value > $total_limit) {
                        $fail('يجب ان يكون المجموع اقل من ' . $total_limit . ' درجة.');
                    } elseif (is_numeric($value) && $value < 0) {
                        $fail('قيمة المجموع المدخلة غير صالحة.');
                    } elseif (!is_numeric($value)) {
                        $fail('يجب أدخال ارقام فقط');
                    }
                }
            ],
            'number_of_modules_studied' => ['nullable', new Number()],
            'score_average_before' => ['nullable', new Number()],
            'score_average_after' => ['required', new Number()],
            'has_institution_certificate' => 'nullable|in:0,1',
            'has_english_module' => 'nullable|in:0,1',
            'enrollment_channel_id' => 'nullable|integer',
            'date_of_birth' => 'required|date',
            'place_of_birth' => 'nullable|string',
            'gender' => 'nullable|string',
            'social_status' => 'nullable|string',
            'national_id_number' => 'required|string',
            'national_id_issue_date' => 'nullable|string',
            'national_id_issuer' => 'nullable|string',
            'certificate_of_iraqi_nationality' => 'nullable|string',
            'certificate_of_iraqi_nationality_issue_date' => 'nullable|date',
            'certificate_of_iraqi_nationality_issuer' => 'nullable|string',
            'city' => ['required', new Number()],
            'town' => 'nullable|string',
            'township' => 'nullable|string',
            'neighbor' => 'nullable|string',
            'district_no' => 'nullable|string',
            'side_street_no' => 'nullable|string',
            'house_number' => 'nullable|string',
            'phone' => 'nullable|string',
            'house_phone_no' => 'nullable|string',
            'email' => 'nullable|email',
            'near_point' => 'nullable|string',
            'ministry_name' => 'nullable|string',
            'department' => 'nullable|string',
            'work_place' => 'nullable|string',
            'career_title' => 'nullable|string',
            'photo' => 'nullable|file',
        ];

        //$v = Validator::make($request->all(), $rules, []);
        //$v->validate();
        $student->first_name = $request->input('first_name');
        $student->father_name = $request->input('father_name');
        $student->middle_name = $request->input('middle_name');
        $student->last_name = $request->input('last_name');
        $student->surname = $request->input('surname');
        $student->exam_number = $request->input('exam_number');
        $student->exam_attempt_number = $request->input('exam_attempt_number');
        $student->primary_school_specialty_general_id = $request->input('primary_school_specialty_general_id');
        $student->primary_school_specialty_special_id = $request->input('primary_school_specialty_special_id');
        $student->application_certificate = $request->input('application_certificate');
        $student->primary_school_graduation_year = $request->input('primary_school_graduation_year');
        $student->primary_school_name = $request->input('primary_school_name');
        $student->total_score = $request->input('total_score');
        $student->number_of_modules_studied = $request->input('number_of_modules_studied');
        $student->score_average_before = $request->input('score_average_before');
        $student->score_average_after = $request->input('score_average_after');
        $student->has_institution_certificate = $request->input('has_institution_certificate');
        $student->has_english_module = $request->input('has_english_module');
        $student->enrollment_channel_id = $request->input('enrollment_channel_id');
        $student->date_of_birth = $request->input('date_of_birth');
        $student->place_of_birth = $request->input('place_of_birth');
        $student->gender = $request->input('gender');
        $student->social_status = $request->input('social_status');
        $student->national_id_number = $request->input('national_id_number');
        $student->national_id_issue_date = $request->input('national_id_issue_date');
        $student->national_id_issuer = $request->input('national_id_issuer');
        $student->certificate_of_iraqi_nationality = $request->input('certificate_of_iraqi_nationality');
        $student->certificate_of_iraqi_nationality_issue_date = $request->input('certificate_of_iraqi_nationality_issue_date');
        $student->certificate_of_iraqi_nationality_issuer = $request->input('certificate_of_iraqi_nationality_issuer');
        $student->city = $request->input('city');
        $student->town = $request->input('town');
        $student->township = $request->input('township');
        $student->neighbor = $request->input('neighbor');
        $student->district_no = $request->input('district_no');
        $student->side_street_no = $request->input('side_street_no');
        $student->house_number = $request->input('house_number');
        $student->phone = $request->input('phone');
        $student->house_phone_no = $request->input('house_phone_no');
        $student->email = $request->input('email');
        $student->near_point = $request->input('near_point');
        $student->ministry_name = $request->input('ministry_name');
        $student->department = $request->input('department');
        $student->work_place = $request->input('work_place');
        $student->career_title = $request->input('career_title');
        $student->date_registered = Carbon::today();
        $student->user_id = auth()->user()->id;
        $fullname = $this->student_fullname($student);
        $student->full_name = $fullname;
        $student->update();

        if ($student != null && $request->has('photo')) {
            $exists = Storage::disk('public')->exists('student_archive/' . $student->id, $request->file('photo'));
            if ($exists) {
                Storage::disk('public')->delete('student_archive/' . $student->id, $request->file('photo'));
            }
            $path = Storage::disk('public')->put('student_archive/' . $student->id, $request->file('photo'));
            $student->photo = $path;
            $student->update();
        }


        $doc_t = DocumentType::all();
        foreach ($doc_t as $d) {
            if ($request->has($d->input_name)) {
                $f = $request->file($d->input_name);
                $exists = Storage::disk('public')->exists('student_archive/' . $student->id, $f);
                if ($exists) {
                    Storage::disk('public')->delete('student_archive/' . $student->id, $f);
                }
                $path = Storage::disk('public')->put('student_archive/' . $student->id, $f);
                $new_doc = new StudentDocument();
                $new_doc->student_id = $student->student_id;
                $new_doc->path = $path;
                $new_doc->document_type_id = $d->id;
                $new_doc->save();
            }
        }


        return back()->with('success', "تم تعديل قيد الطالب بنجاح");
    }

    public function destroy(Student $student)
    {
        //
    }
}
