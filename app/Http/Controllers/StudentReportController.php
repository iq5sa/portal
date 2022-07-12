<?php

namespace App\Http\Controllers;

use App\AcademicStatus;
use App\AcademicYear;
use App\College;
use App\Exports\ByTownStudentReportExport;
use App\Exports\IraqiByDOBStudentReportExport;
use App\Exports\IraqiByDOBStudentReportSheet;
use App\Exports\IraqiByStagesStudentReportExport;
use App\Exports\IraqiEnrolledStudentReportExport;
use App\Exports\StudentReportExport;
use App\primarySchoolGraduationYear;
use App\Shift;
use App\StudentSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
class StudentReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:تحميل تقارير التسجيل');
    }

    public function table1(Request $request){
      $generals=DB::table('primary_school_specialty_general')->get();
      $departments=DB::table('departments')->select('departments.id','departments.name as deptname','colleges.id as colid','colleges.name as colname')
      ->leftjoin('colleges','colleges.id','=','departments.college_id')->get();

foreach ($departments as $keyd => $department) {
  $totalmale1=0;
  $totalfemale1=0;
  $totalmale2=0;
  $totalfemale2=0;
      foreach ($generals as $keyg => $general) {

          $statusesmales1=DB::table('statuses')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('primary_school_specialty_general','primary_school_specialty_general.id','students.primary_school_specialty_general_id')
          ->where('levels.level','1')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
          ->where('students.primary_school_specialty_general_id',$general->id)
          ->where('students.gender','0')->where('student_classes.college_id',$department->colid)->where('student_classes.shift_id',1)
          ->where('student_classes.department_id',$department->id)->get();

          $statusesfemale1=DB::table('statuses')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('primary_school_specialty_general','primary_school_specialty_general.id','students.primary_school_specialty_general_id')
          ->where('levels.level','1')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
          ->where('students.primary_school_specialty_general_id',$general->id)
          ->where('students.gender','1')->where('student_classes.college_id',$department->colid)->where('student_classes.shift_id',1)
          ->where('student_classes.department_id',$department->id)->get();

          $statusesmales2=DB::table('statuses')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('primary_school_specialty_general','primary_school_specialty_general.id','students.primary_school_specialty_general_id')
          ->where('levels.level','1')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
          ->where('students.primary_school_specialty_general_id',$general->id)
          ->where('students.gender','0')->where('student_classes.college_id',$department->colid)->where('student_classes.shift_id',2)
          ->where('student_classes.department_id',$department->id)->get();

          $statusesfemale2=DB::table('statuses')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('primary_school_specialty_general','primary_school_specialty_general.id','students.primary_school_specialty_general_id')
          ->where('levels.level','1')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
          ->where('students.primary_school_specialty_general_id',$general->id)
          ->where('students.gender','1')->where('student_classes.college_id',$department->colid)->where('student_classes.shift_id',2)
          ->where('student_classes.department_id',$department->id)->get();

          $arrayName = array($general->general,count($statusesmales1),count($statusesfemale1),count($statusesmales1)+count($statusesfemale1));
          $arrgen1[$keyg] = $arrayName;
          $totalmale1=$totalmale1+count($statusesmales1);
          $totalfemale1=$totalfemale1+count($statusesfemale1);

          $arrayName = array($general->general,count($statusesmales2),count($statusesfemale2),count($statusesmales2)+count($statusesfemale2));
          $arrgen2[$keyg] = $arrayName;
          $totalmale2=$totalmale2+count($statusesmales2);
          $totalfemale2=$totalfemale2+count($statusesfemale2);

        }



        $statusesmalessec1=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('primary_school_specialty_general','primary_school_specialty_general.id','students.primary_school_specialty_general_id')
        ->where('levels.level','2')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('students.gender','0')->where('student_classes.college_id',$department->colid)->where('student_classes.shift_id',1)
        ->where('student_classes.department_id',$department->id)->get();

        $statusesfemalesec1=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('primary_school_specialty_general','primary_school_specialty_general.id','students.primary_school_specialty_general_id')
        ->where('levels.level','2')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('students.gender','1')->where('student_classes.college_id',$department->colid)->where('student_classes.shift_id',1)
        ->where('student_classes.department_id',$department->id)->get();

        $statusesmalessec2=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('primary_school_specialty_general','primary_school_specialty_general.id','students.primary_school_specialty_general_id')
        ->where('levels.level','2')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('students.gender','0')->where('student_classes.college_id',$department->colid)->where('student_classes.shift_id',2)
        ->where('student_classes.department_id',$department->id)->get();

        $statusesfemalesec2=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('primary_school_specialty_general','primary_school_specialty_general.id','students.primary_school_specialty_general_id')
        ->where('levels.level','2')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('students.gender','1')->where('student_classes.college_id',$department->colid)->where('student_classes.shift_id',2)
        ->where('student_classes.department_id',$department->id)->get();


        $arrayName = array(count($statusesmalessec1),count($statusesfemalesec1));
        $arrgensec1[$keyd] = $arrayName;

        $arrayName = array(count($statusesmalessec2),count($statusesfemalesec2));
        $arrgensec2[$keyd] = $arrayName;

        $arrayName = array($department->colname,$department->deptname,$arrgen1,$totalmale1,$totalfemale1,count($statusesmalessec1),count($statusesfemalesec1));
        $arrdept1[$keyd] = $arrayName;
        $arrayName = array($department->colname,$department->deptname,$arrgen2,$totalmale2,$totalfemale2,count($statusesmalessec2),count($statusesfemalesec2));
        $arrdept2[$keyd] = $arrayName;
      }
        return view('admin.reports.student_reports.table1')->with('arrgen1',$arrdept1)->with('arrgen2',$arrdept2)->with('generals',$generals);
    }

    public function table2(Request $request){
      $towns=DB::table('towns')->get();
      $totalmale1=0;
      $totalfemale1=0;
      $totalmale2=0;
      $totalfemale2=0;
      $totalmalesec1=0;
      $totalfemalesec1=0;
      $totalmalesec2=0;
      $totalfemalesec2=0;
      foreach ($towns as $key => $town) {
        $statusesmales1=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('towns','towns.id','students.city')
        ->where('levels.level','1')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('towns.id',$town->id)->where('students.gender','0')->where('student_classes.shift_id',1)->get();

        $statusesfemale1=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('towns','towns.id','students.city')
        ->where('levels.level','1')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('towns.id',$town->id)->where('students.gender','1')->where('student_classes.shift_id',1)->get();

        $statusesmales2=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('towns','towns.id','students.city')
        ->where('levels.level','2')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('towns.id',$town->id)->where('students.gender','0')->where('student_classes.shift_id',1)->get();

        $statusesfemale2=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('towns','towns.id','students.city')
        ->where('levels.level','2')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('towns.id',$town->id)->where('students.gender','1')->where('student_classes.shift_id',1)->get();

        $statusesmalessec1=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('towns','towns.id','students.city')
        ->where('levels.level','1')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('towns.id',$town->id)->where('students.gender','0')->where('student_classes.shift_id',2)->get();

        $statusesfemalesec1=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('towns','towns.id','students.city')
        ->where('levels.level','1')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('towns.id',$town->id)->where('students.gender','1')->where('student_classes.shift_id',2)->get();

        $statusesmalessec2=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('towns','towns.id','students.city')
        ->where('levels.level','2')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('towns.id',$town->id)->where('students.gender','0')->where('student_classes.shift_id',2)->get();

        $statusesfemalesec2=DB::table('statuses')
        ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
        ->leftjoin('students','students.student_id','statuses.student_id')
        ->leftjoin('levels','levels.id','student_classes.level_id')
        ->leftjoin('towns','towns.id','students.city')
        ->where('levels.level','2')->where('is_active','1')->where('academic_status_id','1')->where('acceptance_year','3')
        ->where('towns.id',$town->id)->where('students.gender','1')->where('student_classes.shift_id',2)->get();

        $totalmale1=$totalmale1+count($statusesmales1);
        $totalfemale1=$totalfemale1+count($statusesfemale1);
        $totalmale2=$totalmale2+count($statusesmales2);
        $totalfemale2=$totalfemale2+count($statusesfemale2);
        $totalmalesec1=$totalmalesec1+count($statusesmalessec1);
        $totalfemalesec1=$totalfemalesec1+count($statusesfemalesec1);
        $totalmalesec2=$totalmalesec2+count($statusesmalessec2);
        $totalfemalesec2=$totalfemalesec2+count($statusesfemalesec2);

        $arrayName = array($town->town_name,count($statusesmales1),count($statusesfemale1),count($statusesmales2),count($statusesfemale2));
        $arrdept1[$key] = $arrayName;
        $arrayName = array($town->town_name,count($statusesmalessec1),count($statusesfemalesec1),count($statusesmalessec2),count($statusesfemalesec2));
        $arrdept2[$key] = $arrayName;

      }
      $arrayName = array($totalmale1,$totalfemale1,$totalmale2,$totalfemale2,$arrdept1);
      $arr1[$key] = $arrayName;
      $arrayName = array($totalmalesec1,$totalfemalesec1,$totalmalesec2,$totalfemalesec2,$arrdept2);
      $arr2[$key] = $arrayName;
return view('admin.reports.student_reports.table2')->with('arr1',$arr1)->with('arr2',$arr2)->with('towns',$towns);
    }

    public function iraqi_students_enrolled(Request $request)
    {
        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
        $shifts = Shift::all();
        return view('admin.reports.student_reports.iraqi_students_enrolled', compact('shifts', 'academics'));

    }

    public function iraqi_students_enrolled_download(Request $request)
    {
        $rules = [
            'academic_year_id' => 'required'
        ];
        $message = ['required' => 'يجب ادخال بيانات الحقل'];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $columns = [
            'colleges.name as college_name',
            'departments.name as department_name',
            'departments.id as department_id',
            'class_id'
        ];

        $studentClass = DB::table('student_classes')->select($columns)
            ->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->join('departments', 'departments.id', '=', 'student_classes.department_id')
            ->where('academic_years.id', '=', $request->input('academic_year_id'));

        if ($request->input('shift_id') != null) {
            $studentClass->where('student_classes.shift_id', '=', $request->input('shift_id'));
        }
        $students = DB::table('students')
            ->select(['gender', 'primary_school_specialty_general_id', 'college_name','department_id' ,'department_name', DB::raw('count(*) as number')])
            ->join('statuses', 'students.student_id', '=', 'statuses.student_id')
            ->where('statuses.academic_status_id', '=', 18)
            ->where('primary_school_specialty_general_id', '!=', 0);

        $students->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'statuses.student_class_id');
        })
            ->groupBy(['department_id', 'students.primary_school_specialty_general_id', 'gender'])
            ->orderBy('department_id', 'asc');

        $data = $students->get();


        $array = [];
        foreach ($data as $d) {
            $array[$d->department_name][] = $d;
        }




        $academic_year = AcademicYear::where('id', '=', $request->input('academic_year_id'))->first();
        return Excel::download(new IraqiEnrolledStudentReportExport($array, $academic_year), 'iraqi_student_enrolled.xlsx');

    }

    public function iraqi_students_by_stages()
    {
        $academics = AcademicYear::where('active_year','=',1)->orderBy('start_year', "DESC")->get();
        $shifts = Shift::all();
        return view('admin.reports.student_reports.iraqi_students_by_stages', compact('shifts', 'academics'));

    }

    public function iraqi_students_by_stages_download(Request $request)
    {
        $rules = [
            'academic_year_id' => 'required'
        ];
        $message = ['required' => 'يجب ادخال بيانات الحقل'];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $columns = [
            'colleges.name as college_name',
            'departments.name as department_name',
            'departments.id as department_id',
            'class_id',
            'levels.level as level'
        ];

        $studentClass = DB::table('student_classes')->select($columns)
            ->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id')
            ->join('departments', 'departments.id', '=', 'student_classes.department_id')
            ->where('academic_years.id', '=', $request->input('academic_year_id'));

        if ($request->input('shift_id') != null) {
            $studentClass->where('student_classes.shift_id', '=', $request->input('shift_id'));
        }


        $students = DB::table('students')
            ->select(['level', 'gender', 'college_name', 'department_name', DB::raw('count(*) as number')])
            ->join('statuses', 'students.student_id', '=', 'statuses.student_id')
            ->where('statuses.academic_status_id', '=', 1)
            ->where('statuses.is_active', '=', 1);

        $students->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'statuses.student_class_id');
        })
            ->groupBy(['department_id', 'c.level', 'gender'])
            ->orderBy('department_id', 'asc');

        $data = $students->get();


        $array = [];
        foreach ($data as $d) {
            $array[$d->department_name][] = $d;
        }


        $academic_year = AcademicYear::where('id', '=', $request->input('academic_year_id'))->first();
        return Excel::download(new IraqiByStagesStudentReportExport($array, $academic_year), 'iraqi_student_enrolled.xlsx');

    }

    public function index()
    {
        $academics = AcademicYear::orderBy('start_year','asc')->get();
        $colleges = College::all();
        $shifts = Shift::all();
        $types = AcademicStatus::all();
        return view('admin.reports.student_reports.index', compact('colleges', 'academics', 'shifts', 'types'));
    }

    public function downloadReport(Request $request)
    {

        $rules = [
            'academic_year_id' => 'required',
            'academic_status_id' => 'required'
        ];
        $message = ['academic_year_id.required' => "يجب اختيار العام الدراسي", 'academic_status_id.required' => 'يجب اختيار الحالة الاكاديمية للطالب'];
        Validator::make($request->all(), $rules, $message)->validate();

        $columns = [
            'students.student_id',
            "students.full_name",
            "students.gender",
            "level",
            "shift",
            "college_name",
            "department_name",
            "academic_status_name",
        ];


        $statuses = DB::table('statuses')->select(['academic_statuses.name as academic_status_name','student_id','student_class_id'])
            ->join('academic_statuses', 'statuses.academic_status_id', '=', 'academic_statuses.id')
            ->where('academic_statuses.id', "=", $request->input('academic_status_id'));



        $studentClass = DB::table('student_classes')->select(['class_id',"colleges.name as college_name", "departments.name as department_name", "levels.level",
            "shifts.shift"])->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->join('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id')
            ->where('academic_years.id', '=', $request->input('academic_year_id'));

        // Search for a user based on their college_id.
        if ($request->input('college_id') != null) {
            $studentClass->where('student_classes.college_id', "=", $request->input('college_id'));
        }

        // Search for a user based on their department_id.
        if ($request->input('department_id') != null) {
            $studentClass->where('student_classes.department_id', "=", $request->input('department_id'));
        }

        // Search for a user based on their levels.
        if ($request->input('level_id') != null) {
            $studentClass->where('student_classes.level_id', "=", $request->input('level_id'));
        }

        // Search for a user based on their shifts.
        if ($request->input('shift_id') != null) {
            $studentClass->where('student_classes.shift_id', "=", $request->input('shift_id'));
        }

        $students = DB::table('students')->select($columns);
        $students->joinSub($statuses, 'st', function ($join) {
            $join->on('st.student_id', '=', 'students.student_id');
        });
        $students->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'st.student_class_id');
        });

        $students->orderBy('students.full_name','asc');

        return Excel::download(new StudentReportExport($students->get()), 'report.xlsx');
    }

    public function students_report_by_town()
    {
        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
        $shifts = Shift::all();
        return view('admin.reports.student_reports.students_by_town', compact('shifts', 'academics'));

    }

    public function students_report_by_town_download(Request $request)
    {
        $rules = [
            'academic_year_id' => 'required'
        ];
        $message = ['required' => 'يجب ادخال بيانات الحقل'];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $studentClass = DB::table('student_classes')->select(['class_id'])
            ->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->where('academic_years.id', '=', $request->input('academic_year_id'));

        if ($request->input('shift_id') != null) {
            $studentClass->where('student_classes.shift_id', '=', $request->input('shift_id'));
        }

        $columns = [DB::raw('towns.id as town_id'), 'town_name', 'gender', DB::raw('count(*) as number')];
        $students = DB::table('students')
            ->select($columns)
            ->join('statuses', 'students.student_id', '=', 'statuses.student_id')
            ->join('towns', 'towns.id', '=', 'students.city')
            ->where('statuses.academic_status_id', '=', 18);

        $students->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'statuses.student_class_id');
        })
            ->groupBy(['town_id', 'town_name', 'students.gender']);

        $f_data = DB::table('towns')->select(['towns.id as town_id', 'towns.town_name as t_name', 'gender', 'number'])
            ->leftJoinSub($students, 'st', function ($join) {
                $join->on('st.town_id', '=', 'towns.id');
            })->orderBy('towns.id', 'asc');

        $data = $f_data->get();

        $array = [];
        foreach ($data as $d) {
            $array[$d->town_id][] = $d;
        }


        $academic_year = AcademicYear::where('id', '=', $request->input('academic_year_id'))->first();
        return Excel::download(new ByTownStudentReportExport($array, $academic_year), 'iraqi_student_enrolled.xlsx');

    }

    public function failed_students()
    {
        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
        $shifts = Shift::all();
        return view('admin.reports.student_reports.failed_students', compact('shifts', 'academics'));
    }

    public function failed_students_download(Request $request)
    {
        $rules = [
            'academic_year_id' => 'required'
        ];
        $message = ['required' => 'يجب ادخال بيانات الحقل'];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();


    }

    public function student_by_birth_date_form(){
        $academics = AcademicYear::where('active_year','=',1)->orderBy('start_year', "DESC")->get();
        $shifts = Shift::all();
        return view('admin.reports.student_reports.students_by_date_of_birth', compact('shifts', 'academics'));

    }

    public function table3(Request $request){

        //dd(DB::select($ages));
      $id = $request->input('academic_year_id');
      $shift = $request->input('shift_id');

      $levels = DB::table('levels')->distinct()->get('level');

      $colleges=DB::table('colleges')->get();
      foreach ($colleges as $keyd => $department) {
        foreach ($levels as $keyl => $level) {
          $studentsmalemornning=DB::table('statuses')->select('date_of_birth','colleges.name','level')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('colleges','colleges.id','student_classes.college_id')->where('students.gender','0')
          ->where('levels.level',$level->level)->where('is_active','1')->where('academic_status_id','1')
          ->where('student_classes.shift_id',1)->where('student_classes.college_id',$department->id)->get();

          $studentsfemalemornning=DB::table('statuses')->select('date_of_birth','colleges.name','level')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('colleges','colleges.id','student_classes.college_id')->where('students.gender','1')
          ->where('levels.level',$level->level)->where('is_active','1')->where('academic_status_id','1')
          ->where('student_classes.shift_id',1)->where('student_classes.college_id',$department->id)->get();

          $studentsmaleevening=DB::table('statuses')->select('date_of_birth','colleges.name','level')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('colleges','colleges.id','student_classes.college_id')->where('students.gender','0')
          ->where('levels.level',$level->level)->where('is_active','1')->where('academic_status_id','1')
          ->where('student_classes.shift_id',2)->where('student_classes.college_id',$department->id)->get();

          $studentsfemaleevening=DB::table('statuses')->select('date_of_birth','colleges.name','level')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('colleges','colleges.id','student_classes.college_id')->where('students.gender','1')
          ->where('levels.level',$level->level)->where('is_active','1')->where('academic_status_id','1')
          ->where('student_classes.shift_id',2)->where('student_classes.college_id',$department->id)->get();

          $v17 =0;  $v18 =0;$v19 =0;  $v20 =0;$v21 =0;$v22 =0;$v23 =0;  $v24 =0;$v25 =0;  $v26 =0;$v27 =0;$v28 =0;  $v29 =0;$v30 =0;$v31 =0;$v32 =0;
          $v33 =0;$v34 =0;$v35 =0;$v36 =0;$v37 =0;$v38 =0;$v39 =0;$v40 =0;$v41 =0;$v42 =0;$v43 =0;$v44 =0;$v45 =0;$v46 =0;$v47 =0;$v48 =0;
          $v49 =0;$v50 =0;$v51 =0;$totalmalemornning=0;
          foreach ($studentsmalemornning as $key => $value) {
            $now = Carbon::now();
            $date = Carbon::parse($value->date_of_birth);
            $diff = $date->diffInYears($now);
             if ($diff == 17){$v17=$v17+1;}
             elseif ($diff == 18) {$v18=$v18+1;}
             elseif ($diff == 19) {$v19=$v19+1;}
             elseif ($diff == 20) {$v20=$v20+1;}
             elseif ($diff == 21) {$v21=$v21+1;}
             elseif ($diff == 22) {$v22=$v22+1;}
             elseif ($diff == 23) {$v23=$v23+1;}
             elseif ($diff == 24) {$v24=$v24+1;}
             elseif ($diff == 25) {$v25=$v25+1;}
             elseif ($diff == 26) {$v26=$v26+1;}
             elseif ($diff == 27) {$v27=$v27+1;}
             elseif ($diff == 28) {$v28=$v28+1;}
             elseif ($diff == 29) {$v29=$v29+1;}
             elseif ($diff == 30) {$v30=$v30+1;}
             elseif ($diff == 31) {$v31=$v31+1;}
             elseif ($diff == 32) {$v32=$v32+1;}
             elseif ($diff == 33) {$v33=$v33+1;}
             elseif ($diff == 34) {$v34=$v34+1;}
             elseif ($diff == 35) {$v35=$v35+1;}
             elseif ($diff == 36) {$v36=$v36+1;}
             elseif ($diff == 37) {$v37=$v37+1;}
             elseif ($diff == 38) {$v38=$v38+1;}
             elseif ($diff == 39) {$v39=$v39+1;}
             elseif ($diff == 40) {$v40=$v40+1;}
             elseif ($diff == 41) {$v41=$v41+1;}
             elseif ($diff == 42) {$v42=$v42+1;}
             elseif ($diff == 43) {$v43=$v43+1;}
             elseif ($diff == 44) {$v44=$v44+1;}
             elseif ($diff == 45) {$v45=$v45+1;}
             elseif ($diff == 46) {$v46=$v46+1;}
             elseif ($diff == 47) {$v47=$v47+1;}
             elseif ($diff == 48) {$v48=$v48+1;}
             elseif ($diff == 49) {$v49=$v49+1;}
             elseif ($diff >= 50) {$v50=$v50+1;}
             elseif ($diff == 'non') {$v51=$v51+1;}

          }
          $totalmalemornning=$v17+$v18+$v19+$v20+$v21+$v22+$v23+$v24+$v25+$v26+$v27+$v28+$v29+$v30+$v31+$v32+
          $v33+$v34+$v35+$v36+$v37+$v38+$v39+$v40+$v41+$v42+$v43+$v44+$v45+$v46+$v47+$v48+$v49+$v50+$v51;
          $array = array("17" =>$v17,"18" =>$v18,"19" =>$v19,"20"=>$v20,"21"=>$v21,"22" =>$v22,"23" =>$v23,"24" =>$v24,"25" =>$v25,"26" =>$v26,
          "27" =>$v27,"28" =>$v28,"29" =>$v29,"30" =>$v30,"31" =>$v31,"32" =>$v32,"33" =>$v33,"34" =>$v34,"35" =>$v35,"36" =>$v36,"37" =>$v37,
          "38" =>$v38,"39" =>$v39,"40" =>$v40,"41" =>$v41,"42" =>$v42,"43" =>$v43,"44" =>$v44,"45" =>$v45,"46" =>$v46,"47" =>$v47,"48" =>$v48,
          "49" =>$v49,"خمسين واكثر" =>$v50,"غير معرف" =>$v51,);
          $array = array("study" =>'صباحي',"gender"=>'ذكر',"college" =>$value->name,"level" =>$value->level,"total"=>$totalmalemornning,"age"=>$array,);
          $arrstudentsmalemornning[$keyl] = $array;

          $v17 =0;  $v18 =0;$v19 =0;  $v20 =0;$v21 =0;$v22 =0;$v23 =0;  $v24 =0;$v25 =0;  $v26 =0;$v27 =0;$v28 =0;  $v29 =0;$v30 =0;$v31 =0;$v32 =0;
          $v33 =0;$v34 =0;$v35 =0;$v36 =0;$v37 =0;$v38 =0;$v39 =0;$v40 =0;$v41 =0;$v42 =0;$v43 =0;$v44 =0;$v45 =0;$v46 =0;$v47 =0;$v48 =0;
          $v49 =0;$v50 =0;$v51 =0;$totalfemalemornning=0;
          foreach ($studentsfemalemornning as $key => $value) {
            $now = Carbon::now();
            $date = Carbon::parse($value->date_of_birth);
            $diff = $date->diffInYears($now);
             if ($diff == 17){$v17=$v17+1;}
             elseif ($diff == 18) {$v18=$v18+1;}
             elseif ($diff == 19) {$v19=$v19+1;}
             elseif ($diff == 20) {$v20=$v20+1;}
             elseif ($diff == 21) {$v21=$v21+1;}
             elseif ($diff == 22) {$v22=$v22+1;}
             elseif ($diff == 23) {$v23=$v23+1;}
             elseif ($diff == 24) {$v24=$v24+1;}
             elseif ($diff == 25) {$v25=$v25+1;}
             elseif ($diff == 26) {$v26=$v26+1;}
             elseif ($diff == 27) {$v27=$v27+1;}
             elseif ($diff == 28) {$v28=$v28+1;}
             elseif ($diff == 29) {$v29=$v29+1;}
             elseif ($diff == 30) {$v30=$v30+1;}
             elseif ($diff == 31) {$v31=$v31+1;}
             elseif ($diff == 32) {$v32=$v32+1;}
             elseif ($diff == 33) {$v33=$v33+1;}
             elseif ($diff == 34) {$v34=$v34+1;}
             elseif ($diff == 35) {$v35=$v35+1;}
             elseif ($diff == 36) {$v36=$v36+1;}
             elseif ($diff == 37) {$v37=$v37+1;}
             elseif ($diff == 38) {$v38=$v38+1;}
             elseif ($diff == 39) {$v39=$v39+1;}
             elseif ($diff == 40) {$v40=$v40+1;}
             elseif ($diff == 41) {$v41=$v41+1;}
             elseif ($diff == 42) {$v42=$v42+1;}
             elseif ($diff == 43) {$v43=$v43+1;}
             elseif ($diff == 44) {$v44=$v44+1;}
             elseif ($diff == 45) {$v45=$v45+1;}
             elseif ($diff == 46) {$v46=$v46+1;}
             elseif ($diff == 47) {$v47=$v47+1;}
             elseif ($diff == 48) {$v48=$v48+1;}
             elseif ($diff == 49) {$v49=$v49+1;}
             elseif ($diff >= 50) {$v50=$v50+1;}
             elseif ($diff == 'non') {$v51=$v51+1;}
          }
          $totalfemalemornning=$v17+$v18+$v19+$v20+$v21+$v22+$v23+$v24+$v25+$v26+$v27+$v28+$v29+$v30+$v31+$v32+
          $v33+$v34+$v35+$v36+$v37+$v38+$v39+$v40+$v41+$v42+$v43+$v44+$v45+$v46+$v47+$v48+$v49+$v50+$v51;
          $array = array("17" =>$v17,"18" =>$v18,"19" =>$v19,"20"=>$v20,"21"=>$v21,"22" =>$v22,"23" =>$v23,"24" =>$v24,"25" =>$v25,"26" =>$v26,
          "27" =>$v27,"28" =>$v28,"29" =>$v29,"30" =>$v30,"31" =>$v31,"32" =>$v32,"33" =>$v33,"34" =>$v34,"35" =>$v35,"36" =>$v36,"37" =>$v37,
          "38" =>$v38,"39" =>$v39,"40" =>$v40,"41" =>$v41,"42" =>$v42,"43" =>$v43,"44" =>$v44,"45" =>$v45,"46" =>$v46,"47" =>$v47,"48" =>$v48,
          "49" =>$v49,"خمسين واكثر" =>$v50,"غير معرف" =>$v51,);
          $array = array("study" =>'صباحي',"gender"=>'انثى',"college" =>$value->name,"level" =>$value->level,"total"=>$totalfemalemornning,"age"=>$array,);
          $arrstudentsfemalemornning[$keyl] = $array;

          $v17 =0;  $v18 =0;$v19 =0;  $v20 =0;$v21 =0;$v22 =0;$v23 =0;  $v24 =0;$v25 =0;  $v26 =0;$v27 =0;$v28 =0;  $v29 =0;$v30 =0;$v31 =0;$v32 =0;
          $v33 =0;$v34 =0;$v35 =0;$v36 =0;$v37 =0;$v38 =0;$v39 =0;$v40 =0;$v41 =0;$v42 =0;$v43 =0;$v44 =0;$v45 =0;$v46 =0;$v47 =0;$v48 =0;
          $v49 =0;$v50 =0;$v51 =0;$totlalmaleevening=0;
          foreach ($studentsmaleevening as $key => $value) {
            $now = Carbon::now();
            $date = Carbon::parse($value->date_of_birth);
            $diff = $date->diffInYears($now);
             if ($diff == 17){$v17=$v17+1;}
             elseif ($diff == 18) {$v18=$v18+1;}
             elseif ($diff == 19) {$v19=$v19+1;}
             elseif ($diff == 20) {$v20=$v20+1;}
             elseif ($diff == 21) {$v21=$v21+1;}
             elseif ($diff == 22) {$v22=$v22+1;}
             elseif ($diff == 23) {$v23=$v23+1;}
             elseif ($diff == 24) {$v24=$v24+1;}
             elseif ($diff == 25) {$v25=$v25+1;}
             elseif ($diff == 26) {$v26=$v26+1;}
             elseif ($diff == 27) {$v27=$v27+1;}
             elseif ($diff == 28) {$v28=$v28+1;}
             elseif ($diff == 29) {$v29=$v29+1;}
             elseif ($diff == 30) {$v30=$v30+1;}
             elseif ($diff == 31) {$v31=$v31+1;}
             elseif ($diff == 32) {$v32=$v32+1;}
             elseif ($diff == 33) {$v33=$v33+1;}
             elseif ($diff == 34) {$v34=$v34+1;}
             elseif ($diff == 35) {$v35=$v35+1;}
             elseif ($diff == 36) {$v36=$v36+1;}
             elseif ($diff == 37) {$v37=$v37+1;}
             elseif ($diff == 38) {$v38=$v38+1;}
             elseif ($diff == 39) {$v39=$v39+1;}
             elseif ($diff == 40) {$v40=$v40+1;}
             elseif ($diff == 41) {$v41=$v41+1;}
             elseif ($diff == 42) {$v42=$v42+1;}
             elseif ($diff == 43) {$v43=$v43+1;}
             elseif ($diff == 44) {$v44=$v44+1;}
             elseif ($diff == 45) {$v45=$v45+1;}
             elseif ($diff == 46) {$v46=$v46+1;}
             elseif ($diff == 47) {$v47=$v47+1;}
             elseif ($diff == 48) {$v48=$v48+1;}
             elseif ($diff == 49) {$v49=$v49+1;}
             elseif ($diff >= 50) {$v50=$v50+1;}
             elseif ($diff == 'non') {$v51=$v51+1;}
          }
          $totlalmaleevening=$v17+$v18+$v19+$v20+$v21+$v22+$v23+$v24+$v25+$v26+$v27+$v28+$v29+$v30+$v31+$v32+
          $v33+$v34+$v35+$v36+$v37+$v38+$v39+$v40+$v41+$v42+$v43+$v44+$v45+$v46+$v47+$v48+$v49+$v50+$v51;
          $array = array("17" =>$v17,"18" =>$v18,"19" =>$v19,"20"=>$v20,"21"=>$v21,"22" =>$v22,"23" =>$v23,"24" =>$v24,"25" =>$v25,"26" =>$v26,
          "27" =>$v27,"28" =>$v28,"29" =>$v29,"30" =>$v30,"31" =>$v31,"32" =>$v32,"33" =>$v33,"34" =>$v34,"35" =>$v35,"36" =>$v36,"37" =>$v37,
          "38" =>$v38,"39" =>$v39,"40" =>$v40,"41" =>$v41,"42" =>$v42,"43" =>$v43,"44" =>$v44,"45" =>$v45,"46" =>$v46,"47" =>$v47,"48" =>$v48,
          "49" =>$v49,"خمسين واكثر" =>$v50,"غير معرف" =>$v51,);
          $array = array("study" =>'مسائي',"gender"=>'ذكر',"college" =>$value->name,"level" =>$value->level,"total"=>$totlalmaleevening,"age"=>$array,);
          $arrstudentsmaleevening[$keyl] = $array;

          $v17 =0;  $v18 =0;$v19 =0;  $v20 =0;$v21 =0;$v22 =0;$v23 =0;  $v24 =0;$v25 =0;  $v26 =0;$v27 =0;$v28 =0;  $v29 =0;$v30 =0;$v31 =0;$v32 =0;
          $v33 =0;$v34 =0;$v35 =0;$v36 =0;$v37 =0;$v38 =0;$v39 =0;$v40 =0;$v41 =0;$v42 =0;$v43 =0;$v44 =0;$v45 =0;$v46 =0;$v47 =0;$v48 =0;
          $v49 =0;$v50 =0;$v51 =0;$arrfemaleevening=0;
          foreach ($studentsfemaleevening as $key => $value) {
            $now = Carbon::now();
            $date = Carbon::parse($value->date_of_birth);
            $diff = $date->diffInYears($now);
             if ($diff == 17){$v17=$v17+1;}
             elseif ($diff == 18) {$v18=$v18+1;}
             elseif ($diff == 19) {$v19=$v19+1;}
             elseif ($diff == 20) {$v20=$v20+1;}
             elseif ($diff == 21) {$v21=$v21+1;}
             elseif ($diff == 22) {$v22=$v22+1;}
             elseif ($diff == 23) {$v23=$v23+1;}
             elseif ($diff == 24) {$v24=$v24+1;}
             elseif ($diff == 25) {$v25=$v25+1;}
             elseif ($diff == 26) {$v26=$v26+1;}
             elseif ($diff == 27) {$v27=$v27+1;}
             elseif ($diff == 28) {$v28=$v28+1;}
             elseif ($diff == 29) {$v29=$v29+1;}
             elseif ($diff == 30) {$v30=$v30+1;}
             elseif ($diff == 31) {$v31=$v31+1;}
             elseif ($diff == 32) {$v32=$v32+1;}
             elseif ($diff == 33) {$v33=$v33+1;}
             elseif ($diff == 34) {$v34=$v34+1;}
             elseif ($diff == 35) {$v35=$v35+1;}
             elseif ($diff == 36) {$v36=$v36+1;}
             elseif ($diff == 37) {$v37=$v37+1;}
             elseif ($diff == 38) {$v38=$v38+1;}
             elseif ($diff == 39) {$v39=$v39+1;}
             elseif ($diff == 40) {$v40=$v40+1;}
             elseif ($diff == 41) {$v41=$v41+1;}
             elseif ($diff == 42) {$v42=$v42+1;}
             elseif ($diff == 43) {$v43=$v43+1;}
             elseif ($diff == 44) {$v44=$v44+1;}
             elseif ($diff == 45) {$v45=$v45+1;}
             elseif ($diff == 46) {$v46=$v46+1;}
             elseif ($diff == 47) {$v47=$v47+1;}
             elseif ($diff == 48) {$v48=$v48+1;}
             elseif ($diff == 49) {$v49=$v49+1;}
             elseif ($diff >= 50) {$v50=$v50+1;}
             elseif ($diff == 'non') {$v51=$v51+1;}
          }
          $arrfemaleevening=$v17+$v18+$v19+$v20+$v21+$v22+$v23+$v24+$v25+$v26+$v27+$v28+$v29+$v30+$v31+$v32+
          $v33+$v34+$v35+$v36+$v37+$v38+$v39+$v40+$v41+$v42+$v43+$v44+$v45+$v46+$v47+$v48+$v49+$v50+$v51;
          $array = array("17" =>$v17,"18" =>$v18,"19" =>$v19,"20"=>$v20,"21"=>$v21,"22" =>$v22,"23" =>$v23,"24" =>$v24,"25" =>$v25,"26" =>$v26,
          "27" =>$v27,"28" =>$v28,"29" =>$v29,"30" =>$v30,"31" =>$v31,"32" =>$v32,"33" =>$v33,"34" =>$v34,"35" =>$v35,"36" =>$v36,"37" =>$v37,
          "38" =>$v38,"39" =>$v39,"40" =>$v40,"41" =>$v41,"42" =>$v42,"43" =>$v43,"44" =>$v44,"45" =>$v45,"46" =>$v46,"47" =>$v47,"48" =>$v48,
          "49" =>$v49,"خمسين واكثر" =>$v50,"غير معرف" =>$v51,);
          $array = array("study" =>'مسائي',"gender"=>'انثى',"college" =>$value->name,"level" =>$value->level,"total"=>$arrfemaleevening,"age"=>$array,);
          $arrstudentsfemaleevening[$keyl] = $array;

        }
        $arrayName = array($arrstudentsmalemornning,$arrstudentsfemalemornning,$arrstudentsmaleevening,$arrstudentsfemaleevening);
        $arr[$keyd] = $arrayName;
      }

        foreach ($levels as $keyl => $level) {
          $studentsmalemornning=DB::table('statuses')->select('date_of_birth','colleges.name','level')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('colleges','colleges.id','student_classes.college_id')->where('students.gender','0')
          ->where('levels.level',$level->level)->where('is_active','1')->where('academic_status_id','1')
          ->where('student_classes.shift_id',1)->get();

          $studentsfemalemornning=DB::table('statuses')->select('date_of_birth','colleges.name','level')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('colleges','colleges.id','student_classes.college_id')->where('students.gender','1')
          ->where('levels.level',$level->level)->where('is_active','1')->where('academic_status_id','1')
          ->where('student_classes.shift_id',1)->get();

          $studentsmaleevening=DB::table('statuses')->select('date_of_birth','colleges.name','level')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('colleges','colleges.id','student_classes.college_id')->where('students.gender','0')
          ->where('levels.level',$level->level)->where('is_active','1')->where('academic_status_id','1')
          ->where('student_classes.shift_id',2)->get();

          $studentsfemaleevening=DB::table('statuses')->select('date_of_birth','colleges.name','level')
          ->leftjoin('student_classes','student_classes.class_id','statuses.student_class_id')
          ->leftjoin('students','students.student_id','statuses.student_id')
          ->leftjoin('levels','levels.id','student_classes.level_id')
          ->leftjoin('colleges','colleges.id','student_classes.college_id')->where('students.gender','1')
          ->where('levels.level',$level->level)->where('is_active','1')->where('academic_status_id','1')
          ->where('student_classes.shift_id',2)->get();

          $v17 =0;  $v18 =0;$v19 =0;  $v20 =0;$v21 =0;$v22 =0;$v23 =0;  $v24 =0;$v25 =0;  $v26 =0;$v27 =0;$v28 =0;  $v29 =0;$v30 =0;$v31 =0;$v32 =0;
          $v33 =0;$v34 =0;$v35 =0;$v36 =0;$v37 =0;$v38 =0;$v39 =0;$v40 =0;$v41 =0;$v42 =0;$v43 =0;$v44 =0;$v45 =0;$v46 =0;$v47 =0;$v48 =0;
          $v49 =0;$v50 =0;$v51 =0;$totalmalemornning=0;
          foreach ($studentsmalemornning as $key => $value) {
            $now = Carbon::now();
            $date = Carbon::parse($value->date_of_birth);
            $diff = $date->diffInYears($now);
             if ($diff == 17){$v17=$v17+1;}
             elseif ($diff == 18) {$v18=$v18+1;}
             elseif ($diff == 19) {$v19=$v19+1;}
             elseif ($diff == 20) {$v20=$v20+1;}
             elseif ($diff == 21) {$v21=$v21+1;}
             elseif ($diff == 22) {$v22=$v22+1;}
             elseif ($diff == 23) {$v23=$v23+1;}
             elseif ($diff == 24) {$v24=$v24+1;}
             elseif ($diff == 25) {$v25=$v25+1;}
             elseif ($diff == 26) {$v26=$v26+1;}
             elseif ($diff == 27) {$v27=$v27+1;}
             elseif ($diff == 28) {$v28=$v28+1;}
             elseif ($diff == 29) {$v29=$v29+1;}
             elseif ($diff == 30) {$v30=$v30+1;}
             elseif ($diff == 31) {$v31=$v31+1;}
             elseif ($diff == 32) {$v32=$v32+1;}
             elseif ($diff == 33) {$v33=$v33+1;}
             elseif ($diff == 34) {$v34=$v34+1;}
             elseif ($diff == 35) {$v35=$v35+1;}
             elseif ($diff == 36) {$v36=$v36+1;}
             elseif ($diff == 37) {$v37=$v37+1;}
             elseif ($diff == 38) {$v38=$v38+1;}
             elseif ($diff == 39) {$v39=$v39+1;}
             elseif ($diff == 40) {$v40=$v40+1;}
             elseif ($diff == 41) {$v41=$v41+1;}
             elseif ($diff == 42) {$v42=$v42+1;}
             elseif ($diff == 43) {$v43=$v43+1;}
             elseif ($diff == 44) {$v44=$v44+1;}
             elseif ($diff == 45) {$v45=$v45+1;}
             elseif ($diff == 46) {$v46=$v46+1;}
             elseif ($diff == 47) {$v47=$v47+1;}
             elseif ($diff == 48) {$v48=$v48+1;}
             elseif ($diff == 49) {$v49=$v49+1;}
             elseif ($diff >= 50) {$v50=$v50+1;}
             elseif ($diff == 'non') {$v51=$v51+1;}

          }
          $totalmalemornning=$v17+$v18+$v19+$v20+$v21+$v22+$v23+$v24+$v25+$v26+$v27+$v28+$v29+$v30+$v31+$v32+
          $v33+$v34+$v35+$v36+$v37+$v38+$v39+$v40+$v41+$v42+$v43+$v44+$v45+$v46+$v47+$v48+$v49+$v50+$v51;
          $array = array("17" =>$v17,"18" =>$v18,"19" =>$v19,"20"=>$v20,"21"=>$v21,"22" =>$v22,"23" =>$v23,"24" =>$v24,"25" =>$v25,"26" =>$v26,
          "27" =>$v27,"28" =>$v28,"29" =>$v29,"30" =>$v30,"31" =>$v31,"32" =>$v32,"33" =>$v33,"34" =>$v34,"35" =>$v35,"36" =>$v36,"37" =>$v37,
          "38" =>$v38,"39" =>$v39,"40" =>$v40,"41" =>$v41,"42" =>$v42,"43" =>$v43,"44" =>$v44,"45" =>$v45,"46" =>$v46,"47" =>$v47,"48" =>$v48,
          "49" =>$v49,"خمسين واكثر" =>$v50,"غير معرف" =>$v51,);
          $array = array("study" =>'صباحي',"gender"=>'ذكر',"college" =>$value->name,"level" =>$value->level,"total"=>$totalmalemornning,"age"=>$array,);
          $arrstudentsmalemornning[$keyl] = $array;

          $v17 =0;  $v18 =0;$v19 =0;  $v20 =0;$v21 =0;$v22 =0;$v23 =0;  $v24 =0;$v25 =0;  $v26 =0;$v27 =0;$v28 =0;  $v29 =0;$v30 =0;$v31 =0;$v32 =0;
          $v33 =0;$v34 =0;$v35 =0;$v36 =0;$v37 =0;$v38 =0;$v39 =0;$v40 =0;$v41 =0;$v42 =0;$v43 =0;$v44 =0;$v45 =0;$v46 =0;$v47 =0;$v48 =0;
          $v49 =0;$v50 =0;$v51 =0;$totalfemalemornning=0;
          foreach ($studentsfemalemornning as $key => $value) {
            $now = Carbon::now();
            $date = Carbon::parse($value->date_of_birth);
            $diff = $date->diffInYears($now);
             if ($diff == 17){$v17=$v17+1;}
             elseif ($diff == 18) {$v18=$v18+1;}
             elseif ($diff == 19) {$v19=$v19+1;}
             elseif ($diff == 20) {$v20=$v20+1;}
             elseif ($diff == 21) {$v21=$v21+1;}
             elseif ($diff == 22) {$v22=$v22+1;}
             elseif ($diff == 23) {$v23=$v23+1;}
             elseif ($diff == 24) {$v24=$v24+1;}
             elseif ($diff == 25) {$v25=$v25+1;}
             elseif ($diff == 26) {$v26=$v26+1;}
             elseif ($diff == 27) {$v27=$v27+1;}
             elseif ($diff == 28) {$v28=$v28+1;}
             elseif ($diff == 29) {$v29=$v29+1;}
             elseif ($diff == 30) {$v30=$v30+1;}
             elseif ($diff == 31) {$v31=$v31+1;}
             elseif ($diff == 32) {$v32=$v32+1;}
             elseif ($diff == 33) {$v33=$v33+1;}
             elseif ($diff == 34) {$v34=$v34+1;}
             elseif ($diff == 35) {$v35=$v35+1;}
             elseif ($diff == 36) {$v36=$v36+1;}
             elseif ($diff == 37) {$v37=$v37+1;}
             elseif ($diff == 38) {$v38=$v38+1;}
             elseif ($diff == 39) {$v39=$v39+1;}
             elseif ($diff == 40) {$v40=$v40+1;}
             elseif ($diff == 41) {$v41=$v41+1;}
             elseif ($diff == 42) {$v42=$v42+1;}
             elseif ($diff == 43) {$v43=$v43+1;}
             elseif ($diff == 44) {$v44=$v44+1;}
             elseif ($diff == 45) {$v45=$v45+1;}
             elseif ($diff == 46) {$v46=$v46+1;}
             elseif ($diff == 47) {$v47=$v47+1;}
             elseif ($diff == 48) {$v48=$v48+1;}
             elseif ($diff == 49) {$v49=$v49+1;}
             elseif ($diff >= 50) {$v50=$v50+1;}
             elseif ($diff == 'non') {$v51=$v51+1;}
          }
          $totalfemalemornning=$v17+$v18+$v19+$v20+$v21+$v22+$v23+$v24+$v25+$v26+$v27+$v28+$v29+$v30+$v31+$v32+
          $v33+$v34+$v35+$v36+$v37+$v38+$v39+$v40+$v41+$v42+$v43+$v44+$v45+$v46+$v47+$v48+$v49+$v50+$v51;
          $array = array("17" =>$v17,"18" =>$v18,"19" =>$v19,"20"=>$v20,"21"=>$v21,"22" =>$v22,"23" =>$v23,"24" =>$v24,"25" =>$v25,"26" =>$v26,
          "27" =>$v27,"28" =>$v28,"29" =>$v29,"30" =>$v30,"31" =>$v31,"32" =>$v32,"33" =>$v33,"34" =>$v34,"35" =>$v35,"36" =>$v36,"37" =>$v37,
          "38" =>$v38,"39" =>$v39,"40" =>$v40,"41" =>$v41,"42" =>$v42,"43" =>$v43,"44" =>$v44,"45" =>$v45,"46" =>$v46,"47" =>$v47,"48" =>$v48,
          "49" =>$v49,"خمسين واكثر" =>$v50,"غير معرف" =>$v51,);
          $array = array("study" =>'صباحي',"gender"=>'انثى',"college" =>$value->name,"level" =>$value->level,"total"=>$totalfemalemornning,"age"=>$array,);
          $arrstudentsfemalemornning[$keyl] = $array;

          $v17 =0;  $v18 =0;$v19 =0;  $v20 =0;$v21 =0;$v22 =0;$v23 =0;  $v24 =0;$v25 =0;  $v26 =0;$v27 =0;$v28 =0;  $v29 =0;$v30 =0;$v31 =0;$v32 =0;
          $v33 =0;$v34 =0;$v35 =0;$v36 =0;$v37 =0;$v38 =0;$v39 =0;$v40 =0;$v41 =0;$v42 =0;$v43 =0;$v44 =0;$v45 =0;$v46 =0;$v47 =0;$v48 =0;
          $v49 =0;$v50 =0;$v51 =0;$totlalmaleevening=0;
          foreach ($studentsmaleevening as $key => $value) {
            $now = Carbon::now();
            $date = Carbon::parse($value->date_of_birth);
            $diff = $date->diffInYears($now);
             if ($diff == 17){$v17=$v17+1;}
             elseif ($diff == 18) {$v18=$v18+1;}
             elseif ($diff == 19) {$v19=$v19+1;}
             elseif ($diff == 20) {$v20=$v20+1;}
             elseif ($diff == 21) {$v21=$v21+1;}
             elseif ($diff == 22) {$v22=$v22+1;}
             elseif ($diff == 23) {$v23=$v23+1;}
             elseif ($diff == 24) {$v24=$v24+1;}
             elseif ($diff == 25) {$v25=$v25+1;}
             elseif ($diff == 26) {$v26=$v26+1;}
             elseif ($diff == 27) {$v27=$v27+1;}
             elseif ($diff == 28) {$v28=$v28+1;}
             elseif ($diff == 29) {$v29=$v29+1;}
             elseif ($diff == 30) {$v30=$v30+1;}
             elseif ($diff == 31) {$v31=$v31+1;}
             elseif ($diff == 32) {$v32=$v32+1;}
             elseif ($diff == 33) {$v33=$v33+1;}
             elseif ($diff == 34) {$v34=$v34+1;}
             elseif ($diff == 35) {$v35=$v35+1;}
             elseif ($diff == 36) {$v36=$v36+1;}
             elseif ($diff == 37) {$v37=$v37+1;}
             elseif ($diff == 38) {$v38=$v38+1;}
             elseif ($diff == 39) {$v39=$v39+1;}
             elseif ($diff == 40) {$v40=$v40+1;}
             elseif ($diff == 41) {$v41=$v41+1;}
             elseif ($diff == 42) {$v42=$v42+1;}
             elseif ($diff == 43) {$v43=$v43+1;}
             elseif ($diff == 44) {$v44=$v44+1;}
             elseif ($diff == 45) {$v45=$v45+1;}
             elseif ($diff == 46) {$v46=$v46+1;}
             elseif ($diff == 47) {$v47=$v47+1;}
             elseif ($diff == 48) {$v48=$v48+1;}
             elseif ($diff == 49) {$v49=$v49+1;}
             elseif ($diff >= 50) {$v50=$v50+1;}
             elseif ($diff == 'non') {$v51=$v51+1;}
          }
          $totlalmaleevening=$v17+$v18+$v19+$v20+$v21+$v22+$v23+$v24+$v25+$v26+$v27+$v28+$v29+$v30+$v31+$v32+
          $v33+$v34+$v35+$v36+$v37+$v38+$v39+$v40+$v41+$v42+$v43+$v44+$v45+$v46+$v47+$v48+$v49+$v50+$v51;
          $array = array("17" =>$v17,"18" =>$v18,"19" =>$v19,"20"=>$v20,"21"=>$v21,"22" =>$v22,"23" =>$v23,"24" =>$v24,"25" =>$v25,"26" =>$v26,
          "27" =>$v27,"28" =>$v28,"29" =>$v29,"30" =>$v30,"31" =>$v31,"32" =>$v32,"33" =>$v33,"34" =>$v34,"35" =>$v35,"36" =>$v36,"37" =>$v37,
          "38" =>$v38,"39" =>$v39,"40" =>$v40,"41" =>$v41,"42" =>$v42,"43" =>$v43,"44" =>$v44,"45" =>$v45,"46" =>$v46,"47" =>$v47,"48" =>$v48,
          "49" =>$v49,"خمسين واكثر" =>$v50,"غير معرف" =>$v51,);
          $array = array("study" =>'مسائي',"gender"=>'ذكر',"college" =>$value->name,"level" =>$value->level,"total"=>$totlalmaleevening,"age"=>$array,);
          $arrstudentsmaleevening[$keyl] = $array;

          $v17 =0;  $v18 =0;$v19 =0;  $v20 =0;$v21 =0;$v22 =0;$v23 =0;  $v24 =0;$v25 =0;  $v26 =0;$v27 =0;$v28 =0;  $v29 =0;$v30 =0;$v31 =0;$v32 =0;
          $v33 =0;$v34 =0;$v35 =0;$v36 =0;$v37 =0;$v38 =0;$v39 =0;$v40 =0;$v41 =0;$v42 =0;$v43 =0;$v44 =0;$v45 =0;$v46 =0;$v47 =0;$v48 =0;
          $v49 =0;$v50 =0;$v51 =0;$arrfemaleevening=0;
          foreach ($studentsfemaleevening as $key => $value) {
            $now = Carbon::now();
            $date = Carbon::parse($value->date_of_birth);
            $diff = $date->diffInYears($now);
             if ($diff == 17){$v17=$v17+1;}
             elseif ($diff == 18) {$v18=$v18+1;}
             elseif ($diff == 19) {$v19=$v19+1;}
             elseif ($diff == 20) {$v20=$v20+1;}
             elseif ($diff == 21) {$v21=$v21+1;}
             elseif ($diff == 22) {$v22=$v22+1;}
             elseif ($diff == 23) {$v23=$v23+1;}
             elseif ($diff == 24) {$v24=$v24+1;}
             elseif ($diff == 25) {$v25=$v25+1;}
             elseif ($diff == 26) {$v26=$v26+1;}
             elseif ($diff == 27) {$v27=$v27+1;}
             elseif ($diff == 28) {$v28=$v28+1;}
             elseif ($diff == 29) {$v29=$v29+1;}
             elseif ($diff == 30) {$v30=$v30+1;}
             elseif ($diff == 31) {$v31=$v31+1;}
             elseif ($diff == 32) {$v32=$v32+1;}
             elseif ($diff == 33) {$v33=$v33+1;}
             elseif ($diff == 34) {$v34=$v34+1;}
             elseif ($diff == 35) {$v35=$v35+1;}
             elseif ($diff == 36) {$v36=$v36+1;}
             elseif ($diff == 37) {$v37=$v37+1;}
             elseif ($diff == 38) {$v38=$v38+1;}
             elseif ($diff == 39) {$v39=$v39+1;}
             elseif ($diff == 40) {$v40=$v40+1;}
             elseif ($diff == 41) {$v41=$v41+1;}
             elseif ($diff == 42) {$v42=$v42+1;}
             elseif ($diff == 43) {$v43=$v43+1;}
             elseif ($diff == 44) {$v44=$v44+1;}
             elseif ($diff == 45) {$v45=$v45+1;}
             elseif ($diff == 46) {$v46=$v46+1;}
             elseif ($diff == 47) {$v47=$v47+1;}
             elseif ($diff == 48) {$v48=$v48+1;}
             elseif ($diff == 49) {$v49=$v49+1;}
             elseif ($diff >= 50) {$v50=$v50+1;}
             elseif ($diff == 'non') {$v51=$v51+1;}
          }
          $arrfemaleevening=$v17+$v18+$v19+$v20+$v21+$v22+$v23+$v24+$v25+$v26+$v27+$v28+$v29+$v30+$v31+$v32+
          $v33+$v34+$v35+$v36+$v37+$v38+$v39+$v40+$v41+$v42+$v43+$v44+$v45+$v46+$v47+$v48+$v49+$v50+$v51;
          $array = array("17" =>$v17,"18" =>$v18,"19" =>$v19,"20"=>$v20,"21"=>$v21,"22" =>$v22,"23" =>$v23,"24" =>$v24,"25" =>$v25,"26" =>$v26,
          "27" =>$v27,"28" =>$v28,"29" =>$v29,"30" =>$v30,"31" =>$v31,"32" =>$v32,"33" =>$v33,"34" =>$v34,"35" =>$v35,"36" =>$v36,"37" =>$v37,
          "38" =>$v38,"39" =>$v39,"40" =>$v40,"41" =>$v41,"42" =>$v42,"43" =>$v43,"44" =>$v44,"45" =>$v45,"46" =>$v46,"47" =>$v47,"48" =>$v48,
          "49" =>$v49,"خمسين واكثر" =>$v50,"غير معرف" =>$v51,);
          $array = array("study" =>'مسائي',"gender"=>'انثى',"college" =>$value->name,"level" =>$value->level,"total"=>$arrfemaleevening,"age"=>$array,);
          $arrstudentsfemaleevening[$keyl] = $array;

        }
        $arrayName = array($arrstudentsmalemornning,$arrstudentsfemalemornning,$arrstudentsmaleevening,$arrstudentsfemaleevening);
        $arr1[$keyd] = $arrayName;
        
      return view('admin.reports.student_reports.table3')->with('arrs',$arr)->with('arrs1',$arr1);
    }

    public function student_by_birth_date_form_download (Request $request){
      $id = $request->input('academic_year_id');
        $rules = [
            'academic_year_id' => 'required'
        ];
        $message = ['required' => 'يجب ادخال بيانات الحقل'];
        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $data = DB::select(DB::raw("
        SELECT t.age_group, COUNT(*) AS age_count,level,gender,shift
FROM
(
    SELECT
        CASE WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 17
             THEN '17'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 18
             THEN '18'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 19
             THEN '19'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 20
             THEN '20'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 21
             THEN '21'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 22
             THEN '22'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 23
             THEN '23'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 24
             THEN '24'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 25
             THEN '25'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 26
             THEN '26'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 27
             THEN '27'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 28
             THEN '28'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 29
             THEN '29'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 30
             THEN '30'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 31
             THEN '31'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 32
             THEN '32'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 33
             THEN '33'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 34
             THEN '34'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 35
             THEN '35'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 36
             THEN '36'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 37
             THEN '37'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 38
             THEN '38'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 39
             THEN '39'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 40
             THEN '40'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 41
             THEN '41'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 42
             THEN '42'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 43
             THEN '43'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 44
             THEN '44'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 45
             THEN '45'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 46
             THEN '46'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 47
             THEN '47'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 48
             THEN '48'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) = 49
             THEN '49'
             WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 50
             THEN 'خمسين او اكثر'
             ELSE 'غير معرف'
        END AS age_group,
        c.level,
        students.gender,c.shift
    FROM students
    INNER JOIN
    `statuses` ON `students`.`student_id` = `statuses`.`student_id`
        INNER JOIN
    (SELECT
			student_classes.class_id,student_classes.shift_id as 'shift',
            `levels`.`level` AS `level`
    FROM
        `student_classes`
    INNER JOIN `academic_years` ON `academic_years`.`id` = `student_classes`.`academic_year_id`
    INNER JOIN `levels` ON `levels`.`id` = `student_classes`.`level_id`
    WHERE
        `academic_years`.`id` =$request->input('id') and 'shift_id'=$request->input('shift_id')) AS `c` ON `c`.`class_id` = `statuses`.`student_class_id`
    WHERE
    `statuses`.`academic_status_id` = 1
        AND `statuses`.`is_active` = 1

) t
GROUP BY t.age_group, level,gender
        "));


        $array = [];
        dd($data);
        foreach ($data as $d) {
            $array[$d->age_group][] = $d;
        }


        $academic_year = AcademicYear::where('id', '=', $request->input('academic_year_id'))->first();
        return Excel::download(new IraqiByDOBStudentReportExport($array, $academic_year), 'حسب العمر.xlsx');

    }



}
