<?php

namespace App\Http\Controllers;

use App\Models\Payment_method;
use App\Models\Invoice;
use App\Http\Requests\Payment_method_Request;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class Payment_methodControll extends Controller
{



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:payment_method-list|payment_method-create|payment_method-edit|payment_method-delete', ['only' => ['payment_methods']]);

        $this->middleware('permission:payment_method-create', ['only' => ['store_payment_methods']]);

        $this->middleware('permission:payment_method-edit', ['only' => ['update_payment_methods']]);

        $this->middleware('permission:payment_method-delete', ['only' => ['delete_payment_methods']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    //=====store_payment methods=====
    public function payment_methods()
    {
        try {
            $payment_methods = Payment_method::where('company_id', Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
            return view('pages.Payment_method', compact(['payment_methods']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //store
    public function store_payment_methods(Payment_method_Request $request)
    {
        try {
            $payment_method = Payment_method::create([
                'payment_method_name_ar' => $request->payment_method_name_ar,
                'payment_method_name_en' => $request->payment_method_name_en,
                'company_id' => Auth::user()->company_id,

            ]);
            $payment_method->save();
            return back()->with('success', trans('intelligent.Added successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //update
    public function update_payment_methods($id, Payment_method_Request $request)
    {
        try {
            $payment_method = Payment_method::find($id);

            if (!$payment_method)
                return back()->with('error', trans('intelligent.This item not found'));

            // update date


            Payment_method::where('id', $payment_method->id)
                ->update([
                    'payment_method_name_ar' => $request->payment_method_name_ar,
                    'payment_method_name_en' => $request->payment_method_name_en,
                ]);



            return back()->with('success', trans('intelligent.The item was updated successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }





    //delete
    public function delete_payment_methods($id)
    {
        try {
            $payment_method = Payment_method::find($id);

            if (!$payment_method)
                return back()->with('error', trans('intelligent.This item not found'));

            Invoice::where('payment_method_id', $payment_method->id)->selection()->delete();


            $payment_method->delete();

            return back()->with('success', trans('intelligent.Deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }



    //=====end user====================================================




}
