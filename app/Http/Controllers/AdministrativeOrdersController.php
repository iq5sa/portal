<?php

namespace App\Http\Controllers;

use App\AcademicStatus;
use App\AcademicYear;
use App\AdministrativeOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdministrativeOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:أدارة الاوامر الادارية');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$orders = AdministrativeOrder::orderBy('id','DESC')->get();
        $orders = DB::table('administrative_orders')->select(['*','administrative_orders.id as admin_id','administrative_orders.is_active as o_is_active'])
            ->join('academic_statuses','academic_statuses.id','=','administrative_orders.academic_status_id')
            ->join('academic_years','academic_years.id','=','administrative_orders.academic_years_id')
            ->orderBy('administrative_orders.id','DESC')
            ->get();


        $academics = AcademicYear::all();
        $academic_statuses = AcademicStatus::all();
        return view('admin.adminorders.index',compact('academics','orders','academic_statuses'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'number' => "required|string|unique:administrative_orders",
            'date' => 'required|date',
            'file' => 'file|mimes:png,jpg,jpeg',
            'description' => "nullable|string",
            'academic_years_id' => "required|integer",
            'academic_status_id' => "required|integer",
        ];

        $v = Validator::make($request->all(),$rules);
        $v->validate();
        $order = new AdministrativeOrder();
        $order->number = $request->input('number');
        $order->date = $request->input('date');
        $order->description = $request->input('description');
        $order->academic_years_id = $request->input('academic_years_id');
        $order->academic_status_id = $request->input('academic_status_id');



        if ($request->has('file')){
            $path = Storage::disk('public')->put('administrative_orders_documents', $request->file('file'));
            $order->path = $path;

        }
        $order->save();
        return back();
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
        $academics = AcademicYear::all();
        $order = AdministrativeOrder::find($id);
        return view('admin.adminorders.edit',compact('academics','order'));
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
        $rules = [
            'number' => "required|string",
            'date' => 'required|date',
            'file' => 'nullable|file|mimes:png,jpg,jpeg',
            'description' => "nullable|string",
            'academic_years_id' => "required|integer",
        ];

        $v = Validator::make($request->all(),$rules);
        $v->validate();

        $order = AdministrativeOrder::find($id);
        $order->number = $request->input('number');
        $order->date = $request->input('date');
        $order->description = $request->input('description');
        $order->academic_years_id = $request->input('academic_years_id');
        $order->update();
        if ($request->has('file')){
            $path = Storage::disk('public')->put('administrative_orders_documents', $request->file('file'));
            $order->path = $path;
            $order->update();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ob = AdministrativeOrder::find($id);
        $ob->delete();
        return back();
    }

    public function setactive($id)
    {
        //Validator::make($request->all(),['hidden_id' => 'required'])->validate();
        if ($id == null){
            return back();
        }
        $adm = AdministrativeOrder::find($id);
        if ($adm == null){
            return back();
        }
        if ($adm->is_active == 0){
            $adm->is_active = 1;
        }else{
            $adm->is_active = 0;
        }
        $adm->update();
        return back();
    }

}
