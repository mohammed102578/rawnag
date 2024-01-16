<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

define('PAGINATION_COUNT', 10);
class ReportController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:reports', ['only' => ['reports']]);
        $this->middleware('permission:sales_reports', ['only' => ['reports_sales']]);
        $this->middleware('permission:purchase_report', ['only' => ['reports_purchas']]);
        $this->middleware('permission:medicines_report', ['only' => ['reports_percent_medicine']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    //home page



    public function reports()
    {
        try {
            return view('pages.reports');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }

    public function reports_sales(Request $request)
    {
        try {
            $shifts = Shift::where('company_id', Auth::user()->company_id)->selection()->get();

            if ($request->all()) {


                if (isset($request->shift)) {
                    $shift = Shift::where('company_id', Auth::user()->company_id)->where('id', $request->shift)->selection()->first();

                    $start_time = $shift->start_time;
                    $end_time = $shift->end_time;



                    $bills = Invoice::where('company_id', Auth::user()->company_id)->with('payment')->whereBetween('created_at', [$request->from, $request->to])->whereBetween('time', [$start_time, $end_time])->orderBy('created_at', 'DESC')
                        ->select(
                            'invoice_no',
                            'created_at',
                            'payment_method_id',
                            'time',
                            DB::raw("count(purchase_id) as count"),
                            DB::raw("sum(price) as total")
                        )
                        ->groupBy('invoice_no', 'created_at', 'payment_method_id', 'time')->get();

                    $total = $bills->sum('total');
                } else {


                    $bills = Invoice::where('company_id', Auth::user()->company_id)->with('payment')->whereBetween('created_at', [$request->from, $request->to])->orderBy('created_at', 'DESC')
                        ->select(
                            'invoice_no',
                            'created_at',
                            'payment_method_id',
                            DB::raw("count(purchase_id) as count"),
                            DB::raw("sum(price) as total")
                        )->groupBy('invoice_no', 'created_at', 'payment_method_id')->get();

                    $total = $bills->sum('total');
                }

                return view('pages.sales_report', compact(['shifts', 'bills', 'total']));
            }

            $total = 0;

            return view('pages.sales_report', compact(['shifts', 'total']));


            try {
            } catch (\Exception $ex) {
                if (auth()->user()->lang !== 'en') {

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');
                } else {
                    return back()->with('error', 'Something went wrong');
                }
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }







    //=================================repore_purchase
    public function reports_purchas(Request $request)
    {
        try {
            if ($request->all()) {

                $purchases = Purchase::where('company_id', Auth::user()->company_id)->whereBetween('purchase_date', [$request->from, $request->to])->with('medicine.packing')->with('supplier')->orderBy('id', 'DESC')->selection()->get();


                $medicines = Medicine::where('company_id', Auth::user()->company_id)->selection()->get();
                $total = Purchase::where('company_id', Auth::user()->company_id)->sum('price');
                return view('pages.purchases_report', compact(['total', 'purchases', 'medicines']));
            }

            return view('pages.purchases_report');
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }





    public function reports_percent_medicine(Request $request)
    {

        try {
            $date_tody = date('Y-m-d');



            $medicines = Medicine::where('company_id', Auth::user()->company_id)->select()->get();


            $show_style = $request->show_style;

            if (!empty($request->show_style)) {


                //work pace


                $medicines = Medicine::where('company_id', Auth::user()->company_id)->select()->get();

                $purchase_id = Purchase::where('company_id', Auth::user()->company_id)->where('medicine_id', $request->medicine_id)->pluck('id');
                $medicine_graph = Invoice::whereIn('purchase_id', $purchase_id)->where('company_id', Auth::user()->company_id)->orderBy('created_at', 'ASC')
                    ->select(DB::raw("sum(quantity) as quantity"), DB::raw('MONTH(created_at) month'))
                    ->groupBy('month')->get()->toArray();


                $month = array_column($medicine_graph, 'month');




                $medicine_month = array();
                $medicine_month_en = array();

                foreach ($month as $medicine_monthes) {

                    if ($medicine_monthes == 1) {

                        $medicine_month_en[] = "january";
                        $medicine_month[] = "يناير";
                    }
                    if ($medicine_monthes == 2) {
                        $medicine_month[] = "فبراير";
                        $medicine_month_en[] = "February";
                    }
                    if ($medicine_monthes == 3) {
                        $medicine_month_en[] = "March ";

                        $medicine_month[] = "مارس";
                    }
                    if ($medicine_monthes == 4) {
                        $medicine_month[] = "ابريل";
                        $medicine_month_en[] = "April ";
                    }
                    if ($medicine_monthes == 5) {
                        $medicine_month_en[] = "May";
                        $medicine_month[] = "مايو";
                    }
                    if ($medicine_monthes == 6) {
                        $medicine_month_en[] = "June";

                        $medicine_month[] = "يونيو";
                    }
                    if ($medicine_monthes == 7) {
                        $medicine_month[] = "يوليو";
                        $medicine_month_en[] = "July ";
                    }
                    if ($medicine_monthes == 8) {
                        $medicine_month_en[] = "August";

                        $medicine_month[] = "اغسطس";
                    }
                    if ($medicine_monthes == 9) {
                        $medicine_month_en[] = "September";

                        $medicine_month[] = "سبتمبر";
                    }
                    if ($medicine_monthes == 10) {
                        $medicine_month[] = "اكتوبر";
                        $medicine_month_en[] = "October";
                    }
                    if ($medicine_monthes == 11) {
                        $medicine_month_en[] = "November ";

                        $medicine_month[] = "نوفمبر";
                    }
                    if ($medicine_monthes == 12) {
                        $medicine_month_en[] = "December ";

                        $medicine_month[] = "ديسمبر";
                    }
                }

                $medicine_month;

                $medicine_graph = array_column($medicine_graph, 'quantity');
                $pie_data = array();
                $json_data = array();
                for ($i = 0; $i < count($medicine_graph); $i++) {



                    $json_data[] = json_encode("{name : '$medicine_month_en[$i]' , y : $medicine_graph[$i]}");
                }


                $js = json_encode($json_data);
                $jso = str_replace('"\"', "", $js);

                $pie_data = str_replace('\""', "", $jso);


                $medicine_graphs = json_encode($medicine_graph, JSON_NUMERIC_CHECK);

                $medicine_months = json_encode($medicine_month_en, JSON_NUMERIC_CHECK);


                $month = json_encode($month);



                //workspace
                return view('pages.reports_percent_medicine', compact(['medicines', 'pie_data', 'month', 'medicine_months', 'show_style', 'medicine_graphs']));
            }

            return view('pages.reports_percent_medicine', compact(['medicines']));

            try {
            } catch (\Exception $ex) {
                if (auth()->user()->lang !== 'en') {

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');
                } else {
                    return back()->with('error', 'Something went wrong');
                }
            }
        } catch (\Exception $ex) {
            return back()->with('error',  trans('intelligent.some thing went wrong please try again'));
        }
    }
}
