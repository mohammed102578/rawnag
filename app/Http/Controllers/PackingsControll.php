<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Packing;
use App\Http\Requests\packing_Request;


use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class PackingsControll extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:unit_sale-list|unit_sale-create|unit_sale-edit|unit_sale-delete', ['only' => ['packings']]);

        $this->middleware('permission:unit_sale-create', ['only' => ['store_packings']]);

        $this->middleware('permission:unit_sale-edit', ['only' => ['update_packings']]);

        $this->middleware('permission:unit_sale-delete', ['only' => ['delete_packings']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    //=====user=====
    public function packings()
    {
        try {
            $packings = Packing::where('company_id', Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
            return view('pages.packings', compact(['packings']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //store
    public function store_packings(packing_Request $request)
    {
        try {
            $packing = Packing::create([
                'packing_name' => $request->name,
                'company_id' => Auth::user()->company_id,
            ]);
            $packing->save();
            return back()->with('success', trans('intelligent.Added successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //update
    public function update_packings($packing_id, packing_Request $request)
    {

        try {
            $packing = Packing::find($packing_id);

            if (!$packing)
                return back()->with('error', trans('intelligent.This item not found'));

            // update date


            Packing::where('id', $packing_id)
                ->update([
                    'packing_name' => $request->name,
                    'company_id' => Auth::user()->company_id,

                ]);



            return back()->with('success', trans('intelligent.The item was updated successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }





    //delete
    public function delete_packings($id)
    {
        $packing = Packing::find($id);
        try {

            if (!$packing)
                return back()->with('error', trans('intelligent.This item not found'));
            Medicine::where('packing_id', $packing->id)->selection()->delete();
            $packing->delete();

            return back()->with('success', trans('intelligent.Deleted successfully'));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }



    //=====end packing====================================================




}
