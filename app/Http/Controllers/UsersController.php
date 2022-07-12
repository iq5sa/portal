<?php

namespace App\Http\Controllers;

use App\AccountingRoles;
use App\College;
use App\Rules\Number;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:أدارة المستخدمين');
    }

    public function index()
    {


        /*$role = Role::create(['name' => 'موظف تسجيل']);
        $permission = Permission::create(['name' => 'create students']);
        $role->givePermissionTo($permission);*/


        $roles = Role::all();
        $colleges = College::all();
        if (\request()->ajax()) {

            $users = User::orderBy('id', 'DESC')->with('roles')->where('hide', '=', 0);
            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    $role_name = '';
                    if ($row->roles->first() != null){
                        $role_name = $row->roles->first()->name;
                    }
                    return '<span class="badge badge-success">' . $role_name . '</span>';
                })
                ->addColumn('active_account', function ($row) {
                    if ($row->is_active == 1) {
                        return '<a href="' . route("users.setactive", $row->id) . '"
                                               class="btn btn-success btn-sm">مفعل</a>';
                    } else if ($row->is_active == 0) {
                        return '<a href="' . route("users.setactive", $row->id) . '"
                                               class="btn btn-secondary btn-sm">معطل</a>';
                    }
                })
                ->addColumn('actions', function ($row) {
                    return '
                    <div class="btn-group btn-group-sm">
                                            <a href="' . route("users.edit", $row->id) . '" class="btn btn-warning"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="' . route("users.destroy", $row->id) . '" class="btn btn-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                ';
                })
                ->rawColumns(['actions', 'role', 'active_account'])
                ->make(true);
        }
        return view('admin.users.index', compact('roles', 'colleges'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {



        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => ['required', new Number()],
            'assigned_departments' => 'required|array',

        ];


        $messages = [
            'required' => 'يجب ان لايكون الحقل فارغاً',
            'email' => 'البريد الالكتروني غير صالح',
            'min' => 'ادخل 8 رموز على الاقل',
            'unique' => 'البريد الالكتروني مستخدم مسبقاً',
            'max:255' => "يجب ان لاتتجاوز عدد الاحرف 255 حرفا",
            'confirmed' => "كلمة المرور غير متطابقة",
            'assigned_departments.required' => 'يجب تحديد قسم واحد على الاقل'
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        $v->validate();

        //


        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->is_active = 1;
        $user->hide = 0;
        $user->save();

        $user->assignRole($request->input('role_id'));
        if ($request->has('assigned_departments')) {
            $user->departments()->attach($request->input('assigned_departments'));
        }
        return response()->json(['message' => 'تم انشاء الحساب بنجاح'], '200');

    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $colleges = College::all();
        if ($user->departments->isNotEmpty()) {
            $user_departments_ids = $user->departments->pluck('id')->all();
        } else {
            $user_departments_ids = [];
        }
        $user_role = $user->roles->pluck('id')->first();
        return view('admin.users.edit', compact('user', 'roles', 'user_role', 'colleges', 'user_departments_ids'));
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
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role_id' => ['required', new Number()],
            'assigned_departments' => 'required|array',

        ];

        $messages = [
            'required' => 'يجب ان لايكون الحقل فارغاً',
            'email' => 'البريد الالكتروني غير صالح',
            'min' => 'ادخل 8 رموز على الاقل',
            'unique' => 'البريد الالكتروني مستخدم مسبقاً',
            'max:255' => "يجب ان لاتتجاوز عدد الاحرف 255 حرفا",
            'confirmed' => "كلمة المرور غير متطابقة",
            'assigned_departments.required' => 'يجب تحديد قسم واحد على الاقل'
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        $v->validate();
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->is_active = 1;
        $user->hide = 0;
        $user->update();

        if ($request->has('assigned_departments')) {
            $user->departments()->detach();
            $user->departments()->attach($request->input('assigned_departments'));
        }

        if ($request->has('role_id')) {
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('role_id'));
        }

        return response()->json(['message' => 'تم تعديل معلومات الحساب بنجاح'], '200');
    }

    public function reset_password(Request $request, $id)
    {

        $rules = [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
        $messages = [
            'required' => 'يجب ان لايكون الحقل فارغاً',
            'min' => 'ادخل 8 رموز على الاقل',
            'confirmed' => "كلمة المرور غير متطابقة",
        ];

        $v = Validator::make($request->all(), $rules,$messages);
        $v->validate();

        $user = User::find($id);
        $user->password = bcrypt($request->input('password'));
        $user->update();

        return redirect()->back()
            ->with('reset_message', 'تم أعادة تعيين كلمة المرور بنجاح');
    }

    public function destroy($id)
    {
        $ob = User::find($id);
        $ob->hide = 1;
        $ob->is_active = 0;
        $ob->update();
        return back();


    }

    public function setactive($id)
    {
        if ($id == null) {
            return back();
        }

        $adm = User::find($id);

        if ($adm == null) {
            return back();
        }

        if ($adm->is_active == 0) {
            $adm->is_active = 1;
        } else {
            $adm->is_active = 0;
        }
        $adm->update();
        return back();
    }
}
