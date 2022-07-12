<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
class Main implements FromCollection
{
  public function collection()
      {
        $student=DB::table('statuses')->where('student_class_id','92')->where('is_active','1')->get();
          return $student;
      }
}
