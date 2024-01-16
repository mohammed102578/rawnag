<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Purchase;
use App\Http\Requests\Supplier_Request;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class SuplliersController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:suppliers-list|suppliers-create|suppliers-edit|suppliers-delete', ['only' => ['suppliers']]);

        $this->middleware('permission:suppliers-create', ['only' => ['store_suppliers']]);

        $this->middleware('permission:suppliers-edit', ['only' => ['update_Suppliers']]);

        $this->middleware('permission:suppliers-delete', ['only' => ['delete_suppliers']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //=====user=====
    public function suppliers()
    {
        try {
            $suppliers = Supplier::where('company_id', Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);

            return view('pages.suppliers', compact(['suppliers']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //store
    public function store_suppliers(Supplier_Request $request)
    {
        try {

            $Supplier = Supplier::create([

                'supplier_name' => $request->supplier_name,
                'email' => $request->email,
                'address' => $request->address,
                'supplier_id' => $request->supplier,
                'contact_number' => $request->contact_number,
                'company_id' => Auth::user()->company_id,

            ]);
            $Supplier->save();

            return back()->with('success', trans('intelligent.Added successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //update
    public function update_Suppliers($supplier_id, Supplier_Request $request)
    {

        try {
            $supplier = Supplier::find($supplier_id);

            if (!$supplier)
                return back()->with('error', trans('intelligent.This item not found'));

            // update date
            Supplier::where('id', $supplier_id)
                ->update([
                    'supplier_name' => $request->supplier_name,
                    'email' => $request->email,
                    'contact_number' => $request->contact_number,
                    'address' => $request->address,

                ]);
            return back()->with('success', trans('intelligent.The item was updated successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }


    //delete
    public function delete_suppliers($id)
    {
        try {
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return back()->with('error', trans('intelligent.This item not found'));
            }

            Purchase::where('supplier_id', $supplier->id)->select()->delete();

            $supplier->delete();

            return back()->with('success', trans('intelligent.Deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
    //=====end user====================================================

}
