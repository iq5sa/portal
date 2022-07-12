<?php

namespace App\Http\Controllers;

use App\JobCategory;
use App\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class JobCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.category.index')
            ->with('categories', JobCategory::all());
    }

    public function anyData()
    {
        try {
            return Datatables::of(JobCategory::query())->make(true);
        } catch (\Exception $e) {

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create')
            ->with('types', JobType::all()->where('hide', '=', 0));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "title" => "required|string",
            "start_date" =>
                [
                    'required',
                    'string',
                    'date',
                    function ($attribute, $value, $fail) {
                        $today = Carbon::today();
                        $start_date = Carbon::createFromFormat('Y-m-d', $value);
                        $end_date = Carbon::createFromFormat('Y-m-d', \request('end_date'));

                        if ($start_date->lessThan($today)) {
                            $fail('تأريخ الاعلان عن الاستمارة قديم يرجى أختيار تأريخ حديث.');
                        } elseif ($start_date->greaterThan($end_date)) {
                            $fail('تأريخ الاعلان عن الاستمارة أكبر من تأريخ غلق الاستمارة.');
                        }
                    },
                ],
            "end_date" =>
                [
                    'required',
                    'string',
                    'date',
                    function ($attribute, $value, $fail) {
                        $today = Carbon::today();
                        $start_date = Carbon::createFromFormat('Y-m-d', $value);
                        $end_date = Carbon::createFromFormat('Y-m-d', \request('end_date'));

                        if ($start_date->lessThan($today)) {
                            $fail('تأريخ غلق الاستمارة قديم يرجى أختيار تأريخ حديث.');
                        } elseif ($end_date->lessThan($start_date)) {
                            $fail('تأريخ غلق الاستمارة أقل من تأريخ الاعلان عن الاستمارة يرجى أختيار تأريخ صحيح.');
                        }
                    },
                ],
            'types' =>
                [
                    'required',
                    function ($attribute, $value, $fail) {
                        if ($value == null) {
                            $fail('يجب اختيار الدرجات الوظيفية عند أنشاء الاستمارة.');
                        }
                    },
                ]
        ];

        $message = [
            'required' => 'يجب ادخال بيانات الحقل.',
            'types.required' => 'يجب اختيار الرجات الوظيفية عند أنشاء الاستمارة.',
            'string' => 'يجب ادخال نصوص فقط.',
            'date' => 'لقد قمت بأدخال تأريخ غير صحيح يرجى أعادة المحاولة.'
        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $category = new JobCategory();
        $category->title = $request->input('title');
        $category->start_date = $request->input('start_date');
        $category->end_date = $request->input('end_date');
        $active = $request->input('active');
        if ($active == null) {
            $category->active = 0;
        } else if (is_array($active)) {
            $category->active = $active[0];
        }

        $category->save();

        $types = $request->input('types');
        if ($types != null) {
            $category->types()->attach($types);
        }

        return back()->with('success', 'تم أضاقة استمارة جديدة بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JobCategory $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function show(JobCategory $jobCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobCategory $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCategory $jobCategory)
    {
        return view('admin.category.edit')
            ->with('types',JobType::all()->where('hide','=',0))
            ->with('category', $jobCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\JobCategory $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobCategory $jobCategory)
    {
        $rules = [
            "title" => "required|string",
            "start_date" =>
                [
                    'required',
                    'string',
                    'date',
                    function ($attribute, $value, $fail) {
                        $start_date = Carbon::createFromFormat('Y-m-d', $value);
                        $end_date = Carbon::createFromFormat('Y-m-d', \request('end_date'));

                        if ($start_date->greaterThan($end_date)) {
                            $fail('تأريخ الاعلان عن الاستمارة أكبر من تأريخ غلق الاستمارة.');
                        }
                    },
                ],
            "end_date" =>
                [
                    'required',
                    'string',
                    'date',
                    function ($attribute, $value, $fail) {
                        $today = Carbon::today();
                        $start_date = Carbon::createFromFormat('Y-m-d', $value);
                        $end_date = Carbon::createFromFormat('Y-m-d', \request('end_date'));

                        if ($start_date->lessThan($today)) {
                            $fail('تأريخ غلق الاستمارة قديم يرجى أختيار تأريخ حديث.');
                        } elseif ($end_date->lessThan($start_date)) {
                            $fail('تأريخ غلق الاستمارة أقل من تأريخ الاعلان عن الاستمارة يرجى أختيار تأريخ صحيح.');
                        }
                    },
                ],
            'types' =>
                [
                    'required',
                    function ($attribute, $value, $fail) {
                        if ($value == null) {
                            $fail('يجب اختيار الدرجات الوظيفية عند أنشاء الاستمارة.');
                        }
                    },
                ]
        ];

        $message = [
            'required' => 'يجب ادخال بيانات الحقل.',
            'types.required' => 'يجب اختيار الرجات الوظيفية عند أنشاء الاستمارة.',
            'string' => 'يجب ادخال نصوص فقط.',
            'date' => 'لقد قمت بأدخال تأريخ غير صحيح يرجى أعادة المحاولة.'
        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();


        $jobCategory->title = $request->input('title');
        $jobCategory->start_date = $request->input('start_date');
        $jobCategory->end_date = $request->input('end_date');
        $jobCategory->update();

        $types = $request->input('types');
        if ($types != null) {
            $jobCategory->types()->detach();
            $jobCategory->types()->attach($types);
        }

        return back()->with('success', 'تم تعديل معلومات الاستمارة بنجاح');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobCategory $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobCategory $jobCategory)
    {
        //
    }

    public function setAsActive(Request $request)
    {
        DB::table('job_categories')->where('active', '=', 1)->update(array('active' => 0));
        DB::table('job_categories')->where('id', '=', $request->input('id'))->update(array('active' => 1));

        return back()->with('update', true);
    }
}
