<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

define('PAGINATION_COUNT', 10);

class UserController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:users-list|users-create|users-edit|users-delete', ['only' => ['index', 'create', 'edit']]);

        $this->middleware('permission:users-create', ['only' => ['store']]);

        $this->middleware('permission:users-edit', ['only' => ['update']]);

        $this->middleware('permission:users-delete', ['only' => ['delete']]);
    }



    public function index(Request $request)

    {
        try {
            $data = User::orderBy('id', 'DESC')->with(['roles', 'permissions'])->paginate(5);
            return view('users.index', compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {
        try {
            $roles = Role::pluck('name', 'name')->all();
            return view('users.create', compact('roles'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

    public function store(Request $request)

    {
        try {
            $this->validate($request, [

                'name' => 'required',

                'email' => 'required|email|unique:users,email',

                'password' => 'required|same:confirm-password',

                'roles' => 'required'

            ]);

            $input = $request->all();
            $input['company_id'] = Auth::user()->company_id;
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $user->assignRole($request->input('roles'));
            return redirect()->route('users.index')->with('success', trans('users.User created successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {
        try {
            $user = User::find($id);
            $roles = Role::pluck('name', 'name')->all();
            $userRole = $user->roles->pluck('name', 'name')->all();
            return view('users.edit', compact('user', 'roles', 'userRole'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
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
        try {
            $this->validate($request, [

                'name' => 'required',

                'email' => 'required|email|unique:users,email,' . $id,

                'password' => 'same:confirm-password',

                'roles' => 'required'

            ]);



            $input = $request->all();

            if (!empty($input['password'])) {

                $input['password'] = Hash::make($input['password']);
            } else {

                $input = Arr::except($input, array('password'));
            }
            $user = User::find($id);
            $input['company_id'] = Auth::user()->company_id;
            $user->update($input);
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
            return redirect()->route('users.index')

                ->with('success', trans('users.User updated successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy($id)

    {
        try {
            User::find($id)->delete();
            return redirect()->route('users.index')->with('success', trans('users.User deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
