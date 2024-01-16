<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class ShiftsController extends Controller
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

    //=====SUPPLIER=====
    public function shifts()
    {
        try {
            $shifts = Shift::where('company_id', Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
            return view('pages.shifts', compact(['shifts']));
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }




    //store
    public function store_shifts(Request $request)
    {
        try {


            //  return $request->all();


            //handel start time
            $start = date("g:i A", strtotime($request->start_time));
            if (\str_contains($start, "PM")) {
                $start_time = date("H:i ", strtotime($request->start_time));
            } else {
                $start_time = date("h:i ", strtotime($request->start_time));


                if (\str_contains($start, "12")) {
                    $start_time = date("H:i ", strtotime($request->start_time));
                }
            }


            //handel end time
            $end = date("g:i A", strtotime($request->end_time));
            if (\str_contains($end, "PM")) {



                $end_time = date("H:i ", strtotime($request->end_time));
            } else {

                $end_time = date("h:i ", strtotime($request->end_time));


                if (\str_contains($end, "12")) {
                    $end_time = date("H:i ", strtotime($request->end_time));
                }
            }



            $Shifts = Shift::create([

                'shift_name' => $request->shift_name,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'company_id' => Auth::user()->company_id,

            ]);
            $Shifts->save();

            if (auth()->user()->lang !== 'en') {

                return back()->with('success', trans('intelligent.Added successfully'));
            } else {
                return back()->with('success', 'Added successfully');
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    //update
    public function update_shifts($shift_id, Request $request)
    {

        try {
            $shift = Shift::find($shift_id);

            if (!$shift)
                if (auth()->user()->lang !== 'en') {

                    return back()->with('error', trans('intelligent.This item not found'));
                } else {
                    return back()->with('error', 'This Item does not exist.');
                }
            // update date

            //handel start time
            $start = date("g:i A", strtotime($request->start_time));
            if (\str_contains($start, "PM")) {
                $start_time = date("H:i ", strtotime($request->start_time));
            } else {
                $start_time = date("h:i ", strtotime($request->start_time));


                if (\str_contains($start, "12")) {
                    $start_time = date("H:i ", strtotime($request->start_time));
                }
            }


            //handel end time
            $end = date("g:i A", strtotime($request->end_time));
            if (\str_contains($end, "PM")) {



                $end_time = date("H:i ", strtotime($request->end_time));
            } else {

                $end_time = date("h:i ", strtotime($request->end_time));


                if (\str_contains($end, "12")) {
                    $end_time = date("H:i ", strtotime($request->end_time));
                }
            }


            Shift::where('id', $shift_id)
                ->update([

                    'shift_name' => $request->shift_name,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'company_id' => Auth::user()->company_id,

                ]);



            if (auth()->user()->lang !== 'en') {

                return back()->with('success', trans('intelligent.The item was updated successfully'));
            } else {
                return back()->with('success', 'Updated successfully');
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }





    //delete
    public function delete_shifts($id)
    {
        try {
            $shift = Shift::find($id);
            if (!$shift) {
                if (auth()->user()->lang !== 'en') {

                    return back()->with('error', trans('intelligent.This item not found'));
                } else {
                    return back()->with('error', 'This Item does not exist.');
                }
            }


            $shift->delete();

            if (auth()->user()->lang !== 'en') {

                return back()->with('success', trans('intelligent.Deleted successfully'));
            } else {
                return back()->with('success', 'Deleted successfully');
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }



    //=====end user====================================================

}
