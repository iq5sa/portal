<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ادارة الصلاحيات');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\request()->ajax()) {
            $permissions = Permission::all();
            return Datatables::of($permissions)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    $html = '';
                    if ($row->roles->isNotEmpty()){
                        foreach ($row->roles as $role){
                            $html .= '<span class="badge badge-success mr-1">' . $role->name . '</span>';
                        }
                    }
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return '
                    <div class="btn-group btn-group-sm">
                                            <a href="' . route("permissions.edit", $row->id) . '" class="btn btn-warning"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="' . route("permissions.destroy", $row->id) . '" class="btn btn-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                    ';
                })
                ->rawColumns(['actions', 'role'])
                ->make(true);
        }
        $roles = Role::all();
        return view('admin.permissions.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get(); //Get all roles

        return view('permissions.create')->with('roles', $roles);
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
            'name' => 'required|string|max:40|unique:permissions',
            'roles' => 'array',
            'guard_name' => 'required',
        ];

        $messages = [
            'required' => 'يجب ان لايكون الحقل فارغاً',
            'max:40' => "يجب ان لاتتجاوز عدد الاحرف 40 حرفا",
            'unique' => 'العنوان مكرر يرجى اختيار عنوان اخر'
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        $v->validate();

        $name = $request->input('name');
        $permission = new Permission();
        $permission->name = $name;
        $permission->guard_name = $request->input('guard_name');
        $permission->save();

        $roles = $request['roles'];
        if (!empty($request['roles'])) { //If one or more role is selected
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record
                $permission = Permission::where('id', '=', $permission->id)->first(); //Match input //permission to db record
                $r->givePermissionTo($permission);
            }
        }

        return response()->json(['message' => 'تم انشاء الحساب بنجاح'], '200');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $roles = Role::all();
        return view('admin.permissions.edit', compact('permission','roles'));
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
            'name' => 'required|string|max:50|unique:permissions,name,' . $id,
            'roles' => 'array',
            'guard_name' => 'required',
        ];

        $messages = [
            'required' => 'يجب ان لايكون الحقل فارغاً',
            'max:40' => "يجب ان لاتتجاوز عدد الاحرف 40 حرفا",
            'unique' => 'العنوان مكرر يرجى اختيار عنوان اخر'
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        $v->validate();

        $permission = Permission::findOrFail($id);
        $permission->name = $request->input('name');
        $permission->guard_name = $request->input('guard_name');
        $permission->update();
        DB::table('role_has_permissions')->where('permission_id', $id)->delete();

        if ($request->has('roles')) {
            foreach ($request->input('roles') as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record
                $r->givePermissionTo($permission);
            }
        }

        return response()->json(['message' => 'تم تعديل معلومات صلاحية المستخدمين بنجاح'], '200');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        //Make it impossible to delete this specific permission
        if ($permission->name == "users-create") {
            return redirect()->route('permissions.index')
                ->with('flash_message',
                    'لا يمكنك حذف هذه الصلاحية'
                )->with('class','alert-warning');
        }

        DB::table('role_has_permissions')->where('permission_id', $id)->delete();


        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('flash_message',
                'تم حذف الصلاحية بنجاح')
            ->with('class','alert-success');

    }
}
