<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Purchase;
use App\Http\Requests\Store_Request;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class StoreController extends Controller
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



    public function stores()
    {
        try {
            $stores = Purchase::where('company_id', Auth::user()->company_id)->orderBy('id', 'DESC')->with('invoice')->with('medicine.packing')->with('supplier')->selection()->paginate(PAGINATION_COUNT);
            $medicines = Medicine::where('company_id', Auth::user()->company_id)->selection()->get();
            return view('pages.stores', compact(['stores', 'medicines']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }


    public function update_stores($id, Store_Request $request)
    {

        try {

            $stores = Purchase::find($id);

            if (!$stores)
                return back()->with('error', trans('intelligent.This item not found'));

            // update date

            if (!$request->has('stop'))
                $request->request->add(['stop' => 0]);
            else
                $request->request->add(['stop' => 1]);




            Purchase::where('id', $stores->id)
                ->update([
                    'medicine_id' => $request->medicine_id,
                    'stop' => $request->stop,

                ]);


            return back()->with('success', trans('intelligent.The item was updated successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
