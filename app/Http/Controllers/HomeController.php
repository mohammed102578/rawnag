<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

define('PAGINATION_COUNT', 10);
class HomeController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:dashboard', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //home page
    public function index()
    {
        try {
            $expiered_date_medicine = date('y-m-d', strtotime('+90 day', time()));
            $date_tody = date('Y-m-d');
            $invoice = Invoice::where('company_id', Auth::user()->company_id)->where('created_at', 'LIKE', "%$date_tody%")->select()->count();
            $purchase = Purchase::where('company_id', Auth::user()->company_id)->where('created_at', 'LIKE', "%$date_tody%")->select()->count();
            $bills = Invoice::where('company_id', Auth::user()->company_id)->orderBy('created_at', 'DESC')
                ->select(
                    'invoice_no',
                    'created_at',
                    'payment_method_id',
                    DB::raw("count(purchase_id) as count"),
                    DB::raw("sum(price) as total")
                )
                ->groupBy('invoice_no', 'created_at', 'payment_method_id')->get()->count();
            $suppliers = Supplier::where('company_id', Auth::user()->company_id)->select()->count();
            $medicines = Medicine::where('company_id', Auth::user()->company_id)->select()->count();
            $purchase_exp_date = Purchase::where('company_id', Auth::user()->company_id)->where('exp_date', "<", "$expiered_date_medicine")->get()->count();
            $stores = Purchase::where('company_id', Auth::user()->company_id)->with('invoice')->orderBy('id', 'DESC')->selection()->get();


            return view('pages.home', compact(['stores', 'invoice', 'purchase', 'bills', 'suppliers', 'medicines', 'purchase_exp_date']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }




    public function tables()
    {
        try {

            return view('pages.tables');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    public function reports()
    {
        try {
            return view('pages.reports');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
