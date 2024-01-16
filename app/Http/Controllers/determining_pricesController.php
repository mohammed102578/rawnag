<?php

namespace App\Http\Controllers;


use App\Models\Determining_price;
use App\Models\Medicine;
use App\Http\Requests\Determining_price_Request;

use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class determining_pricesController extends Controller
{



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:determining_price-list', ['only' => ['determining_prices']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //home page







    //=====determining_prices=====
    public function determining_prices()
    {
        try {
            $determining_prices = Determining_price::where('company_id', Auth::user()->company_id)->with('medicine.packing')->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
            $medicines = Medicine::where('company_id', Auth::user()->company_id)->selection()->get();
            return view('pages.determining_prices', compact(['determining_prices', 'medicines']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //store
    public function store_determining_prices(Determining_price_Request $request)
    {


        try {
            Determining_price::create([
                'medicine_id' => $request->medicine,
                'price' => $request->price,
                'company_id' => Auth::user()->company_id
            ]);
            return back()->with('success', trans('intelligent.Added successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //update
    public function update_determining_prices($id, Determining_price_Request $request)
    {

        try {
            $Determining_price = Determining_price::find($id);

            if (!$Determining_price)
                return back()->with('error', trans('intelligent.This item not found'));

            // update date

            Determining_price::where('id', $Determining_price->id)
                ->update([
                    'medicine_id' => $request->medicine,
                    'price' => $request->price,
                ]);
            return back()->with('success', trans('intelligent.The item was updated successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }





    //delete
    public function delete_determining_prices($id)
    {
        try {
            $Determining_price = Determining_price::find($id);
            if (!$Determining_price) {
                return back()->with('error', trans('intelligent.This item not found'));
            }
            $Determining_price->delete();

            return back()->with('success', trans('intelligent.Deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
