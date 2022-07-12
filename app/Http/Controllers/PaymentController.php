<?php

namespace App\Http\Controllers;

use App\AcademicYear;
use App\Payment;
use App\PaymentSearch;
use App\Rules\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PDF;
class PaymentController extends Controller
{


    function __construct()
    {

        $this->middleware('permission:أدخال ايراد', ['only' => [
            'store',
        ]]);
        $this->middleware('permission:تعديل ايراد', ['only' => ['update']]);
        $this->middleware('permission:ابطال الايراد', ['only' => ['revert']]);
        $this->middleware('permission:حذف ايراد', ['only' => ['destroy']]);
        $this->middleware('permission:البحث عن الوصولات', ['only' => ['show_search_form','search']]);
        $this->middleware('permission:الكشف حسب التأريخ', ['only' => ['show_search_between_dates_form','search_between_dates']]);
    }
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function revert(Request $request)
    {
        $rules = [
            'payment_id' => ['required', new Number()],
            'revert' => 'required'
        ];

        $v = Validator::make($request->all(), $rules);
        $v->validate();

        $payment = DB::table('payments')
            ->where('payment_id', '=', $request->input('payment_id'))
            ->update(['revert' => $request->input('revert')]);
        return response()->json($payment, 200);

    }

    public function store(Request $request)
    {



        if ($request->ajax()) {
          if ($request->input('payment_amount')=="10000") {
            $rules = [
                'payment_id' => [ 'required', new Number()],
                'payment_date' => 'required|date',
                'payment_amount' => ['required', new Number(),

                ],
                'payment_method' => 'required|in:1,2,3',
                'fees_id' => 'required',
                'student_id' => 'required'
            ];
            $messages = [
                'required' => 'لايمكن ان يكون الحقل فارغا',
                'in' => 'يجب تحديد احد الاختيارات المتاحة فقط.',
                'date' => 'التأريخ غير صالح',
                'unique' => 'رقم الوصل مكرر'
            ];
          }else {
            $rules = [
                'payment_id' => ['required', new Number()],
                'payment_date' => 'required|date',
                'payment_amount' => ['required', new Number(),

                ],
                'payment_method' => 'required|in:1,2,3',
                'fees_id' => 'required',
                'student_id' => 'required'
            ];
            $messages = [
                'required' => 'لايمكن ان يكون الحقل فارغا',
                'in' => 'يجب تحديد احد الاختيارات المتاحة فقط.',
                'date' => 'التأريخ غير صالح',
                'unique' => 'رقم الوصل مكرر'
            ];
          }

            $v = Validator::make($request->all(), $rules, $messages);
            $v->validate();

            $payment = new Payment();
            $payment->payment_id = $request->input('payment_id');
            $payment->payment_date = $request->input('payment_date');
            $payment->payment_amount = $request->input('payment_amount');
            $payment->payment_method = $request->input('payment_method');
            $payment->fees_id = $request->input('fees_id');
            $payment->student_id = $request->input('student_id');
            $payment->user_id = auth()->user()->id;
            $payment->cheque_number = $request->input('cheque_number');
            $payment->cheque_date = $request->input('cheque_date');
            $payment->description = $request->input('description');
            $payment->save();
            $payment_count = Payment::all()->count();
            if ($payment_count > 0) {
                $last_payment_number = Payment::orderBy('payment_id', 'ASC')->get()->last()->payment_id + 1;
            } else {
                $last_payment_number = 0001;
            }
            Session::put('message', 'تم أضافة الوصل بنجاح!');

            return response()->json([
                'last_payment_number' => $last_payment_number

            ], 200);
        } else {
            abort(401);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'payment_id' => ['required', new Number()],
                'programing_id' => 'required',
                'fees_id' => 'required',
                'payment_date' => 'required|date',
                'payment_amount' => ['required', new Number(),
                    /*function ($attribute, $value, $fail) {
                        $data = DB::table('payments')->select([
                            'payments.student_id',
                            DB::raw('sum(payment_amount) as total_paid'),
                            'fees.required_amount'
                        ])->join('fees', 'fees.id', '=', 'payments.fees_id')
                            ->where('payments.student_id', '=', \request('student_id'))
                            ->where('payments.fees_id', '=', \request('fees_id'))
                            ->where('payments.revert', '=', 0)
                            ->where('payments.id', '!=', \request('programing_id'))
                            ->groupBy(['payments.student_id', 'fees.required_amount'])->first();
                        if ($data != null) {
                            $f_paid = $data->total_paid + $value;
                            if ($f_paid > $data->required_amount) {
                                $fail('عذرا لايمكن ان يكون المبلغ المدفوع يتجاوز القسط الدراسي');
                            }
                        } else {
                            $fees_required_amount = DB::table('fees')->select(['required_amount'])
                                ->where('id', '=', \request('fees_id'))
                                ->first()->required_amount;
                            if ($value > $fees_required_amount) {
                                $fail('عذرا لايمكن ان يكون المبلغ المدفوع يتجاوز القسط الدراسي');
                            }
                        }
                    }*/
                ],
                'payment_method' => 'required|in:1,2,3',
                'student_id' => 'required'
            ];
            $messages = [
                'required' => 'لايمكن ان يكون الحقل فارغا',
                'in' => 'يجب تحديد احد الاختيارات المتاحة فقط.',
                'date' => 'التأريخ غير صالح'
            ];
            $v = Validator::make($request->all(), $rules, $messages);
            $v->validate();

            $payment = Payment::find($request->input('programing_id'));
            $payment->payment_id = $request->input('payment_id');
            $payment->payment_date = $request->input('payment_date');
            $payment->payment_amount = $request->input('payment_amount');
            $payment->payment_method = $request->input('payment_method');
            $payment->description = $request->input('description');
            $payment->cheque_number = $request->input('cheque_number');
            $payment->cheque_date = $request->input('cheque_date');
            $payment->update();

        } else {
            abort(401);
        }
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);
        $payment->delete();
        return back();
    }

    public function show_search_form(){
        $payments = collect([]);
        $total = 0;
        return view('admin.finance.search_for_payment',compact('payments','total'));
    }
    public function search(Request $request){
        $payments = PaymentSearch::apply($request)->get();
        $total = 0;
        foreach($payments as $payment){
            if ($payment->revert == 0){
                $total += $payment->payment_amount;
            }
        }
        return view('admin.finance.search_for_payment',compact('payments','total'));
    }

    public function show_search_between_dates_form(){
        $payments = collect([]);
        $total = 0;
        return view('admin.finance.search_between_dates',compact('payments','total'));

    }
    public function search_between_dates(Request $request){
        $rules = [
            'payment_date_start' => 'required|date',
            'payment_date_end' => 'required|date',
        ];
        $messages = [
            'required' => 'لايمكن ان يكون الحقل فارغا',
            'date' => 'التأريخ غير صالح',
        ];
        Validator::make($request->all(),$rules,$messages)->validate();
        $payments = PaymentSearch::apply($request)->get();
        $total = 0;
        foreach($payments as $payment){
            if ($payment->revert == 0){
                $total += $payment->payment_amount;
            }
        }

        return view('admin.finance.search_between_dates',compact('payments','total'));

    }

    public function expected_payments_form(){
        $academics = AcademicYear::orderBy('start_year','asc')->get();
        return view('admin.finance.expected_payments_form',compact('academics'));
    }

    public function expected_payments_download(Request $request){
        $rules = [
            'academic_year_id' => ['required', new Number()],
        ];
        $messages = [
            'required' => 'لايمكن ان يكون الحقل فارغا',
        ];
        Validator::make($request->all(),$rules,$messages)->validate();



        $discount_total =
            DB::query()->fromSub(function ($query) {
                $query->from('fees')->select(['fees.id as fees_id',DB::raw('CASE WHEN discounts.discount_type = 1 THEN fees.required_amount * discounts.discount_amount / 100 WHEN discounts.discount_type = 2 THEN discounts.discount_amount ELSE 0 END as dis')])
                    ->join('fees_discounts','fees_discounts.fees_id','fees.id')
                    ->join('discounts','discounts.id','fees_discounts.discount_id');
            }, 'd')->select(['d.fees_id',DB::raw("sum(d.dis) as dds")])->groupBy(['d.fees_id'])->get();




        $total_required = DB::table('fees')->select([DB::raw('sum(fees.required_amount)'),'dds','fees.department_id','fees.college_id'])
            ->join('statuses','statuses.student_id','=','fees.student_id')
            ->where('statuses.academic_status_id','=',1)
            ->where('statuses.is_active','=',1)
            ->where('fees.academic_year_id','=',$request->input('academic_year_id'))
            ->leftJoinSub($discount_total,'discount_query','discount_query.fees_id','=','fees.id')
            ->groupBy(['fees.college_id','fees.department_id'])
            ->get();

        dd($total_required);

        /*$html = '<style>
               * {
               direction: rtl;
               }
                table.first {
                    color: #000;

                   border: none;
                }
                table.first td {
                    border: 1px solid #444444;
                    text-align: center;
                }
                table.first td.title {
                     background-color: #ccc;
                }

                table.first td.error {
                background-color: #b0191f;
                }



            </style>
            <table class="first" cellpadding="2" cellspacing="0">
             <tr>
              <td width="10%" class="title" rowspan="2"><b>القاعة</b></td>
              <td width="15%" class="title" colspan="2"><b>السبت</b></td>
              <td width="15%" class="title" colspan="2"><b>الاحد</b></td>
              <td width="15%" class="title" colspan="2"><b>الاثنين</b></td>
              <td width="15%" class="title" colspan="2"><b>الثلاثاء</b></td>
              <td width="15%" class="title" colspan="2"><b>الاربعاء</b></td>
              <td width="15%" class="title" colspan="2"><b>الاشغال الاسبوعي</b></td>
             </tr>
             <tr>

             <td width="7.5%" class="title"><b>عدد الساعات</b></td>
              <td width="7.5%" class="title"><b>النسبة</b></td>
              <td width="7.5%" class="title"><b>عدد الساعات</b></td>
              <td width="7.5%" class="title"><b>النسبة</b></td>
              <td width="7.5%" class="title"><b>عدد الساعات</b></td>
              <td width="7.5%" class="title"><b>النسبة</b></td>
              <td width="7.5%" class="title"><b>عدد الساعات</b></td>
              <td width="7.5%" class="title"><b>النسبة</b></td>
              <td width="7.5%" class="title"><b>عدد الساعات</b></td>
              <td width="7.5%" class="title"><b>النسبة</b></td>
              <td width="7.5%" class="title"><b>عدد الساعات الكلية</b></td>
              <td width="7.5%" class="title"><b>النسبة النهائية</b></td>

</tr>
             ';
        foreach ($rooms as $key => $r) {

            $html .= "<tr><td>$key</td>";

            $d = collect($r);
            $total_hours = 0;
            $ratio = 0;
            for ($i = 0; $i < 5; $i++) {
                $desired_object = $d->filter(function ($item) use ($i) {
                    return $item->day_number == $i;

                })->first();
                if ($desired_object == null) {
                    $html .= "<td>0:00</td><td>%0.00</td>";
                } else {
                    $class = "";
                    if ($desired_object->ocp_per_day > 100) {
                        $class = "error";
                    }
                    $html .= '<td>'.$desired_object->hours_per_day.'</td>' . '<td class="'.$class.'">%'.$desired_object->ocp_per_day.'</td>';
                    $total_hours += $desired_object->hours;
                    $ratio += $desired_object->ocp_per_day;
                }


            }
            $final_ratio = round($ratio / 5);

            $total_h = 0;

            if ($this->convertToHoursMins($total_hours) != null) {
                $total_h = $this->convertToHoursMins($total_hours);
            }

            $html .= "<td>$total_h</td><td>%$final_ratio</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";

        $building_name = DB::table('buildings')->select(['building'])->where('id', '=', $request->input('building_id'))->get()->first()->building;*/


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
            $path = url('/images/uni_logo.png');
            $img = '<img src="' . $path . '" width="64">';
            // Title
            $html = '
            <table>
                <tr>
                    <td width="33.33%"></td>
                    <td width="33.33%" align="center" rowspan="2">' . 1 . '</td>
                    <td width="33.33%"></td>
                </tr>
                <tr>
                    <td width="33.33%" align="center" >
                    وزارة التعليم العالي والبحث العلمي
                    <br>
                    وحدة المالية
                    </td>
                    <td width="33.33%;" align="center">
                    <b>Ministry of Higher Education and Scientific Research</b>
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
            $pdf->Cell(0, 0, 'جميع الحقوق محفوظة / وحدة تكنولوجيا المعلومات ©2019', 0, 1, 'C', 0, '', 0);;
        });

        PDF::AddPage();
        //PDF::SetFont('dejavusans', '', 9);
        PDF::SetFont('aealarabiya', '', 14);
        $year = AcademicYear::find($request->input('academic_year_id'));
        $html1 = '<table cellpadding="2" cellspacing="0"><tr><td width="100%" align="center"> م / تقرير الدفوعات المتوقعه والدفوعات المتحققة للعام الدراسي '.$year->end_year.'-'.$year->start_year.'</td></tr></table>';
        PDF::writeHTML($html1, true, false, true, false, '');

        PDF::Ln();
        PDF::SetFontSize(7);

        //PDF::writeHTML($html, true, false, true, false, '');
        PDF::Output('payments1.pdf');
    }
}
