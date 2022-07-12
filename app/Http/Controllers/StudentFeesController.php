<?php

namespace App\Http\Controllers;

use App\AcademicStatus;
use App\AcademicYear;
use App\College;
use App\Discount;
use App\Fee;
use App\Payment;
use App\primarySchoolGraduationYear;
use App\Rules\Number;
use App\Shift;
use App\StudentClass;
use App\StudentSearch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StudentFeesController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:عرض جميع الدفوعات', ['only' => ['index']]);
        $this->middleware('permission:عرض ايراد طالب معين', ['only' => ['show']]);
        $this->middleware('permission:اضافة مبلغ قسط', ['only' => ['add_fees']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $academics = AcademicYear::orderBy('start_year', "DESC")->get();
        $colleges = College::all();
        $shifts = Shift::all();
        $types = AcademicStatus::all();
        $grad_years = primarySchoolGraduationYear::orderBy('start_year', "DESC")->get();
        // $fees=Fee::all();
        // // student_id
        // $Payment=Payment::first();
        // // fees_id
        // // student_id
        // foreach ($fees as $key => $fee) {
        //   $Payments=Payment::where('fees_id',$fee->id)->get();
        //   if ($Payments) {
        //     foreach ($Payments as $key => $Payment) {
        //       $payment = Payment::find($Payment->id);
        //       $payment->student_id = $fee->student_id;
        //       $payment->update();
        //     }
        //
        //   }
        //
        // }

        return view('admin.finance.index', compact('grad_years', 'colleges', 'academics', 'shifts', 'types'));

    }


    public function show_students_info(Request $request)
    {


        if ($request->ajax()) {
            $data = StudentSearch::applyfee($request);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('fullname', function ($row) {
                    $name = "";
                    if ($row->first_name == null) {
                        $name = $row->full_name . " " . $row->surname;
                    } else {
                        $name = $row->first_name . ' ' . $row->father_name . ' ' . $row->middle_name . ' ' . $row->last_name . ' ' . $row->surname;
                    }
                    $url = route('students.show', $row->st_uni_id);
                    return "<a href='$url' target='_blank'>$name</a>";
                })->addColumn('student_gender', function ($row) {
                    return $row->gender == 0 ? 'ذكر' : "انثى";

                })->addColumn('college_department', function ($row) {
                    $college_dpt = $row->college_name;
                    if ($row->department_name != null || $row->department_name != "") {
                        $college_dpt .= '/' . $row->department_name;
                    }
                    return $college_dpt;
                })->addColumn('image', function ($row) {
                    if ($row->photo != null) {
                        $url = asset('storage/' . $row->photo);
                        return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                    } else {
                        if ($row->gender == 0) {
                            $url = asset('images/student.svg');
                            return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                        } elseif ($row->gender == 1) {
                            $url = asset('images/woman.svg');
                            return "<div class='text-center'><img src=" . $url . " height='40' width='40' class='rounded-circle' align='center' /></div>";
                        }
                    }
                })
                ->addColumn('edited_status', function ($row) {
                    if ($row->has_hold_subject == 1) {
                        return $row->academic_status_name . ' <span class="badge badge-warning">عبور من العام الماضي</span>';
                    }

                    if ($row->has_fail == 1) {
                        return $row->academic_status_name . ' <span class="badge badge-danger">راسب من العام الماضي</span>';
                    }

                    return $row->academic_status_name;


                })
                ->addColumn('collect_fees_btn', 'admin.finance.add_payments_button')
                ->rawColumns(['image', 'fullname', 'edited_status', 'collect_fees_btn'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $std = StudentSearch::student_record($id)->get()->first();
        if ($std == null) {
            abort(404);
        }
        $columns = [
            'fees.id as fees_id',
            'fees.fee_name',
            'start_year',
            'end_year',
            'level',
            'required_amount',
            'discount',
        ];

        $columns1 = [
            'fees.id as fees_id',
            'required_amount',
            'discount',
            'programing_id',
            'payment_id',
            'payment_date',
            'payment_amount',
            'payment_method',
            'description',
            'cheque_number',
            'cheque_date',
            'revert',
            'name'
        ];
        $payments = DB::table('payments')->select([
            'payments.id as programing_id',
            'fees_id',
            'payment_id',
            'payment_date',
            'payment_amount',
            'payment_method',
            'payments.description',
            'cheque_number',
            'cheque_date',
            'revert',
            'users.name'
        ])->join('users', 'users.id', '=', 'payments.user_id')
            ->where('student_id', '=', $id);

        $fees_records = DB::table('fees')->select($columns1)
            ->leftJoinSub($payments, 'pays', 'pays.fees_id', '=', 'fees.id')
            ->where('fees.student_id', '=', $id)
            ->orderBy('fees.level_id', 'desc')
            ->get();


        $fees_arr = [];
        $fees = [];


        // payment
        foreach ($fees_records as $record) {
            $fees_arr[$record->fees_id][] = $record;

        }


        foreach ($fees_arr as $key => $val) {
            $fees_record = DB::table('fees')->select($columns)
                ->join('academic_years', 'academic_years.id', '=', 'fees.academic_year_id')
                ->join('levels', 'levels.id', 'fees.level_id')
                ->where('fees.id', '=', $key)
                ->get()->first();

            $discounts = DB::table('fees')->select(['fees.required_amount', 'discount_name', 'discount_amount', 'discounts.discount_type'
                , 'discount_description', 'discounts.id as disid'])
                ->join('fees_discounts', 'fees_discounts.fees_id', '=', 'fees.id')
                ->join('discounts', 'discounts.id', '=', 'fees_discounts.discount_id')
                ->where('fees.id', '=', $key)
                ->get();

            //  dd($discounts);
            $total_discount = 0;

            foreach ($discounts as $discount) {
                if ($discount->discount_type == 1) {
                    $total_discount += ($discount->required_amount * $discount->discount_amount) / 100;
                } else if ($discount->discount_type == 2) {
                    $total_discount += $discount->discount_amount;

                }
            }


            $total_paid = 0;
            foreach ($val as $v) {
                if ($v->revert == 0) {
                    $total_paid += $v->payment_amount;
                }
            }

            $total_due = $fees_record->required_amount - $total_paid;
            $total_due = $total_due - $total_discount;
            $ar = array('fee_name' => $fees_record->fee_name, 'fees_id' => $fees_record->fees_id, 'academic_year' => $fees_record->start_year . '-' . $fees_record->end_year,
                'level' => $fees_record->level, 'required_amount' => $fees_record->required_amount, 'discount' => $fees_record->discount,
                'total_paid' => $total_paid,
                'total_due' => $total_due,
                'all_discount' => $total_discount,
                'payments' => $val,
                'discount_details' => $discounts->toArray()
            );


            array_push($fees, $ar);
        }

        $payment_count = Payment::all()->count();
        if ($payment_count > 0) {
            $last_payment_number = Payment::orderBy('payment_id', 'ASC')->get()->last()->payment_id + 1;
        } else {
            $last_payment_number = 0001;
        }

        $academics = AcademicYear::orderBy('start_year')->get();
        $student_id = $id;

        $discountNames = Discount::all()->unique("discount_name");


        return view('admin.finance.show', compact('student_id', 'academics', 'std', 'fees', 'last_payment_number', "discountNames"));
    }

    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('discounts')->where('id', $id)->delete();
        DB::table('fees_discounts')->where('discount_id', $id)->delete();
        return back();
    }

    public function add_fees(Request $request)
    {


        $academic_year_id = $request->input("academic_year_id");
        $student_id = $request->input("student_id");
        $amount = $request->input("amount");

        $class_info = DB::table('statuses')->select(['student_classes.class_id', 'student_classes.college_id',
            'student_classes.department_id', 'student_classes.level_id', 'student_classes.level_id', 'student_classes.shift_id'])
            ->join('student_classes', 'student_classes.class_id', '=', 'statuses.student_class_id')
            ->where('statuses.student_id', '=', $student_id)
            ->where('student_classes.academic_year_id', '=', $academic_year_id)->first();





        $fee = new Fee();
        $fee->academic_year_id = $academic_year_id;
        $fee->college_id = $class_info->college_id;
        $fee->department_id = $class_info->department_id;
        $fee->class_id = $class_info->class_id;
        $fee->level_id = $class_info->level_id;
        $fee->shift_id = $class_info->shift_id;
        $fee->student_id = $student_id;
        $fee->required_amount = $amount;
        $fee->fee_name = 'القسط السنوي';
        $fee->fee_type = 0;
        $fee->save();
        return response()->json(['message' => 'تم اضافة مبلغ القسط']);

    }

    public function edit_fee(Request $request)
    {
        $fee = Fee::find($request->input('fee_id'));
        $fee->required_amount = $request->input('amount');
        $fee->save();
        return back();
    }

    public function add_dis(Request $request)
    {
        $iddis = DB::table('discounts')->insertGetId(
            [
                'discount_name' => $request->input('discount_name'),
                'discount_amount' => $request->input('discount_amount'),
                'discount_description' => $request->input('discount_description'),
                'discount_type' => 2
            ]);
        $id = DB::table('fees_discounts')->insertGetId(['discount_id' => $iddis, 'fees_id' => $request->input('fees_id')]);
        return back();
    }

    public function add_id_card_fees(Request $request)
    {

        $rules = [
            'academic_year_id' => ['required',
                function ($attribute, $value, $fail) {
                    $class_info = DB::table('statuses')->select(['student_classes.class_id', 'student_classes.college_id',
                        'student_classes.department_id', 'student_classes.level_id'])
                        ->join('student_classes', 'student_classes.class_id', '=', 'statuses.student_class_id')
                        ->where('statuses.student_id', '=', \request()->input('student_id'))
                        ->where('student_classes.academic_year_id', '=', \request()->input('academic_year_id'))->first();
                    if ($class_info == null) {
                        $fail('الطالب غير مسجل في العام الدراسي الذي قمت بأختياره يرجى مراجعة وحدة التسجيل');
                    }
                }],
            'amount' => ['required', new Number()],
            'student_id' => 'required',
        ];


        $messages = [
            'required' => 'لايمكن ان يكون الحقل فارغ!',
        ];


        Validator::make($request->all(), $rules, $messages)->validate();
        // get the class info
        $class_info = DB::table('statuses')->select(['student_classes.class_id', 'student_classes.college_id',
            'student_classes.department_id', 'student_classes.level_id'])
            ->join('student_classes', 'student_classes.class_id', '=', 'statuses.student_class_id')
            ->where('statuses.student_id', '=', $request->input('student_id'))
            ->where('student_classes.academic_year_id', '=', $request->input('academic_year_id'))->first();

        $fee = new Fee();
        $fee->academic_year_id = $request->input('academic_year_id');
        $fee->college_id = $class_info->college_id;
        $fee->department_id = $class_info->department_id;
        $fee->class_id = $class_info->class_id;
        $fee->level_id = $class_info->level_id;
        //  $fee->shift_id = $class_info->shift_id;
        $fee->student_id = $request->input('student_id');
        $fee->required_amount = $request->input('amount');
        $fee->fee_name = 'مبلغ الهوية';
        $fee->fee_type = 1;
        $fee->save();


        $this->storePayment($request);
        return response()->json(['message' => $request]);

    }


    public function storePayment(Request $request)
    {


        if ($request->input('payment_amount') == "10000") {
            $rules = [
                'payment_id' => ['required', new Number()],
                'payment_date' => 'required|date',
                'payment_amount' => ['required', new Number(),

                ],
                'payment_method' => 'required|in:1,2,3',
                'student_id' => 'required'
            ];
            $messages = [
                'required' => 'لايمكن ان يكون الحقل فارغا',
                'in' => 'يجب تحديد احد الاختيارات المتاحة فقط.',
                'date' => 'التأريخ غير صالح',
                'unique' => 'رقم الوصل مكرر'
            ];
        } else {
            $rules = [
                'payment_id' => ['unique:payments', 'required', new Number()],
                'payment_date' => 'required|date',
                'payment_amount' => ['required', new Number(),

                ],
                'payment_method' => 'required|in:1,2,3',
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


        $fees = Fee::all()->last();


        $payment = new Payment();
        $payment->payment_id = $request->input('payment_id');
        $payment->payment_date = $request->input('payment_date');
        $payment->payment_amount = $request->input('payment_amount');
        $payment->payment_method = $request->input('payment_method');
        $payment->fees_id = $fees->id;
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
//        return response()->json(array('last_payment_number' => $last_payment_number), 200);

    }
}
