<?php

namespace App\Http\Controllers;

use App\Exports\JobRequestExport;
use App\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class JobTypeController extends Controller
{


    public function ajaxIndex(){}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.type.index')
            ->with('types',JobType::all()->where('hide', '<>', 1));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "title" => "required|string",
            "speciality" => "required|string",
            "number" => "required|integer",
            "certificate" => "required",
        ];

        $message = [
            'required' => 'يجب ادخال بيانات الحقل!',
            'certificate.required' => 'يجب أختيار شهادة واحدة على الاقل!',
            'string' => 'يجب ادخال نصوص فقط!',
            'integer' => 'يجب أدخال ارقام فقط!'
        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $JobType = new JobType();
        $JobType->title = $request->input('title');
        $JobType->speciality = $request->input('speciality');
        $JobType->number = $request->input('number');
        $certificates = $request->input('certificate');
        $string = "";
        if ($certificates != null){
            foreach ($certificates as $certificate){
                $string .= $certificate . " ";
            }
        }
        $JobType->certificate = $string;
        $JobType->save();

        return back()->with('success', 'تم أضاقة الدرجة الوظيفية بنجاح. يمكنك الان فتح استمارة جديدة.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $t = JobType::find($id);
        $t->hide = 1;
        $t->update();
        return back()->with('types',JobType::all()->where('hide', '<>', 1));
    }
}
