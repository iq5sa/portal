<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:ادارة دور المستخدم');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\request()->ajax()) {
            $roles = Role::all();
            return Datatables::of($roles)
                ->addIndexColumn()
                ->addColumn('permissions', function ($row) {
                    $html = '';
                    if ($row->permissions->isNotEmpty()) {
                        foreach ($row->permissions as $permission) {
                            $html .= '<span class="badge badge-success mr-1">' . $permission->name . '</span>';
                        }
                    }
                    return $html;
                })
                ->addColumn('actions', function ($row) {
                    return '
                    <div class="btn-group btn-group-sm">
                                            <a href="' . route("roles.edit", $row->id) . '" class="btn btn-warning"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="' . route("roles.destroy", $row->id) . '" class="btn btn-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                    ';
                })
                ->rawColumns(['actions', 'permissions'])
                ->make(true);
        }
        $permissions = Permission::all();
        return view('admin.roles.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();//Get all permissions

        return view('roles.create', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate name and permissions field
        $rules = [
            'name' => 'required|string|max:40|unique:roles',
            'permissions' => 'required',
            'guard_name' => 'required',
        ];

        $messages = [
            'required' => 'يجب ان لايكون الحقل فارغاً',
            'permissions.required' => "يجب أختيار الصلاحيات (واحدة على الاقل)",
            'unique' => 'العنوان مكرر يرجى اختيار عنوان اخر',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        $v->validate();

        $name = $request->input('name');
        $role = new Role();
        $role->name = $name;
        $role->guard_name = $request->input('guard_name');
        $role->save();


        $permissions = $request->input('permissions');
        //Looping thru selected permissions
        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            //Fetch the newly created role and assign permission
            $role = Role::where('id', '=', $role->id)->first();
            $role->givePermissionTo($p);
        }

        return response()->json(['message' => 'تم انشاء دور مستخدم بنجاح'], '200');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('admin.roles.edit', compact('role', 'permissions'));
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

        $role = Role::findOrFail($id);//Get role with the given id
        //Validate name and permission fields
        $rules = [
            'name' => 'required|max:50|unique:roles,name,' . $id,
            'permissions' => 'required',
            'guard_name' => 'required',
        ];
        $messages = [
            'required' => 'يجب ان لايكون الحقل فارغاً',
            'permissions.required' => "يجب أختيار الصلاحيات (واحدة على الاقل)",
            'unique' => 'العنوان مكرر يرجى اختيار عنوان اخر',
        ];

        $v = Validator::make($request->all(), $rules, $messages);
        $v->validate();

        $input = $request->except(['permissions']);
        $role->fill($input)->save();

        $permissions = $request['permissions'];
        $p_all = Permission::all();//Get all permissions

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
            $role->givePermissionTo($p);  //Assign permission to role
        }

        return response()->json(['message' => 'تم تعديل دور مستخدم بنجاح'], '200');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $p_all = Permission::all();//Get all permissions
        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }
        $role->delete();

        return redirect()->route('roles.index')
            ->with('flash_message',
                'تم حذف الدور بنجاح')
            ->with('class','alert-success');

    }
}
