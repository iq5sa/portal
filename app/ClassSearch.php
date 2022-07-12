<?php
/**
 * Created by PhpStorm.
 * User: IT-Hasan
 * Date: 8/17/2019
 * Time: 11:49 PM
 */

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassSearch
{
    public static function all()
    {

    }

    public static function apply(Request $filters)
    {

        $columns = [
            "class_id",
            "level",
            "shift",
            DB::raw("shifts.id as shift_id"),
            DB::raw("colleges.id as college_id"),
            DB::raw("departments.id as department_id"),
            DB::raw("academic_years.start_year AS start_year"),
            DB::raw("academic_years.end_year AS end_year"),
            DB::raw("colleges.name AS college_name"),
            DB::raw("departments.name AS department_name"),
            DB::raw("times.time AS `time`"),
            DB::raw("batches.batch AS batch"),
            DB::raw("groups.group AS `group`"),
            DB::raw("course_types.type AS `type`")

        ];

        $studentClass = DB::table('student_classes')->select($columns)
            ->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->leftJoin('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('times', 'times.id', '=', 'student_classes.time_id')
            ->join('batches', 'batches.id', '=', 'student_classes.batch_id')
            ->join('groups', 'groups.id', '=', 'student_classes.group_id')
            ->join('course_types', 'course_types.id', '=', 'student_classes.type_id');


        $user = auth()->user();
        if ($user->hasRole('موظف تسجيل')){
            $colleges = [];

            foreach ($user->colleges as $col){
                $colleges[] = $col->id;
            }
            $studentClass->whereIn('colleges.id',$colleges);

        }

        // Search for a user based on their academic_year_id.
        if ($filters->input('academic_year_id') != null) {
            $studentClass->where('student_classes.academic_year_id', "=", $filters->input('academic_year_id'));
        }


        // Search for a user based on their college_id.
        if ($filters->input('college_id') != null) {
            $studentClass->where('student_classes.college_id', "=", $filters->input('college_id'));
        }

        // Search for a user based on their department_id.
        if ($filters->input('department_id') != null) {
            $studentClass->where('student_classes.department_id', "=", $filters->input('department_id'));
        }

        // Search for a user based on their levels.
        if ($filters->input('level_id') != null) {
            $studentClass->where('student_classes.level_id', "=", $filters->input('level_id'));
        }

        // Search for a user based on their shifts.
        if ($filters->input('shift_id') != null) {
            $studentClass->where('student_classes.shift_id', "=", $filters->input('shift_id'));
        }

        // Search for a user based on their course_types.
        if ($filters->input('type_id') != null) {
            $studentClass->where('student_classes.type_id', "=", $filters->input('type_id'));
        }

        // Search for a user based on their times.
        if ($filters->input('time_id') != null) {
            $studentClass->where('student_classes.time_id', "=", $filters->input('time_id'));
        }

        // Search for a user based on their groups.
        if ($filters->input('group_id') != null) {
            $studentClass->where('student_classes.group_id', "=", $filters->input('group_id'));
        }

        // Search for a user based on their batches.
        if ($filters->input('batch_id') != null) {
            $studentClass->where('student_classes.batch_id', "=", $filters->input('batch_id'));
        }

        if ($filters->input('department_id_input') != null) {
            $studentClass->where('student_classes.department_id', "=", $filters->input('department_id_input'));
        }

        if ($filters->input('college_id_input') != null) {
            $studentClass->where('student_classes.college_id', "=", $filters->input('college_id_input'));
        }
        if ($filters->input('shift_id_input') != null) {
            $studentClass->where('shifts.id', "=", $filters->input('shift_id_input'));
        }


        $studentClass->where('active', '=', 1);

        // Get the results and return them.
        return $studentClass;
    }
}
