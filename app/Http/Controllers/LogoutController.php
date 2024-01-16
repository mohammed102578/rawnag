<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT', 10);
class LogoutController extends Controller
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




    public function logout_expierd(Request $request)
    {
        try {
            Auth::logout();
            return redirect('/login')->with('error', 'عذرا لقد تم انتهاء الفترة التجريبية');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }


    public function logout_stop(Request $request)
    {
        try {
            Auth::logout();
            return redirect('/login')->with('error', 'عذرا لقد تم توقيفك من قبل الادارة ');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            return redirect('/login');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
