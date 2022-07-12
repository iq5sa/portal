<?php

namespace App\Exports;

use App\JobCategory;
use App\JobType;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class JobRequestExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $job_category_id;
    public $job_types;
    public $certificate;
    public $print_certificate;

    public function __construct($job_category_id, $job_types = null, $certificate = null)
    {
        switch ($certificate) {
            case "بكالوريوس":
                $this->certificate = 1;
                break;
            case "ماجستير":
                $this->certificate = 2;
                break;
            case "دكتوراه":
                $this->certificate = 3;
                break;
            case null:
                $this->certificate = null;
                break;
            case 0:
                $this->certificate = null;
                break;
        }

        if ($job_types == 0) {
            $this->job_types = null;
        } else {
            $this->job_types = $job_types;
        }

        $this->job_category_id = $job_category_id;
        $this->print_certificate = $certificate;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        if ($this->certificate != null || $this->job_types != null) {
            $title = "جميع الاختصاصات";
            $spe = "";
            $cer = "دكتوراه / ماجستير / بكالوريوس";
            if ($this->job_types !=null && $this->certificate ==null){
                $t = JobType::find($this->job_types);
                $title = $t->title;
                $spe = $t->speciality;

            }elseif ($this->job_types ==null && $this->certificate !=null){
                $cer = $this->print_certificate;
            }elseif($this->job_types !=null && $this->certificate !=null){
                $t = JobType::find($this->job_types);
                $title = $t->title;
                $spe = $t->speciality;
                $cer = $this->print_certificate;
            }
            $sheets[] = new JobsPerTypeSheet($this->job_category_id,$this->job_types,$this->certificate, null,$title,$spe,$cer,true);
        }else{
            $sheets[] = new JobsStatisticsSheet();
            $cat = JobCategory::find($this->job_category_id);
            $job_types = $cat->types;
            foreach ($job_types as $job_type){
                $sheets[] = new JobsPerTypeSheet($this->job_category_id,null,null,$job_type->id,$job_type->title,$job_type->speciality,$job_type->certificate,false);
            }
        };

        return $sheets;
    }
}
