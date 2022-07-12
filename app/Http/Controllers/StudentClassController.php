<?php

namespace App\Http\Controllers;

use App\AcademicYear;
use App\Batch;
use App\ClassSearch;
use App\College;
use App\CourseType;
use App\Department;
use App\Group;
use App\Level;
use App\Rules\Number;
use App\Shift;
use App\StudentClass;
use App\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class StudentClassController extends Controller
{
    public function __construct()
    {
        //$this->middleware('permission:ادارة السنوات الاكاديمية');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
        $colleges = College::all();
        $shifts = Shift::all();
        $types = CourseType::all();
        $times = Time::all();
        $batches = Batch::all();
        $groups = Group::all();

        return view('admin.classes.index', compact('colleges', 'academics', 'shifts', 'types', 'times', 'batches', 'groups'));

    }

    public function postInsertAcademic(Request $request)
    {
        $rules = [
            'start_year' => [
                'required',
                'unique:academic_years'
            ],
            'end_year' => [
                'required',
                'unique:academic_years',
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
        $message = [
            "start_year.required" => "يجب ادخال بداية السنة",
            "end_year.required" => "يجب ادخال نهاية السنة",
            'unique' => 'السنة مضافة مسبقا.',
        ];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();


        if ($request->ajax()) {
            return response()->json(AcademicYear::create($request->all()), 200);
        }
        return abort(401);
    }

    public function postInsertCollege(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        if ($request->ajax()) {
            return response()->json(College::create($request->all()), 200);
        }
        return abort(401);
    }

    public function postInsertDepartment(Request $request)
    {
        $this->validate($request, [
            'college_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        if ($request->ajax()) {
            return response()->json(Department::create($request->all()), 200);
        }
        return abort(401);
    }

    public function showDepartment(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(Department::where('college_id', $request->input('college_id'))->get(), 200);
        }
        return abort(401);
    }

    public function postInsertLevel(Request $request)
    {
        $this->validate($request, [
            'level' => ['required', new Number()],
            'description' => 'nullable|string',
            'college_id' => ['required', new Number()],
            'department_id' => Rule::requiredIf(function () use ($request) {
                $count = Department::where('college_id', $request->input('college_id'))->get()->count();
                return $count > 0 ? true : false;
            }),
        ]);

        if ($request->ajax()) {
            return response(Level::create($request->all()));
        }
        return abort(401);
    }

    public function showLevel(Request $request)
    {


        if ($request->ajax()) {
            $this->validate($request, [
                'college_id' => 'required',
                'department_id' => Rule::requiredIf(function () use ($request) {
                    $count = Department::where('college_id', $request->input('college_id'))->get()->count();
                    return $count > 0 ? true : false;
                }),
            ]);
            $criterial = array();
            if ($request->input('college_id') != "" && $request->input('department_id') == "") {
                $criterial = array('college_id' => $request->input('college_id'));

            } elseif ($request->input('college_id') != "" && $request->input('department_id') != "") {
                $criterial = array(
                    'college_id' => $request->input('college_id'),
                    'department_id' => $request->input('department_id'),
                );
            }
            $levels = DB::table('levels')->where($criterial)->get();
            return response()->json($levels, 200);
        }
        return abort(401);
    }

    public function postInsertShift(Request $request)
    {
        $this->validate($request, [
            'shift' => 'required',
        ]);

        if ($request->ajax()) {
            return response(Shift::create($request->all()));
        }
    }

    public function postInsertTypes(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
        ]);

        if ($request->ajax()) {
            return response(CourseType::create($request->all()));
        }
    }

    public function postInsertTime(Request $request)
    {
        $this->validate($request, [
            'time' => 'required',
        ]);

        if ($request->ajax()) {
            return response(Time::create($request->all()));
        }
    }

    public function postInsertBatch(Request $request)
    {
        $this->validate($request, [
            'batch' => 'required',
        ]);

        if ($request->ajax()) {
            return response(Batch::create($request->all()));
        }
    }

    public function postInsertGroup(Request $request)
    {
        $this->validate($request, [
            'group' => 'required',
        ]);

        if ($request->ajax()) {
            return response(Group::create($request->all()));
        }
    }

    public function postCreateClass(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'academic_year_id' => 'required',
                'college_id' => 'required',
                'department_id' => Rule::requiredIf(function () use ($request) {
                    $count = Department::where('college_id', $request->input('college_id'))->get()->count();
                    return $count > 0 ? true : false;
                }),
                'level_id' => 'required',
                'shift_id' => 'required',
                'type_id' => 'required',
                'time_id' => 'required',
                'group_id' => 'required',
                'batch_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ];
            $messages = ['required' => "الحقل مطلوب."];
            $validator = Validator::make($request->all(), $rules, $messages);


            $validator->after(function ($validator) {
                $existed = StudentClass::where('academic_year_id', '=', \request('academic_year_id'))
                    ->where('college_id', '=', \request('college_id'))
                    ->where('department_id', '=', \request('department_id'))
                    ->where('level_id', '=', \request('level_id'))
                    ->where('shift_id', '=', \request('shift_id'))
                    ->where('type_id', '=', \request('type_id'))
                    ->where('time_id', '=', \request('time_id'))
                    ->where('group_id', '=', \request('group_id'))
                    ->where('batch_id', '=', \request('batch_id'))
                    ->where('start_date', '=', \request('start_date'))
                    ->where('end_date', '=', \request('end_date'))
                    ->count();

                if ($existed > 0) {
                    $validator->errors()->add('course_existed', 'الكورس مضاف مسبقا يرجى التأكد من معلومات الكورس!');
                }

            });

            $validator->validate();

            if (!$validator->fails()) {
                $student_class = StudentClass::firstOrCreate($request->except('_token'));
                return response()->json($student_class, 200);
            }


        }
    }

    public function showClassInfo(Request $request)
    {
        
        if ($request->ajax()) {
            $data = ClassSearch::apply($request);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('academic_year', function ($row) {
                    return $row->start_year . '-' . $row->end_year;
                })
                ->addColumn('college_department', function ($row) {

                    $college_dpt = $row->college_name;
                    if ($row->department_name != null || $row->department_name != "") {
                        $college_dpt .= '/' . $row->department_name;
                    }
                    return $college_dpt;
                })
                ->addColumn('level_group', function ($row) {
                    return $row->group . '-' . $row->level;
                })
                ->addColumn('action', 'admin.classes.datatableButtons')
                ->addColumn('delete_action', 'admin.classes.datatableDeleteButtons')
                ->rawColumns(['action', 'delete_action'])
                ->make(true);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\StudentClass $studentClass
     * @return \Illuminate\Http\Response
     */
    public function show(StudentClass $studentClass)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\StudentClass $studentClass
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentClass $studentClass)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\StudentClass $studentClass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentClass $studentClass)
    {
        //
    }


    public function destroy(Request $request)
    {
        $rules = [
            'class_id' => "required"
        ];
        $v = Validator::make($request->all(), $rules);
        $v->validate();

        DB::table('student_classes')->where('class_id', '=', $request->input('class_id'))
            ->update(['active' => 0]);
        return back();
    }
}
