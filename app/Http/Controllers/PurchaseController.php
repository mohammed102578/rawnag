<?php

namespace App\Http\Controllers;


use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Http\Requests\Purchase_Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

define('PAGINATION_COUNT', 10);
class PurchaseController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-delete', ['only' => ['purchases']]);

        $this->middleware('permission:purchase-create', ['only' => ['store_purchases']]);

        $this->middleware('permission:purchase-edit', ['only' => ['update_purchases']]);

        $this->middleware('permission:purchase-delete', ['only' => ['delete_purchases']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    //=====purrchase=====
    public function purchases()
    {
        try {
            $suppliers = Supplier::where('company_id', Auth::user()->company_id)->selection()->get();
            $purchases = Purchase::where('company_id', Auth::user()->company_id)->with('medicine.packing')->with('supplier')->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
            $medicines = Medicine::where('company_id', Auth::user()->company_id)->selection()->get();
            $total = Purchase::where('company_id', Auth::user()->company_id)->sum('price');

            return view('pages.purchases', compact(['suppliers', 'total', 'purchases', 'medicines']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //store
    public function store_purchases(Purchase_Request $request)
    {

        try {
            if (isset($request->supplier)) {
                for ($i = 0; $i < count($request->medicine); $i++) {

                    Purchase::create([
                        'medicine_id' => $request->medicine[$i],
                        'exp_date' => $request->exp_date[$i],
                        'quantity' => $request->quantity[$i],
                        'supplier_id' => $request->supplier,
                        'purchase_date' => $request->purchase_date,
                        'batch' => $request->batch[$i],
                        'price' => $request->price[$i],
                        'invoice_number' => $request->invoice_number,
                        'company_id' => Auth::user()->company_id,
                    ]);
                }

                return back()->with('success', trans('intelligent.Added successfully'));
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //update
    public function update_purchases($id, Purchase_Request $request)
    {


        try {

            if (!empty($request->supplier)) {
                $purchases = Purchase::find($id);

                if (!$purchases)
                    return back()->with('error', trans('intelligent.This item not found'));

                // update date
                Purchase::where('id', $purchases->id)
                    ->update([
                        'medicine_id' => $request->medicine,
                        'exp_date' => $request->exp_date,
                        'quantity' => $request->quantity,
                        'batch' => $request->batch,
                        'purchase_date' => $request->purchase_date,
                        'supplier_id' => $request->supplier,
                        'price' => $request->price,
                        'invoice_number' => $request->invoice_number,
                        'company_id' => Auth::user()->company_id,
                    ]);


                return back()->with('success', trans('intelligent.The item was updated successfully'));
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }





    //delete
    public function delete_purchases($id)
    {
        try {
            $purchases = Purchase::find($id);
            if (!$purchases) {
                return back()->with('failed', trans('intelligent.This item not found'));
            }
            $purchases->delete();

            return back()->with('success', trans('intelligent.Deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }


    public function invoice_purchases($invoice_no)
    {
        try {
            $suppliers = Supplier::where('company_id', Auth::user()->company_id)->selection()->get();
            $medicines = Medicine::where('company_id', Auth::user()->company_id)->selection()->get();
            $total = Purchase::where('company_id', Auth::user()->company_id)->sum('price');
            $purchase = DB::table('purchases')->where('invoice_number', $invoice_no)->first();
            $supplier_id = $purchase->supplier_id;
            $supplier = DB::table('suppliers')->where('id', $supplier_id)->first();
            $purchases = Purchase::where('invoice_number', $invoice_no)->with('medicine.packing')->with('supplier')->get();
            if (!$purchases) {
                if (auth()->user()->lang !== 'en') {

                    return back()->with('error', trans('intelligent.This item not found'));
                } else {
                    return back()->with('error', 'This Item does not exist.');
                }
            } else {
                return view('pages.invoice_purchases', compact(['supplier', 'purchase', 'suppliers', 'total', 'purchases', 'medicines']));
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
    //=====end _purchases====================================================

}
