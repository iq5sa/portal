<?php

namespace App\Http\Controllers;

use App\AcademicYear;
use App\AdministrativeOrder;
use App\College;
use App\Department;
use App\Discount;
use App\Rules\Number;
use App\Shift;
use App\StudentSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:تخفيض الاقساط');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::all();
        $edit = false;
        return view('admin.finance.discount.index', compact('discounts', 'edit'));
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
        $rules = [
            'discount_name' => 'required|unique:discounts',
            'discount_type' => 'required|in:1,2',
            'discount_amount' => [
                'required',
                new Number(),
                function ($attribute, $value, $fail) {
                    if (\request('discount_type') == 1 & ($value > 100 || $value <= 0)) {
                        $fail('يجب ادخال نسبة مئوية صحيحة 1-100');
                    }
                    if ($value <= 0) {
                        $fail('يجب ادخال قيمة اعلى من 0');
                    }
                }
            ],
            'discount_description' => 'nullable|string|max:100',
        ];

        $messages = [
            'عنوان التخفيض مستخدم مسبقا',
            'required' => 'لايمكن ان يكون الحقل فارغا',
            'in' => 'يجب تحديد احد الاختيارات المتاحة فقط.',
            'date' => 'التأريخ غير صالح',
            'discount_description.max' => 'يجب ان لايتجاوز عدد الاحرف 100 حرف'
        ];
        Validator::make($request->all(), $rules, $messages)->validate();

        $discount = Discount::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {



        if (\request()->ajax()) {
            $rules = [
                'academic_y_id' => 'required'
            ];

            $messages = [
                'academic_y_id.required' => 'يجب اختيار السنة الدراسية لتحديد التخفيض'
            ];

            Validator::make(\request()->all(), $rules, $messages)->validate();

            $data = StudentSearch::applyfee(\request(), null, null, null, null, true);
            $data->join('fees', 'fees.student_id', '=', 'students.student_id')
                ->where('fees.academic_year_id', '=', \request('academic_y_id'))
                ->where('fees.fee_type', '=', 0);


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
                })->addColumn('academic_year', function ($row) {
                    return '';
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
                ->rawColumns(['image', 'fullname', 'edited_status'])
                ->make(true);

        }


        $colleges = College::all();
        $departments = Department::all();
        $stages = collect([1, 2, 3, 4, 5]);
        $shifts = Shift::all();
        $academics = AcademicYear::all();
        $orders = AdministrativeOrder::where('is_active', '=', 1)->get();
        return view('admin.finance.discount.assign', compact('discount', 'colleges', 'departments', 'stages', 'shifts', 'academics', 'orders'));
    }

    public function show_students(Request $request)
    {
        $rules = [
            'academic_year_id' => 'required'
        ];

        $messages = [
            'academic_year_id.required' => 'يجب اختيار السنة الدراسية لتحديد التخفيض'
        ];

        Validator::make($request->all(), $rules, $messages)->validate();


    }

    public function assign(Request $request)
    {
        $rules = [
            'fees_id' => 'required|array',
            'discount_id' => ['required',new Number()],
            'administrative_order_id' => 'required'
        ];

        $messages = [
            'fees_id.required' => 'يجب اختيار قيد طالب واحد على الاقل',
            'discount_id.required' => 'يجب اختيار نوع التخفيض',
            'administrative_order_id.required' => 'يجب اختيار الامر الاداري للتخفيض',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();



        foreach ($request->input('fees_id') as $fees_id){

         $find_duplicates=   DB::table('fees_discounts')->where('discount_id','=',$request->input('discount_id'))
                ->where('fees_id','=',$fees_id)->count();
            if($find_duplicates==0) {
                DB::table('fees_discounts')->insert(
                    [
                        'discount_id' => $request->input('discount_id'),
                        'fees_id' => $fees_id,
                        'administrative_order_id' => $request->input('administrative_order_id')
                    ]
                );
            }
            else{
                return response()->json(['duplicate_message'=>'الطالب لديه نفس التخفيض مبسقا و بنص الامر الاداري'],200);

            }

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        $discounts = Discount::all();
        $edit = true;
        return view('admin.finance.discount.index', compact('discounts', 'discount', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Discount $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        //
    }


    public function showDiscountById($id){


        $discounts = DB::table("discounts")->join("fees_discounts","discounts.id","=","fees_discounts.discount_id")
            ->where("discounts.id","=",$id);
        return response()->json($discounts->get());

    }


    public function editDiscount(Request $request){

       $discounts = Discount::find($request->discount_id);
       $discounts->discount_name = $request->discount_name;
       $discounts->discount_amount = $request->discount_amount;
       $discounts->discount_description = $request->discount_description;
       $discounts->save();

       return redirect()->back()->with("status","successful update discount");


    }
}
