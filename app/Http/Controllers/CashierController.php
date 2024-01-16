<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class CashierController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:cashier', ['only' => ['cashiers']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function cashiers(Request $request)
    {
        try {
            if ($request->all()) {
                $date = date('Y-m-d');
                if (!empty($request->medicine_name)) {
                    $medicine = Medicine::where('company_id', Auth::user()->company_id)->where('medicine_name', 'LIKE', "%$request->medicine_name%")->first();
                    if (!empty($medicine)) {
                        $purchases = Purchase::where('company_id', Auth::user()->company_id)->where('medicine_id', $medicine->id)->with('invoice')->with('medicine.determining_price')->with('medicine.packing')->orderBy('id', 'DESC')->get();
                        // return  $purchases->medicine->determining_price->price;
                        if ($purchases->isEmpty()) {

                            return back()->with('error', trans('cashier.This drug was not found in any batch'));
                        }
                    } else {

                        return back()->with('error', trans('cashier.this medicine was not found in medicine list'));
                    }


                   $cashier=[];
                    foreach ($purchases as $purchase) {


                        if ($purchase->stop == 0) {
                            if ($purchase->exp_date > $date) {
                                if ($purchase->quantity - $purchase->invoice->sum('quantity') > 0) {
                            $cashier[]=['id'=>$purchase->id,'medicine_name'=>$purchase->medicine->medicine_name,'store'=>$purchase->quantity - $purchase->invoice->sum('quantity') ,
                        'batch'=> $purchase->batch,'quantity'=> $purchase->quantity,'expired'=>$purchase->exp_date,'price'=> $purchase->medicine->determining_price->price,'generic_name'=>
                        $purchase->medicine->generic_name,'packing'=>$purchase->medicine->packing->packing_name];
                                }
                            }
                        }
                    }




                    $purchases=$cashier;


                    return view('pages.cashiers', compact(['purchases', 'date']));
                } elseif (!empty($request->generic_name)) {

                    $medicine = Medicine::where('company_id', Auth::user()->company_id)->where('generic_name', 'LIKE', "%$request->generic_name%")->first();
                    if (!empty($medicine)) {
                        $purchases = Purchase::where('company_id', Auth::user()->company_id)->where('medicine_id', $medicine->id)->with('invoice')->with('medicine.determining_price')->with('medicine.packing')->orderBy('id', 'DESC')->get();
                        if (empty($purchases)) {

                            return back()->with('error', trans('cashier.This drug was not found in any batch'));
                        }
                    } else {

                        return back()->with('error', trans('cashier.this medicine was not found in medicine list'));
                    }
                    return view('pages.cashiers', compact(['purchases', 'date']));
                } elseif (!empty($request->barcode)) {


                    $medicine = Medicine::where('company_id', Auth::user()->company_id)->where('barcode', 'LIKE', "%$request->barcode%")->first();
                    if (!empty($medicine)) {
                        $purchases = Purchase::where('company_id', Auth::user()->company_id)->where('medicine_id', $medicine->id)->with('invoice')->with('medicine.determining_price')->with('medicine.packing')->orderBy('id', 'DESC')->get();
                        if (empty($purchases)) {

                            return back()->with('error', trans('cashier.This drug was not found in any batch'));
                        }
                    } else {

                        return back()->with('error', trans('cashier.this medicine was not found in medicine list'));
                    }
                    return view('pages.cashiers', compact(['purchases', 'date']));
                }
            }
            return view('pages.cashiers');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
