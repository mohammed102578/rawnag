<?php

namespace App\Http\Controllers;


use App\Models\Log;
use App\Models\Purchase;
use App\Models\Determining_price;
use App\Models\Medicine;
use App\Models\Invoice;
use App\Models\Packing;

use App\Models\Supplier;

use App\Http\Requests\Medicine_Request;
use App\Models\Generic_Name;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class MedicinesController extends Controller
{




    /**
     * Create a new controller instance.
     *
     * @return void
     */


    function __construct()

    {
        $this->middleware('auth');

        $this->middleware('permission:medicine-list|medicine-create|medicine-edit|medicine-delete', ['only' => ['medicines']]);

        $this->middleware('permission:medicine-create', ['only' => ['store_medicines']]);

        $this->middleware('permission:medicine-edit', ['only' => ['update_medicines']]);

        $this->middleware('permission:medicine-delete', ['only' => ['delete_medicines']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //home page




    //=====user=====
    public function medicines()
    {
        try {
            $medicines = Medicine::where('company_id', Auth::user()->company_id)->with('packing')->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
            $packings = Packing::where('company_id', Auth::user()->company_id)->selection()->get();
            $generic_names = Generic_Name::all();
            return view('pages.medicines', compact(['medicines', 'packings', 'generic_names']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //expier_store_medicines

    public function expier_store_medicines()
    {

        try {
            $medicines = Medicine::where('company_id', Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
            $packings = Packing::where('company_id', Auth::user()->company_id)->selection()->get();
            $stores = Purchase::where('company_id', Auth::user()->company_id)->with('medicine.packing')->with('supplier')->orderBy('id', 'DESC')->selection()->get();
            return view('pages.expier_store_medicines', compact(['stores', 'medicines', 'packings']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //expier_date_medicines

    public function expier_date_medicines()
    {
        try {
            $expier_date_tody = date('y-m-d', strtotime('+90 day', time()));
            $medicines = Medicine::where('company_id', Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
            $packings = Packing::where('company_id', Auth::user()->company_id)->selection()->get();
            $purchase_exp_date = Purchase::where('company_id', Auth::user()->company_id)->with('medicine.packing')->with('supplier')->where('exp_date', "<", "$expier_date_tody")->get();


            return view('pages.expier_date_medicines', compact(['purchase_exp_date', 'medicines', 'packings']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //store
    public function store_medicines(Medicine_Request $request)
    {

        try {



            Medicine::create([
                'medicine_name' => $request->medicine_name,
                'generic_name' => $request->generic_name,
                'packing_id' => $request->packing,
                'barcode' => $request->barcode,
                'company_id' => Auth::user()->company_id,


            ]);
            return back()->with('success', trans('intelligent.Added successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //update
    public function update_medicines($id, Medicine_Request $request)
    {


        try {
            $medicine = Medicine::find($id);

            if (!$medicine)
            return back()->with('error', trans('intelligent.This item not found'));

            // update date


            Medicine::where('id', $id)
                ->update([
                    'medicine_name' => $request->medicine_name,
                    'generic_name' => $request->generic_name,
                    'packing_id' => $request->packing,
                    'barcode' => $request->barcode,

                ]);



            return back()->with('success', trans('intelligent.The item was updated successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }





    //delete
    public function delete_medicines($id)
    {

        try {
            $medicine = Medicine::find($id);

            if (!$medicine)
                return back()->with('error', trans('intelligent.This item not found'));

            $medicine_id = $medicine->id;
            Determining_price::where('medicine_id', $medicine_id)->selection()->delete();
            Purchase::where('medicine_id', $medicine_id)->selection()->delete();
            Log::where('medicine_id', $medicine_id)->selection()->delete();
            Invoice::where('medicine_id', $medicine_id)->selection()->delete();

            $medicine->delete();

            return back()->with('success', trans('intelligent.Deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }



    //=====end user====================================================


}
