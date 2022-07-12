<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Psy\sh;

class StudentSearch
{
    public static function all()
    {

    }

    public static function student_record($student_id)
    {
        $columns = [
            '*',
            DB::raw('students.id as std_id'),
            "first_name",
            "father_name",
            "middle_name",
            "last_name",
            "surname",
            "full_name",
            "students.student_id",
            "photo",
            "gender",
            "level",
            "shift",
            "start_year",
            "end_year",
            "college_name",
            "department_name",
            "academic_status_name",
            'has_hold_subject',
            'has_fail'
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
            ->where('statuses.is_active', '=', 1);


        $order_info = DB::table('administrative_orders')
            ->select([DB::raw('administrative_orders.id as ad_id', 'administrative_orders.number as number')])
            ->join('academic_statuses', 'academic_statuses.id', 'administrative_orders.academic_status_id');


        $statuses->leftJoinSub($order_info, 'order', function ($join) {
            $join->on('statuses.administrative_order_id', '=', 'order.ad_id');
        })->where('statuses.student_id', '=', $student_id);


        $st = DB::table('students')->select($columns);

        $st->joinSub($statuses, 'st', function ($join) {
            $join->on('st.student_id', '=', 'students.student_id');
        });


        $ac_year = DB::table('academic_years')->select(['id', DB::raw('start_year as ac_start_year'), DB::raw('end_year as ac_end_year')]);
        $st->leftJoin('enrollment_channels', 'enrollment_channels.id', '=', 'students.enrollment_channel_id');
        $st->leftJoinSub($ac_year, 'y', function ($join) {
            $join->on('y.id', '=', 'students.acceptance_year');
        });


        $studentClass = DB::table('student_classes')->select($column3)->join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->leftJoin('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id');

        $studentClass->join('groups', 'groups.id', '=', 'student_classes.group_id');
        $st->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'st.student_class_id');
        });

        return $st;
    }

    public static function apply(Request $filters = null, $filtered = true, $show = false, $student_id = null, $report = false, $discount_page = false)
    {

        $columns = [
            DB::raw('students.id as std_programming_id'),
            DB::raw('students.student_id as st_uni_id'),
            "first_name",
            "father_name",
            "middle_name",
            "last_name",
            "surname",
            "full_name",
            "students.student_id",
            "photo",
            "gender",
            "level",
            "shift",
            "start_years",
            "end_years",
            "college_name",
            "department_name",
            "academic_status_name",
            'has_hold_subject',
            'has_fail',
            'town_name',
            'date_of_birth',
            'town',
            'general',
            'enrollment_channel',
            'primary_school_name',
            'score_average_before',
            'score_average_after',
            DB::raw("primary_school_graduation_years.start_year AS dstart_year"),
            DB::raw("primary_school_graduation_years.end_year AS dend_year"),
            'phone',
            'national_id_number'

        ];
        if ($discount_page == true){
            array_push($columns,'fees.id as fees_id');
        }
        $column2 = [
            DB::raw('academic_statuses.name AS academic_status_name'),
            'student_id',
            'student_class_id',
            'has_hold_subject',
            'has_fail'
        ];
        $column3 = [
            "class_id",
            "level",
            "shift",
            DB::raw("academic_years.start_year AS start_years"),
            DB::raw("academic_years.end_year AS end_years"),
            DB::raw("colleges.name AS college_name"),
            DB::raw("departments.name AS department_name")
        ];

        if ($report == true) {
            $columns = [
                'students.national_id_number',
                'students.date_of_birth',
                'students.city',
                'students.phone',
                'students.primary_school_name',
                'students.score_average_before',
                'students.score_average_after',
                'students.primary_school_speciality',
                DB::raw('students.student_id as st_uni_id'),
                "first_name",
                "father_name",
                "middle_name",
                "last_name",
                "surname",
                "full_name",
                "students.student_id",
                "photo",
                "gender",
                "level",
                "shift",
                "start_year",
                "end_year",
                "college_name",
                "department_name",
                "academic_status_name",
                'town_name',
                'date_of_birth',
                'town',
                'general',
                'enrollment_channel',
                'primary_school_name',
                'score_average_before',
                'score_average_after',
                DB::raw("primary_school_graduation_years.start_year AS dstart_year"),
                DB::raw("primary_school_graduation_years.end_year AS dend_year"),
                'phone',
                'national_id_number'

            ];
        }


        $statuses = DB::table('statuses')->select($column2)
            ->join('academic_statuses', 'statuses.academic_status_id', '=', 'academic_statuses.id');
            // ->where('statuses.is_active', '=', 1);




        if ($filters->input('academic_statuses_id') != null) {
            $statuses->where('academic_statuses.id', "=", $filters->input('academic_statuses_id'));
        }

        if ($filters->input('tarhel_page') == 1) {
            //$statuses->where('academic_statuses.special_statues', '<>', 2);
        }


        $st = DB::table('students')->select($columns);
        $st->leftjoin('towns', 'towns.id', '=', 'students.city');
        $st->leftjoin('primary_school_specialty_general', 'primary_school_specialty_general.id', '=', 'students.primary_school_specialty_general_id');
        $st->leftjoin('enrollment_channels', 'enrollment_channels.id', '=', 'students.enrollment_channel_id');
        $st->leftjoin('primary_school_graduation_years', 'primary_school_graduation_years.id', '=', 'students.primary_school_graduation_year');




        $st->joinSub($statuses, 'st', function ($join) {
            $join->on('st.student_id', '=', 'students.student_id');
        });


        $studentClass = DB::table('student_classes')->select($column3)
            ->Join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->leftJoin('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id');


        // Search for a user based on their college_id.
        if ($filters->input('college_id') != null) {
            $studentClass->where('student_classes.college_id', "=", $filters->input('college_id'));
        }

        if ($filters->input('academic_year_id') != null) {
            $studentClass->where('student_classes.academic_year_id', "=", $filters->input('academic_year_id'));
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


        if ($filters->has('class_id')) {
            $studentClass->where('class_id', '=', $filters->get('class_id'));
        }


        $st->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'st.student_class_id');
        });

        if ($filters->input('gender') != null) {
            $st->where('gender', "=", $filters->input('gender'));
        }
        if ($filters->has('search_all')) {
            $st->whereRaw('(full_name like "%'.$filters->get('search_all').'%" or
            students.student_id like "%'.$filters->get('search_all').'%")');

        }

        //$st->orderBy('full_name');
        return $st;

    }
    public static function applytarheel(Request $filters = null, $filtered = true, $show = false, $student_id = null, $report = false, $discount_page = false)
    {
        $columns = [
            DB::raw('students.id as std_programming_id'),
            DB::raw('students.student_id as st_uni_id'),
            "first_name",
            "father_name",
            "middle_name",
            "last_name",
            "surname",
            "full_name",
            "students.student_id",
            "photo",
            "gender",
            "level",
            "shift",
            "start_years",
            "end_years",
            "college_name",
            "department_name",
            "academic_status_name",
            'has_hold_subject',
            'has_fail',
            'town_name',
            'date_of_birth',
            'town',
            'general',
            'enrollment_channel',
            'primary_school_name',
            'score_average_before',
            'score_average_after',
            DB::raw("primary_school_graduation_years.start_year AS dstart_year"),
            DB::raw("primary_school_graduation_years.end_year AS dend_year"),
            'phone',
            'national_id_number'

        ];
        if ($discount_page == true){
            array_push($columns,'fees.id as fees_id');
        }
        $column2 = [
            DB::raw('academic_statuses.name AS academic_status_name'),
            'student_id',
            'student_class_id',
            'has_hold_subject',
            'has_fail'
        ];
        $column3 = [
            "class_id",
            "level",
            "shift",
            DB::raw("academic_years.start_year AS start_years"),
            DB::raw("academic_years.end_year AS end_years"),
            DB::raw("colleges.name AS college_name"),
            DB::raw("departments.name AS department_name")
        ];

        if ($report == true) {
            $columns = [
                'students.national_id_number',
                'students.date_of_birth',
                'students.city',
                'students.phone',
                'students.primary_school_name',
                'students.score_average_before',
                'students.score_average_after',
                'students.primary_school_speciality',
                DB::raw('students.student_id as st_uni_id'),
                "first_name",
                "father_name",
                "middle_name",
                "last_name",
                "surname",
                "full_name",
                "students.student_id",
                "photo",
                "gender",
                "level",
                "shift",
                "start_year",
                "end_year",
                "college_name",
                "department_name",
                "academic_status_name",
                'town_name',
                'date_of_birth',
                'town',
                'general',
                'enrollment_channel',
                'primary_school_name',
                'score_average_before',
                'score_average_after',
                DB::raw("primary_school_graduation_years.start_year AS dstart_year"),
                DB::raw("primary_school_graduation_years.end_year AS dend_year"),
                'phone',
                'national_id_number'

            ];
        }


        $statuses = DB::table('statuses')->select($column2)
            ->join('academic_statuses', 'statuses.academic_status_id', '=', 'academic_statuses.id')
            ->where('statuses.is_active', '=', 1);




        if ($filters->input('academic_statuses_id') != null) {
            $statuses->where('academic_statuses.id', "=", $filters->input('academic_statuses_id'));
        }

        if ($filters->input('tarhel_page') == 1) {
            $statuses->where('academic_statuses.special_statues', '<>', 2);
        }


        $st = DB::table('students')->select($columns);
        $st->leftjoin('towns', 'towns.id', '=', 'students.city');
        $st->leftjoin('primary_school_specialty_general', 'primary_school_specialty_general.id', '=', 'students.primary_school_specialty_general_id');
        $st->leftjoin('enrollment_channels', 'enrollment_channels.id', '=', 'students.enrollment_channel_id');
        $st->leftjoin('primary_school_graduation_years', 'primary_school_graduation_years.id', '=', 'students.primary_school_graduation_year');




        $st->joinSub($statuses, 'st', function ($join) {
            $join->on('st.student_id', '=', 'students.student_id');
        });


        $studentClass = DB::table('student_classes')->select($column3)
            ->Join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->leftJoin('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id');


        // Search for a user based on their college_id.
        if ($filters->input('college_id') != null) {
            $studentClass->where('student_classes.college_id', "=", $filters->input('college_id'));
        }

        if ($filters->input('academic_year_id') != null) {
            $studentClass->where('student_classes.academic_year_id', "=", $filters->input('academic_year_id'));
        }else {
          $academic_years = DB::table('academic_years')->where('active_year',1)->first();
          // $academic_years = DB::table('academic_years')->first();
            $studentClass->where('student_classes.academic_year_id', "=", $academic_years->id);
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


        if ($filters->has('class_id')) {
            $studentClass->where('class_id', '=', $filters->get('class_id'));
        }


        $st->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'st.student_class_id');
        });

        if ($filters->input('gender') != null) {
            $st->where('gender', "=", $filters->input('gender'));
        }
        if ($filters->has('search_all')) {
            $st->whereRaw('(full_name like "%'.$filters->get('search_all').'%" or
            students.student_id like "%'.$filters->get('search_all').'%")');

        }

        $st->orderBy('full_name');
        return $st;

    }

    public static function applyfee(Request $filters = null, $filtered = true, $show = false, $student_id = null, $report = false, $discount_page = false)
    {

        $columns = [
            DB::raw('students.id as std_programming_id'),
            DB::raw('students.student_id as st_uni_id'),
            "first_name",
            "father_name",
            "middle_name",
            "last_name",
            "surname",
            "full_name",
            "students.student_id",
            "photo",
            "gender",
            "level",
            "shift",
            "start_years",
            "end_years",
            "college_name",
            "department_name",
            "academic_status_name",
            'has_hold_subject',
            'has_fail',
            'town_name',
            'date_of_birth',
            'town',
            'general',
            'enrollment_channel',
            'primary_school_name',
            'score_average_before',
            'score_average_after',
            DB::raw("primary_school_graduation_years.start_year AS dstart_year"),
            DB::raw("primary_school_graduation_years.end_year AS dend_year"),
            'phone',
            'national_id_number'

        ];
        if ($discount_page == true){
            array_push($columns,'fees.id as fees_id');
        }
        $column2 = [
            DB::raw('academic_statuses.name AS academic_status_name'),
            'student_id',
            'student_class_id',
            'has_hold_subject',
            'has_fail'
        ];
        $column3 = [
            "class_id",
            "level",
            "shift",
            DB::raw("academic_years.start_year AS start_years"),
            DB::raw("academic_years.end_year AS end_years"),
            DB::raw("colleges.name AS college_name"),
            DB::raw("departments.name AS department_name")
        ];

        if ($report == true) {
            $columns = [
                'students.national_id_number',
                'students.date_of_birth',
                'students.city',
                'students.phone',
                'students.primary_school_name',
                'students.score_average_before',
                'students.score_average_after',
                'students.primary_school_speciality',
                DB::raw('students.student_id as st_uni_id'),
                "first_name",
                "father_name",
                "middle_name",
                "last_name",
                "surname",
                "full_name",
                "students.student_id",
                "photo",
                "gender",
                "level",
                "shift",
                "start_year",
                "end_year",
                "college_name",
                "department_name",
                "academic_status_name",
                'town_name',
                'date_of_birth',
                'town',
                'general',
                'enrollment_channel',
                'primary_school_name',
                'score_average_before',
                'score_average_after',
                DB::raw("primary_school_graduation_years.start_year AS dstart_year"),
                DB::raw("primary_school_graduation_years.end_year AS dend_year"),
                'phone',
                'national_id_number'

            ];
        }


        $statuses = DB::table('statuses')->select($column2)
            ->join('academic_statuses', 'statuses.academic_status_id', '=', 'academic_statuses.id')
            ->where('statuses.is_active', '=', 1);




        if ($filters->input('academic_statuses_id') != null) {
            $statuses->where('academic_statuses.id', "=", $filters->input('academic_statuses_id'));
        }

        if ($filters->input('tarhel_page') == 1) {
            //$statuses->where('academic_statuses.special_statues', '<>', 2);
        }


        $st = DB::table('students')->select($columns);
        $st->leftjoin('towns', 'towns.id', '=', 'students.city');
        $st->leftjoin('primary_school_specialty_general', 'primary_school_specialty_general.id', '=', 'students.primary_school_specialty_general_id');
        $st->leftjoin('enrollment_channels', 'enrollment_channels.id', '=', 'students.enrollment_channel_id');
        $st->leftjoin('primary_school_graduation_years', 'primary_school_graduation_years.id', '=', 'students.primary_school_graduation_year');




        $st->joinSub($statuses, 'st', function ($join) {
            $join->on('st.student_id', '=', 'students.student_id');
        });


        $studentClass = DB::table('student_classes')->select($column3)
            ->Join('academic_years', 'academic_years.id', '=', 'student_classes.academic_year_id')
            ->join('colleges', 'colleges.id', '=', 'student_classes.college_id')
            ->leftJoin('departments', 'departments.id', '=', 'student_classes.department_id')
            ->join('shifts', 'shifts.id', '=', 'student_classes.shift_id')
            ->join('levels', 'levels.id', '=', 'student_classes.level_id');


        // Search for a user based on their college_id.
        if ($filters->input('college_id') != null) {
            $studentClass->where('student_classes.college_id', "=", $filters->input('college_id'));
        }

        if ($filters->input('academic_year_id') != null) {
            $studentClass->where('student_classes.academic_year_id', "=", $filters->input('academic_year_id'));
        }else {
          //$academic_years = DB::table('academic_years')->where('active_year',1)->first();
          // $academic_years = DB::table('academic_years')->first();
          //  $studentClass->where('student_classes.academic_year_id', "=", $academic_years->id);
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


        if ($filters->has('class_id')) {
            $studentClass->where('class_id', '=', $filters->get('class_id'));
        }


        $st->joinSub($studentClass, 'c', function ($join) {
            $join->on('c.class_id', '=', 'st.student_class_id');
        });

        if ($filters->input('gender') != null) {
            $st->where('gender', "=", $filters->input('gender'));
        }
        if ($filters->has('search_all')) {
            $st->whereRaw('(full_name like "%'.$filters->get('search_all').'%" or
            students.student_id like "%'.$filters->get('search_all').'%")');

        }

        //$st->orderBy('full_name');
        return $st;

    }

}
