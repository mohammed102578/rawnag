<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\Models\License;
use App\Models\Log;
use App\Models\Payment_method;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

define('PAGINATION_COUNT',10);
class LicenseController extends Controller
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
    //=====user=====



public function about_us()
{
    $date_tody=date('Y-m-d');

    $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;

   $diff = strtotime($expiered) - strtotime($date_tody);
   $remaining_days= round($diff/ 86400);

   if($remaining_days<=0){

   return redirect()->route('license');
   }



    if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

      }else{

        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

      }


$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

    return view('pages.about_us',compact(['permissionsGroup','total_price','logs','payment_methods']));

}

public function license()
{

    return view('pages.license');
}

//store
public function store_license(Request $request)
{


 $company_id=Auth::user()->company_id;
$licens_request=$request->license;

$response = Http::asForm()->post('https://etooplay.com/Intelligent System_licenses.php', [
    'license' => $request->license,

]);

$apidata=json_decode( $response);
$api_company_id=$apidata->company_id;
$api_license=$apidata->license;
$expire_date=$apidata->expire_date;


if($licens_request==$api_license && $company_id==$api_company_id){
    $company_id=Auth::user()->company_id;

    License::where('company_id',$company_id)->where('status',0)
    ->create([
        'license'=>$api_license,
        'company_id'=>$api_company_id,
        'expiered_date'=>$expire_date,

    ])->save();

return redirect()->route('home');

}else{

    return back()->with('error',  'لا يوجد ترخيص بهذا الرقم');

}
try{
        } catch (\Exception $ex) {
            return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');
        }


}

public function license_system(){
    $date_tody=date('Y-m-d');

    $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;

   $diff = strtotime($expiered) - strtotime($date_tody);
   $remaining_days= round($diff/ 86400);

   if($remaining_days<=0){

   return redirect()->route('license');
   }



    if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

      }else{

        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

      }


$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->paginate(PAGINATION_COUNT);
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

    $company=$expiered = \DB::table('licenses')->latest("id")->first();
    return view('pages.license_system',compact(['company','permissionsGroup','total_price','logs','payment_methods']));


}
//=====end user====================================================

}
