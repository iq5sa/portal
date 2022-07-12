<?php

namespace App\Exports;

use App\JobCategory;
use App\JobType;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StudentReportExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new StudentReportSheet($this->data);
        return $sheets;
    }
}
