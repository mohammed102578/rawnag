<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Purchase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */





    //=====log=====

    //store
    public function store_logs(Request $request)
    {
         $request->all();
     //check if quantity entered in log no greater than quantity in stock
        $logs= Log::where('purchase_id',$request->purchase_id)->select('quantity')->get();
        $quantity_in_log= $logs->sum('quantity')+$request->quantity;

        $store = Purchase::where('id',$request->purchase_id)->orderBy('id', 'DESC')->with('invoice')->with('medicine.packing')->with('supplier')->first();

        $quantity_in_store=$store->quantity - $store->invoice->sum('quantity');



        if($quantity_in_log > $quantity_in_store){
            return back()->with('error', trans('cashier.This quantity is out of stock'));

        }

        try {

            if ($request->quantity > $request->store_quantity) {
                return back()->with('error', 'log_side.The quantity entered for one of the medicines is not available');
            } else {
                $purchases = Purchase::where('id', "$request->purchase_id")->selection()->get();

                //return $request->all();
                $log =  Log::create([
                    'purchase_id' => $request->purchase_id,
                    'quantity' => $request->quantity,
                    'price' => $request->price * $request->quantity,
                    'user_id' => Auth::user()->id,
                    'company_id' => Auth::user()->company_id,

                ]);
                $log->save();


                if (App::getLocale()  !== 'en') {

                    return back()->with('success', trans('intelligent.Added successfully'));
                } else {
                    return back()->with('success', 'Added successfully');
                }
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }






    //delete
    public function delete_logs($id)
    {
        try {
            $log = Log::find($id);
            if (!$log) {
                return back()->with('error', 'هذا السجل غير موجود');
            }
            $log->delete();

            return back()->with('success', trans('intelligent.Deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
    public function delete_all_logs()
    {
        try {
            Log::select()->delete();

            return back()->with('success', trans('intelligent.Deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }


    //=====end log====================================================


}
