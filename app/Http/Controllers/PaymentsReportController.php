<?php

namespace App\Http\Controllers;

use App\AcademicYear;
use App\College;
use App\Discount;
use App\Rules\Number;
use App\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class PaymentsReportController extends Controller
{

    public function paid_form()
    {
        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
        $colleges = College::all();
        $shifts = Shift::all();
        return view('admin.finance.reports.paid_fees.index', compact('academics', 'colleges', 'shifts'));

    }

    public function paid_download(Request $request)
    {
        $rules = [
            'academic_year_id' => 'required',
            'amount_type' => 'required',
            'amount_boundaries' => 'required',
            'fee_type' => 'required',
            'amount' => [
                'required',
                new Number(),
                function ($attribute, $value, $fail) {

                    if ($value != null) {
                        if (\request('amount_type') == 1 & ($value > 100 || $value <= 0)) {
                            $fail('يجب ادخال نسبة مئوية صحيحة 1-100');
                        }
                        if ($value <= 0) {
                            $fail('يجب ادخال قيمة اعلى من 0');
                        }
                    }
                }
            ],
        ];

        $messages = [
            'required' => 'لايمكن ان يكون الحقل فارغا',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();


        $columns = [
            'students.student_id',
            "first_name",
            "father_name",
            "middle_name",
            "last_name",
            "surname",
            "full_name",
            "level",
            "colleges.name as college_name",
            "departments.name as department_name",
            'dsc.discount_total',
            'payments.payment_amount',
            'payments.payment_date',
            'payments.payment_id',
            'fees.required_amount'
        ];


        $discount = DB::query()->fromSub(function ($query) {
            $query->from('fees')->select(['fees.id as fees_id', 'fees.student_id', DB::raw('CASE WHEN discounts.discount_type = 1 THEN
            fees.required_amount * discounts.discount_amount / 100 WHEN discounts.discount_type = 2 THEN discounts.discount_amount ELSE 0 END as x')])
                ->leftJoin('fees_discounts', 'fees_discounts.fees_id', 'fees.id')
                ->leftJoin('discounts', 'discounts.id', 'fees_discounts.discount_id')
                ->where('fees.academic_year_id', '=', \request()->input('academic_year_id'));
        }, 'd')->select(['d.fees_id', DB::raw("ifnull(SUM(d.x),0) as discount_total")])->groupBy(['d.fees_id', 'd.student_id']);


        $data = DB::table('students')->select($columns)
            ->join('fees', 'fees.student_id', '=', 'students.student_id')
            ->leftJoin('payments', 'payments.fees_id', '=', 'fees.id')
            ->join('colleges', 'colleges.id', 'fees.college_id')
            ->join('departments', 'departments.id', 'fees.department_id')
            ->join('levels', 'levels.id', 'fees.level_id')
            ->where('fees.academic_year_id', '=', $request->input('academic_year_id'))
            ->where('fees.college_id', '=', $request->input('college_id'))
            ->where('fees.department_id', '=', $request->input('department_id'))
            ->where('fees.level_id', '=', $request->input('level_id'))
            ->where('fees.fee_type', '=', $request->input('fee_type'))
            ->where('fees.shift_id', '=', $request->input('shift_id'))
            ->joinSub($discount, 'dsc', 'dsc.fees_id', '=', 'fees.id')
            ->get();

        $array = [];
        foreach ($data as $d) {
            $array[$d->student_id][] = $d;
        }

        $table = '<style>
               * {
               direction: rtl;
               }
                table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}

                table.first th {
                     background-color: #ccc;
                }


            </style>';
        $table .= '<table width="100%" cellpadding="2" border="0"><tr><th width="5%">ت</th><th class="10%">الرقم الجامعي</th><th class="25%">الاسم</th><th>القسم والمرحلة</th><th>المبلغ المطلوب</th><th>مبلغ التخفيض</th><th colspan="3">المبلغ المدفوع</th><th>النسبة</th></tr>';
        $array_data = [];
        $sqn = 1;
        foreach ($array as $a) {
            $total_paid = 0;
            $student_id = $a[0]->student_id;
            $student_name = $a[0]->full_name;
            $level = $a[0]->level;
            $department_name = $a[0]->department_name;
            $discount_total = $a[0]->discount_total;
            $required_amount = $a[0]->required_amount;
            $payments = [];
            $inner_table_head = '<td>رقم</td><td>التأريخ</td><td>المبلغ</td>';
            $inner_table = '';
            foreach ($a as $r) {
                $total_paid += $r->payment_amount;
                array_push($payments, $r);
                $inner_table .= '<tr><td>' . $r->payment_id . '</td><td>' . $r->payment_date . '</td><td>' . $r->payment_amount . '</td></tr>';
            }
            $inner_table .= '<tr><td>المجموع</td><td colspan="2"><b>' . $total_paid . '</b></td></tr>';


            $final_paid = $total_paid + $discount_total;
            $span = 1;
            if (sizeof($payments) > 0 && $payments[0]->payment_amount != null) {
                $span = sizeof($payments) + 2;
            } elseif (sizeof($payments) == 0) {
                $span = 1;
            }

            $print_percentage = 0;
            if ($request->input('amount_type') == 1) {
              $paid_percentage=0;
              if ($required_amount>0) {
                $paid_percentage = (int)(($final_paid / $required_amount) * 100);
              }
                $print_percentage = $paid_percentage;
            } elseif ($request->input('amount_type') == 2) {
                $paid_percentage = $final_paid;
                $print_percentage = (int)(($final_paid / $required_amount) * 100);
            }
            if ($request->input('amount_boundaries') == 1) {
                if ($paid_percentage <= $request->input('amount')) {

                    $table .= '
<tr>
<td rowspan="' . $span . '">' . $sqn . '</td>
<td rowspan="' . $span . '">' . $student_id . '</td>
<td rowspan="' . $span . '">' . $student_name . '</td>
<td rowspan="' . $span . '">' . $department_name . '/' . $level . '</td>
<td rowspan="' . $span . '">' . $required_amount . '</td>
<td rowspan="' . $span . '">' . $discount_total . '</td>
';
                    if (sizeof($payments) > 0 && $payments[0]->payment_amount != null) {
                        $table .= $inner_table_head;
                    } else {
                        $table .= "<td colspan='3'>0</td>";
                    }
                    $table .= '
<td rowspan="' . $span . '">' . $print_percentage . '%</td>
</tr>
';
                    if (sizeof($payments) > 0 && $payments[0]->payment_amount != null) {
                        $table .= $inner_table;
                    }
                    array_push($array_data, array('student_id' => $student_id, 'student_name' => $student_name, 'level' => $level,
                        'department_name' => $department_name, 'total_discount' => $discount_total, 'total_paid' => $total_paid, 'required_amount' => $required_amount, 'payments' => $payments, 'print_percentage' => $print_percentage . '%'));
                }
            } elseif ($request->input('amount_boundaries') == 2) {
                if ($paid_percentage >= $request->input('amount')) {
                    $table .= '
<tr>
<td rowspan="' . $span . '">' . $sqn . '</td>
<td rowspan="' . $span . '">' . $student_id . '</td>
<td rowspan="' . $span . '">' . $student_name . '</td>
<td rowspan="' . $span . '">' . $department_name . '/' . $level . '</td>
<td rowspan="' . $span . '">' . $required_amount . '</td>
<td rowspan="' . $span . '">' . $discount_total . '</td>
';
                    if (sizeof($payments) > 0 && $payments[0]->payment_amount != null) {
                        $table .= $inner_table_head;
                    } else {
                        $table .= "<td colspan='3'>0</td>";
                    }
                    $table .= '
<td rowspan="' . $span . '">' . $print_percentage . '%</td>
</tr>
';
                    if (sizeof($payments) > 0 && $payments[0]->payment_amount != null) {
                        $table .= $inner_table;
                    }
                    array_push($array_data, array('student_id' => $student_id, 'student_name' => $student_name, 'level' => $level,
                        'department_name' => $department_name, 'total_discount' => $discount_total, 'total_paid' => $total_paid, 'required_amount' => $required_amount, 'payments' => $payments, 'print_percentage' => $print_percentage . '%'));
                }
            }

            $sqn += 1;
        }
        $table .= '</table>';

        //return $table;

        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';

        PDF::setLanguageArray($lg);
        PDF::SetCreator(PDF_CREATOR);
        PDF::SetAuthor('نظام ادارة الجامعات والكلية العراقية');
        PDF::SetTitle('تقرير الدفوعات المتوقعه والمدفوعه.');

        PDF::SetMargins(5, 30, 5);
        PDF::setHeaderCallback(function ($pdf) {
            $pdf->SetY(5);
            // Set font
            $pdf->SetFont('aealarabiya', '', 12);;
            $path = url('/images/book_logo.JPG');
            $img = '<img src="' . $path . '" width="64">';
            // Title
            $html = '
            <table>
                <tr>
                    <td width="33.33%"></td>
                    <td width="33.33%" align="center" rowspan="2">' . $img . '</td>
                    <td width="33.33%"></td>
                </tr>
                <tr>
                    <td width="33.33%" align="center" >
                    جامعة البيان
                    <br>
                    قسم الشؤون المالية
                    </td>
                    <td width="33.33%;" align="center">
                    <b>AL_BAYAN UNIVERSITY</b>
                    <br>
                    <b>Finance Department</b>
                    </td>
                </tr>
            </table>
            <hr>

            ';
            PDF::writeHTML($html, true, false, true, false, '');
        });
        PDF::setFooterCallback(function ($pdf) {
            $pdf->SetY(-15);
            // Set font
            $pdf->SetFont('aealarabiya', '', 10);
            $pdf->Cell(0, 0, 'جميع الحقوق محفوظة / قسم تكنولوجيا المعلومات ©2019', 0, 1, 'C', 0, '', 0);;
        });

        PDF::AddPage();
        //PDF::SetFont('dejavusans', '', 9);
        PDF::SetFont('aealarabiya', '', 14);
        $year = AcademicYear::find($request->input('academic_year_id'));
        $amount = $request->input('amount');
        if ($request->input('amount_type') == 1) {
            $header = " بنسبة $amount % ";
        } elseif ($request->input('amount_type') == 2) {
            $a = number_format($amount);
            $header = " بمبلغ $a د.ع ";

        }
        $html1 = '<table cellpadding="2" cellspacing="0"><tr><td width="100%" align="center"> م / تقرير بأجمالية الطلبة الدافعين للقسط  ' . $header . ' للعام الدراسي ' . $year->end_year . '-' . $year->start_year . '</td></tr></table>';
        //PDF::writeHTML($html1, true, false, true, false, '');

        //PDF::Ln();
        //PDF::SetFontSize(7);

        PDF::writeHTML($table, true, false, true, false, '');
        PDF::Output('payments1.pdf');
        return $table;


    }

    public function discount_form()
    {
        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
        $colleges = College::all();
        $shifts = Shift::all();
        $discounts = Discount::all();
        return view('admin.finance.reports.discount_fees.index', compact('academics', 'colleges', 'shifts', 'discounts'));

    }

    public function discount_download(Request $request)
    {
        $rules = [
            'academic_year_id' => 'required',
        ];

        $messages = [
            'academic_year_id.required' => 'يجب اختيار العام الدراسي اولاً',
        ];
        Validator::make($request->all(), $rules, $messages)->validate();


        $columns = [
            'students.student_id',
            "full_name",
            "level",
            "colleges.name as college_name",
            "departments.name as department_name",
            'dsc.discount_total',
            'fees.required_amount',
            'discounts.discount_name',
            DB::raw("CASE WHEN discounts.discount_type = 1 THEN concat(discounts.discount_amount,'%') WHEN discounts.discount_type = 2 THEN discounts.discount_amount ELSE 0 END AS discount_amount"),
            DB::raw("CASE WHEN discounts.discount_type = 1 THEN fees.required_amount * discounts.discount_amount / 100 WHEN discounts.discount_type = 2 THEN discounts.discount_amount ELSE 0 END AS discount_by_fee"),
        ];


        $discount = DB::query()->fromSub(function ($query) {
            $query->from('fees')->select(['fees.id as fees_id', 'fees.student_id', DB::raw('CASE WHEN discounts.discount_type = 1 THEN fees.required_amount * discounts.discount_amount / 100 WHEN discounts.discount_type = 2 THEN discounts.discount_amount ELSE 0 END as x')])
                ->leftJoin('fees_discounts', 'fees_discounts.fees_id', 'fees.id')
                ->leftJoin('discounts', 'discounts.id', 'fees_discounts.discount_id')
                ->where('fees.academic_year_id', '=', \request()->input('academic_year_id'));
        }, 'd')->select(['d.fees_id', DB::raw("ifnull(SUM(d.x),0) as discount_total")])->groupBy(['d.fees_id', 'd.student_id']);


        $data = DB::table('students')->select($columns)
            ->join('fees', 'fees.student_id', '=', 'students.student_id')
            ->join('fees_discounts', 'fees_discounts.fees_id', '=', 'fees.id')
            ->join('discounts', 'discounts.id', '=', 'fees_discounts.discount_id')
            ->join('colleges', 'colleges.id', 'fees.college_id')
            ->join('departments', 'departments.id', 'fees.department_id')
            ->join('levels', 'levels.id', 'fees.level_id')
            ->where('fees.academic_year_id', '=', $request->input('academic_year_id'))
            ->joinSub($discount, 'dsc', 'dsc.fees_id', '=', 'fees.id')
            ->get();


        $array = [];
        foreach ($data as $d) {
            $array[$d->student_id][] = $d;
        }

        $table = '<style>
               * {
               direction: rtl;
               }
               table thead tr td {
                background: #9d9d9d;
               }
                table, th, td {
                  border: 1px solid black;
                  border-collapse: collapse;
                  vertical-align: middle;
                  text-align: center;
                }

                table.first th {
                     background-color: #ccc;
                     height: 10px;
                }
                .title {
                background-color: #ccc;
                height: 10px;
                }



            </style>';
        $table .= '<table width="100%" cellpadding="2" border="0" class="first"><thead><tr><th width="5%">ت</th><th width="10%">الرقم الجامعي</th><th width="25%">الاسم</th><th width="20%">القسم والمرحلة</th><th colspan="3" width="40%">تفاصيل التخفيض</th></tr></thead>';

        $sqn = 1;
        $overall_discount = 0;
        foreach ($array as $a) {
            $student_id = $a[0]->student_id;
            $student_name = $a[0]->full_name;
            $level = $a[0]->level;
            $department_name = $a[0]->department_name;
            $discount_total = $a[0]->discount_total;
            $overall_discount += $discount_total;
            $inner_table_head = '<td class="title" width="13.33%">العنوان</td><td width="13.33%" class="title">القيمة</td><td width="13.33%" class="title">المبلغ</td>';
            $inner_table = '';
            $span = 2;
            foreach ($a as $discount) {
                $inner_table .= '<tr><td>' . $discount->discount_name . '</td><td>' . $discount->discount_amount . '</td><td>' . number_format($discount->discount_by_fee) . '</td></tr>';
                $span += 1;
            }
            $inner_table .= '<tr><td class="title">المجموع</td><td colspan="2"><b>' . number_format($discount_total) . '</b></td></tr>';

            $table .= '
<tr>
<td width="5%" rowspan="' . $span . '">' . $sqn . '</td>
<td width="10%" rowspan="' . $span . '">' . $student_id . '</td>
<td width="25%" rowspan="' . $span . '">' . $student_name . '</td>
<td width="20%" rowspan="' . $span . '">' . $department_name . '/' . $level . '</td>
' . $inner_table_head . '
</tr>
';
            $table .= $inner_table;
            $sqn += 1;
        }
        $table .= '</table>';

        //return $table;

        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';

        PDF::setLanguageArray($lg);
        PDF::SetCreator(PDF_CREATOR);
        PDF::SetAuthor('نظام ادارة الجامعات والكلية العراقية');
        PDF::SetTitle('تقرير الدفوعات المتوقعه والمدفوعه.');

        PDF::SetMargins(5, 30, 5);
        PDF::setHeaderCallback(function ($pdf) {
            $pdf->SetY(5);
            // Set font
            $pdf->SetFont('aealarabiya', '', 12);;
            $path = url('/images/book_logo.JPG');
            $img = '<img src="' . $path . '" width="64">';
            // Title
            $html = '
            <table>
                <tr>
                    <td width="33.33%"></td>
                    <td width="33.33%" align="center" rowspan="2">' . $img . '</td>
                    <td width="33.33%"></td>
                </tr>
                <tr>
                    <td width="33.33%" align="center" >
                    جامعة البيان
                    <br>
                    قسم الشؤون المالية
                    </td>
                    <td width="33.33%;" align="center">
                    <b>AL_BAYAN UNIVERSITY</b>
                    <br>
                    <b>Finance Department</b>
                    </td>
                </tr>
            </table>
            <hr>

            ';
            PDF::writeHTML($html, true, false, true, false, '');
        });
        PDF::setFooterCallback(function ($pdf) {
            $pdf->SetY(-15);
            // Set font
            $pdf->SetFont('aealarabiya', '', 10);
            $pdf->Cell(0, 0, 'جميع الحقوق محفوظة / قسم تكنولوجيا المعلومات ©2019', 0, 1, 'C', 0, '', 0);;
        });

        PDF::AddPage();
        //PDF::SetFont('dejavusans', '', 9);
        PDF::SetFont('aealarabiya', '', 14);
        $year = AcademicYear::find($request->input('academic_year_id'));

        $html1 = '<table cellpadding="2" cellspacing="0"><tr><td width="100%" align="center"> م / تقرير بأجمالية الطلبة المخفضة اجورهم للعام الدراسي  ' . $year->end_year . '-' . $year->start_year . '</td></tr></table>';
        PDF::writeHTML($html1, true, false, true, false, '');

        PDF::Ln();
        PDF::SetFontSize(7);

        PDF::writeHTML($table, true, false, true, false, '');
        PDF::Ln();
        $table = "<table><tr><th colspan='2'>الخلاصة</th></tr><tr><td>مجموع مبلغ التخفيض</td><td>" . $overall_discount . "</td></tr></table>";
        PDF::writeHTML($table, true, false, true, false, '');

        PDF::Output('payments1.pdf');


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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
