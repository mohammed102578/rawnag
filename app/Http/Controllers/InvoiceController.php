<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Purchase;
use App\Models\Determining_price;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
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



    public function invoice_number()
    {
        return $invoice_no = date('Y-m') . "-" . Auth::user()->id . "-" . rand(1000, 9999);
    }



    //=====tickt=====
    public function tickt($invoice_no)
    {
        try {
            $invoices =   Invoice::where('invoice_no', "$invoice_no")->selection()->get();
            $sum_price = Invoice::where('invoice_no', $invoice_no)->sum('price');
            return view('pages.tickt', compact(['invoices', 'sum_price']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }




    //a4 function
    public function A4($invoice_no)
    {
        try {
            $invoices =   Invoice::where('invoice_no', "$invoice_no")->selection()->get();
            $sum_price = Invoice::where('invoice_no', $invoice_no)->sum('price');
            return view('pages.A4', compact(['invoices', 'sum_price']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }




    //==================================================================store
    public function store_invoices(Request $request)
    {

        try {
            date_default_timezone_set("Africa/Cairo");

            if ($request->time) {

                $time_request = date("g:i A", strtotime($time = $request->time));
                if (\str_contains($time_request, "PM")) {
                    $time = date("H:i ", strtotime($request->time));
                } else {
                    $time = date("h:i ", strtotime($request->time));

                    if (\str_contains($request->time, "12")) {
                        $time = date("H:i ", strtotime($request->time));
                    }
                }
            } else {
                $time_request = date("g:i: A");



                if (\str_contains($time_request, "PM")) {
                    $time = date("H:i ");
                } else {
                    $time = date("h:i ");

                    if (\str_contains($time_request, "12")) {
                        $time = date("H:i ");
                    }
                }
            }


            $invoice_no = $this->invoice_number();

            // return $request->all();

            if (isset($request->print)) {

                if (isset($request->quantity)) {
                    DB::beginTransaction();
                    $invoice_create = array();
                    $invoice = array();
                    $purchase = array();
                    $price = array();
                    $quantity = array();
                    $determining_prices = array();
                    for ($i = 0; $i < count($request->quantity); $i++) {

                        //return quantity for this item by id $request->purchase_id[$i]
                        if (isset($request->created_at)) {
                            $purchase[$i] = Purchase::where('id', $request->purchase_id[$i])->where('medicine_id', $request->medicine_id[$i])->select()->get();
                        }


                        //return from log
                        if (isset($request->price[$i])) {

                            $price[] = $request->price[$i];
                        } else {

                            //check if this medicine found in any batch

                            if (isset($purchase[$i][0])) {


                                //return from sale
                                $determining_prices[$i] = Determining_price::where('medicine_id', $request->medicine_id[$i])->select('price')->get();
                                if (isset($determining_prices[$i][0]->price)) {
                                    $price[] = $determining_prices[$i][0]->price * $request->quantity[$i];


                                    //if found in any batch



                                } else {

                                    return back()->with('error', 'cashier.The selling price has not been determined');
                                }
                            } else {


                                return back()->with('error', 'cashier.This drug was not found in any batch');
                            }
                        }

                        //return total quantity of invoice for same $request->purchase_id[$i]
                        $invoices = Invoice::where('purchase_id', $request->purchase_id[$i])->sum('quantity');

                        if (isset($invoices)) {
                            $invoice[$i] = $invoices;
                        } else {
                            $invoice[$i] = 0;
                        }
                    }

                    //return $invoice[0];
                    for ($i = 0; $i < count($request->quantity); $i++) {
                        // $purchase[$i][0]->quantity - $invoice[0];
                        if (isset($invoice[0])) {
                            $count_quantity = $invoice[0];
                        } else {
                            $count_quantity = 0;
                        }


                        //check if quantity in stock greater than request acording to created at
                        if (isset($request->created_at)) {
                            $quantity[] = $purchase[$i][0]->quantity - $count_quantity >=  $request->quantity[$i];
                        } else {
                            $quantity[] = $request->quantity[$i];
                        }


                        if ($quantity[$i]) {

                            if ($request->created_at) {
                                $date = $request->created_at . date(" G:i:s");
                            } else {
                                $date = date('y-m-d H:i:s');
                            }
                            $invoice_create = [
                                'purchase_id' => $request->purchase_id[$i]['purchase_id'],
                                'quantity' => $request->quantity[$i]['quantity'],
                                'price' => $request->price[$i]['price'],
                                'user_id' => Auth::user()->id,
                                'company_id' => Auth::user()->company_id,
                                'payment_method_id' => $request->payment_method_id,
                                'created_at' => $date,
                                'time' => $time,
                                'invoice_no' => $invoice_no,

                            ];
                        } else {
                            return back()->with('error', 'log_side.The quantity entered for one of the medicines is not available');
                        }
                        //end of the check if quantity of the item -quantity


                        DB::table('invoices')->insert($invoice_create);
                    }

                    DB::commit();
                    $log = Log::where('user_id', Auth::user()->id);

                    $log->delete();
                    if ($request->print == 1) {
                        return redirect()->route('tickt', $invoice_no);
                    } else {

                        return redirect()->route('A4', $invoice_no);
                    }
                }


                return back()->with('error', 'log_side.You have not added any medication');
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
