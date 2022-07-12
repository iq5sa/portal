<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
//    public $timestamps = false;
    protected $fillable = [
        'first_name',
        'father_name',
        'middle_name',
        'last_name',
        'surname',
        'exam_number',
        'exam_attempt_number',
        'primary_school_speciality',
        'application_certificate',
        'primary_school_graduation_year',
        'primary_school_name',
        'total_score',
        'number_of_modules_studied',
        'score_average_before',
        'score_average_after',
        'has_institution_certificate',
        'has_english_module',
        'enrollment_channel_id',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'social_status',
        'national_id_number',
        'national_id_issue_date',
        'national_id_issuer',
        'certificate_of_iraqi_nationality',
        'certificate_of_iraqi_nationality_issue_date',
        'certificate_of_iraqi_nationality_issuer',
        'city',
        'town',
        'township',
        'neighbor',
        'district_no',
        'side_street_no',
        'house_number',
        'phone',
        'house_phone_no',
        'email',
        'near_point',
        'ministry_name',
        'department',
        'work_place',
        'career_title',
        'photo',
        'date_registered',
        'acceptance_year',
        'photoFromCamera',
    ];

    public function documents(){
        return $this->hasMany(StudentDocument::class,'student_id');
    }

    public function classes(){
        return $this->belongsToMany(StudentClass::class,'statuses','student_id','student_class_id','id','class_id');
    }

    public function primary_school_graduation_year(){
        return $this->belongsTo(primarySchoolGraduationYear::class,'id');
    }
}
