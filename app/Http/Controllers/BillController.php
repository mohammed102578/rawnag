<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

define('PAGINATION_COUNT', 10);
class BillController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:sales', ['only' => ['bills']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //=====log=====
    public function bills(Request $request)
    {
        try {
            $bills = Invoice::where('company_id', Auth::user()->company_id)->with('payment')->orderBy('created_at', 'DESC')
                ->select(
                    'invoice_no',
                    'created_at',
                    'payment_method_id',
                    DB::raw("count(purchase_id) as count"),
                    DB::raw("sum(price) as total")
                )
                ->groupBy('invoice_no', 'created_at', 'payment_method_id')->paginate(PAGINATION_COUNT);
            $total = $bills->sum('total');
            return view('pages.invoices', compact(['bills', 'total']))->with('i', ($request->input('page', 1) - 1) * 5);
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }


    public function details_bills($id)
    {
        try {
            $bills = Invoice::where('invoice_no', $id)->with('purchase.medicine.determining_price')->selection()->get();
            $total = $bills->sum('price');
            return view('pages.invoice_details', compact(['bills', 'total']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
