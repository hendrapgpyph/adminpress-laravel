<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
    public function index()
    {
        $user = User::where('role','admin')->get();
        return view('home')->with('user', $user);
    }

    public function dataDashboard(Request $req)
    {
        $periode = $req->periode;
        $user_id = $req->user_id;
        // select table by sandbox or production
        if(Auth::User()->sandbox == 1){
            $table = "transaction_sb";
        }else{
            $table = "transaction";
        }

        //total payment
        $total_payment  = DB::table($table)->selectRaw("SUM(amount) as total")->whereNull('deleted_at');
        if($user_id != null){
           $total_payment = $total_payment->where('created_by', $user_id); 
        }
        $total_payment = $total_payment->where('created_at','LIKE',$periode.'%')->get();

        // settlement
        $settlement     = DB::table($table)->selectRaw("SUM(amount) as total")->whereNull('deleted_at');
        if($user_id != null){
           $settlement = $settlement->where('created_by', $user_id); 
        }
        $settlement = $settlement->where('paymentDate','LIKE',$periode.'%')->get();

        // callback
        $fail_callback  = DB::table($table)->selectRaw("COUNT(id) as total")->whereNull('deleted_at');
        if($user_id != null){
           $fail_callback = $fail_callback->where('created_by', $user_id); 
        }
        
        $fail_callback = $fail_callback->where('paymentDate','LIKE',$periode.'%')
                            ->where('status_callback','fail')->get();
        
        $response = [
            'total_payment' => "Rp. ".number_format($total_payment[0]->total,0,',','.'),
            'settlement' => "Rp. ".number_format($settlement[0]->total,0,',','.'),
            'fail_callback' => $fail_callback[0]->total,
        ];
        
        return response()->json($response);
    }

    public function grafikTransaction(Request $req)
    {
        $periode = $req->periode;
        $user_id = $req->user_id;
        // select table by sandbox or production
        if(Auth::User()->sandbox == 1){
            $table = "transaction_sb";
        }else{
            $table = "transaction";
        }

        // payment
        $payment =  DB::table($table)->select([DB::raw("SUM(amount) as total"), DB::raw('(DATE_FORMAT(created_at, "%Y-%m-%d")) as day')]);
        if($user_id != null){
            $payment = $payment->where('created_by', $user_id);
        }
        $payment = $payment->whereNull('deleted_at')
                            ->where('created_at','LIKE', $periode.'%')
                            ->groupBy(DB::raw('(DATE_FORMAT(created_at, "%Y-%m-%d"))'))
                            ->get();

        // settlement
        $settlement =  DB::table($table)->select([DB::raw("SUM(amount) as total"), DB::raw('(DATE_FORMAT(paymentDate, "%Y-%m-%d")) as day')]);
        if($user_id != null){
            $settlement = $settlement->where('created_by', $user_id);
        }
        $settlement = $settlement->whereNull('deleted_at')
                            ->where('paymentDate','LIKE',$periode.'%')
                            ->groupBy(DB::raw('(DATE_FORMAT(paymentDate, "%Y-%m-%d"))'))
                            ->get();
        
        $last_day = date('t', strtotime($periode."-01"));

        $day_arr = [];
        $payment_arr = [];
        $settlement_arr = [];

        for ($i=1; $i <= (int)$last_day; $i++) { 
            $day_arr[] = $i;
            // payment
            $payment_value = 0;
            foreach ($payment as $key => $value) {
                $exp = explode("-", $value->day);
                if((int)$exp[2] == $i){
                    $payment_value = $value->total;
                }
            }
            $payment_arr[] = $payment_value;
            // settlement
            $settlement_value = 0;
            foreach ($settlement as $key => $value) {
                $exp = explode("-", $value->day);
                if((int)$exp[2] == $i){
                    $settlement_value = $value->total;
                }
            }
            $settlement_arr[] = $settlement_value;
        }

        $response = [
            'day' => $day_arr,
            'payment' => $payment_arr,
            'settlement' => $settlement_arr
        ];

        return response()->json($response);
        
    }
}
