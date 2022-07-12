<?php

namespace App\Http\Controllers;

use App\EmailRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = EmailRecipient::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', 'admin.emailButtons')
                ->rawColumns(['action'])
                ->only(['fullName', 'email', 'role', 'action', 'DT_RowIndex'])
                ->make(true);
        }

        return view('admin.settings.index');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "fullName" => "required|string",
            "email" => "required|email|unique:email_recipients",
            "role" => "required|string",
        ];

        $message = [
            'required' => 'يجب ادخال بيانات الحقل.',
            'string' => 'يجب ادخال نصوص فقط.',
            'email' => 'يجب أدخل بريد ألكتروني صحيح.',
            'unique' => 'البريد الالكتروني مستخدم مسبقاً.'
        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $emailRecipient = new EmailRecipient();
        $emailRecipient->fullName = $request->input('fullName');
        $emailRecipient->email = $request->input('email');
        $emailRecipient->role = $request->input('role');
        $emailRecipient->save();

        return back()->with('success', 'تم أضاقة مستلم بريد الكتروني بنجاح.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $email = EmailRecipient::find($id);
        return view('admin.settings.edit')
            ->with('emailRec',$email);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $emailRecipient = EmailRecipient::find($id);
        $rules = [
            "fullName" => "required|string",
            "email" => "required|email",
            "role" => "required|string",
        ];

        $message = [
            'required' => 'يجب ادخال بيانات الحقل.',
            'string' => 'يجب ادخال نصوص فقط.',
            'email' => 'يجب أدخل بريد ألكتروني صحيح.',
        ];

        $v = Validator::make($request->all(), $rules, $message);
        $v->validate();

        $emailRecipient->fullName = $request->input('fullName');
        $emailRecipient->email = $request->input('email');
        $emailRecipient->role = $request->input('role');
        $emailRecipient->update();

        return back()->with('success', 'تم تعديل معلومات مستلم البريد الكتروني بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emailRec = EmailRecipient::find($id);
        if ($emailRec !=null){
            $emailRec->delete();
        }
        return back();
    }
}
