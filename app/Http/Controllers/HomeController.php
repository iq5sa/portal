<?php

namespace App\Http\Controllers;

use App\Fee;
use App\JobCategory;
use App\JobRequest;
use App\JobType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use app\Student;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Main;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        /*$forms_number = JobCategory::all();
        $active_cat = JobCategory::where('active', '=', 1)->get();
        $users_number = User::all();
        $job_types = $active_cat->first()->types;
        $requests_number = $active_cat->first()->requests;

        return view('admin.home')
            ->with('forms_number', count($forms_number))
            ->with('users_number', count($users_number))
            ->with('job_types_number', count($job_types))
            ->with('requests_number', count($requests_number))
            ->with('active_cat', $active_cat->first()->title);*/
			//dd(storage_path());


//            return null;
        return view('admin.home');
    }

    public function test(Request $request){
        $class_info = DB::table('statuses')->select(['student_classes.class_id', 'student_classes.college_id',
            'student_classes.department_id', 'student_classes.level_id', 'student_classes.level_id', 'student_classes.shift_id'])
            ->join('student_classes', 'student_classes.class_id', '=', 'statuses.student_class_id')
            ->where('statuses.student_id', '=', "040162")
            ->where('student_classes.academic_year_id', '=', 1)->first();


        $fees = DB::table('fees')
            ->where('fees.student_id', '=',"040162")
            ->where('fees.fee_type', '=', 0)
            ->where('fees.college_id', '=', $class_info->college_id)
            ->where('fees.academic_year_id', '=',1)->first();

            $fee = new Fee();
            $fee->academic_year_id = 1;
            $fee->college_id = $class_info->college_id;
            $fee->department_id = $class_info->department_id;
            $fee->class_id = $class_info->class_id;
            $fee->level_id = $class_info->level_id;
            $fee->shift_id = $class_info->shift_id;
            $fee->student_id = "040162";
            $fee->required_amount = '500000';
            $fee->fee_name = 'القسط السنوي';
            $fee->fee_type = 0;
            $fee->save();
            return response()->json(['message' => 'تم اضافة مبلغ القسط']);



  }
    public function html()
    {
       return $this->builder()
        ->columns($this->getColumns())
        ->parameters([
            'dom' => 'Bfrtip',
            'buttons' => ['csv', 'excel', 'print'],
        ]);
    }
    protected function getColumns()
    {
        return [
          'No',
          'student_id',
          'full_name',
          'surname',
          'enrollment_channel',
          'shift',
          'cname',
          'dname',
          'start_year',
          'gender',
          'town',
          'phone',
          'score_average_after',
          'exam_number',
          'general',
        ];
    }
    public function jobTypesData()
    {
        /*if (request()->ajax()) {
            $sta1 = DB::select('select COUNT(jr.id) as number, cc.sp as spec,cc.jtid from job_requests as jr right JOIN (select jc.title,jt.speciality as sp ,jt.id as jtid from job_categories as jc INNER JOIN job_type_job_categories jtc on jc.id = jtc.job_category_id INNER JOIN job_types as jt on jt.id = jtc.job_type_id WHERE jc.active = 1 and jt.hide = 0) as cc on jr.job_types_id = cc.jtid GROUP BY cc.jtid');
            return response()->json($sta1, 200);
        }*/
        return response()->json([], 200);

    }

    public function certificateData()
    {
        /*if (request()->ajax()) {
            $data = DB::select('SELECT count(jr.id) as number,cf.name as certificate FROM `job_requests` as jr RIGHT JOIN certificates as cf on jr.certificate = cf.id  join job_categories as js on js.id = jr.job_category_id where js.active = 1 GROUP BY cf.name');
            return response()->json($data, 200);
        }*/
        return response()->json([], 200);
    }
}
