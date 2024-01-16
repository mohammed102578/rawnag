<?php

namespace App\Providers;

use App\Models\Log;
use App\Models\Payment_method;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('includes.log_side',function($view){

            $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
            $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
            $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

            $view->with(['payment_methods'=>$payment_methods,'logs'=>$logs,'total_price'=>$total_price]);
          });



          View::composer('includes.list_side',function($view){

            $user=Auth::user();

            $view->with(['user'=>$user]);
          });

    }
}
