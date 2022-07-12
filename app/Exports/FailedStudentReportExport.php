<?php

namespace App\Exports;

use App\JobCategory;
use App\JobType;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FailedStudentReportExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $data;
    public $academic_year;

    public function __construct($data,$academic_year)
    {
        $this->data = $data;
        $this->academic_year = $academic_year;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new FailedStudentReportExport($this->data,$this->academic_year);
        return $sheets;
    }
}
