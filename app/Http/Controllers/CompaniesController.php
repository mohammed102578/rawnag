<?php

namespace App\Http\Controllers;


use App\Models\Company;
use App\Http\Requests\Companies_Request;

define('PAGINATION_COUNT', 10);
class CompaniesController extends Controller
{



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    //=====user=====
    public function companies()
    {
        try {
            return view('auth.company');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //store
    public function store_companies(Companies_Request $request)
    {

        try {

            $company = Company::create([
                'company_name' => $request->company_name,
                'address' => $request->address,
                'vat_number' => $request->vat_number,

            ]);
            $company->save();


            // User::where('id',Auth::user()->id)->update(['company_id' => $last_row]);


            return redirect()->route('register');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }


    //=====end companies====================================================

}
