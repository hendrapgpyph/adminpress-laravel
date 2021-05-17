<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $req)
    {
        $user = User::all();
        return view('transaction.index')->with('user', $user);
    }

    public function jsonListTransaksi(Request $req)
    {
        $search = $req->cari;
        $range  = $req->range;

        if(Auth::User()->sandbox == 1){
            $table = "transaction_sb";
        }else{
            $table = "transaction";
        }

		$data = DB::table($table)->orderBy('created_at','desc');
		if($search != null && $data != ""){
			$data = $data->where(function($query) use ($search){
			$query->orWhere('name','LIKE','%'.$search.'%')
					->orWhere('keterangan','LIKE','%'.$search.'%')
					->orWhere('custCode','LIKE','%'.$search.'%')
					->orWhere('brivaNo','LIKE','%'.$search.'%')
					->orWhere(DB::raw("CONCAT(brivaNo,custCode)"),'LIKE','%'.$search.'%');
			});
		}
        
        if($range != null){
            $explode    = explode(" - ", $range);
            $startDate  = str_replace("/","-",$explode[0]);
            $endDate    = str_replace("/","-",$explode[1]);
            $data = $data->whereBetween('created_at', [$startDate, $endDate]);
        }

      	$data = $data->paginate(10);
    	return response()->json($data);
    }
}
