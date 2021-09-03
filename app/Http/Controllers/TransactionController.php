<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Briva;
use App\Transaction;

class TransactionController extends Controller
{
    public function index(Request $req)
    {
        $user = User::where('role','admin')->get();
        return view('transaction.index')->with('user', $user);
    }

    public function jsonListTransaksi(Request $req)
    {
        $search = $req->cari;
        $range  = $req->range;
        $status = $req->status;
        $status_callback = $req->status_callback;
        $user = $req->user;

        // select table by sandbox or production
        if(Auth::User()->sandbox == 1){
            $table = "transaction_sb";
        }else{
            $table = "transaction";
        }

		$data = DB::table($table)->select([$table.".*",'users.name as nama_user'])->join('users','users.id',$table.".created_by");
		
        // search
        if($search != null && $data != ""){
			$data = $data->where(function($query) use ($search){
			$query->orWhere('nama','LIKE','%'.$search.'%')
					->orWhere('keterangan','LIKE','%'.$search.'%')
					->orWhere('custCode','LIKE','%'.$search.'%')
					->orWhere('brivaNo','LIKE','%'.$search.'%')
					->orWhere(DB::raw("CONCAT(brivaNo,custCode)"),'LIKE','%'.$search.'%')
					->orWhere(DB::raw("CONCAT(brivaNo,'-',custCode)"),'LIKE','%'.$search.'%');
			});
		}
        
        // range
        if($range != null){
            $explode    = explode(" - ", $range);
            $startDate  = str_replace("/","-",$explode[0]);
            $endDate    = str_replace("/","-",$explode[1]);
            if($startDate != $endDate){
                $data = $data->whereBetween($table.'.created_at', [$startDate, $endDate]);
            }else{
                $data = $data->where($table.'.created_at', 'LIKE', "%".$startDate."%");
            }
        }
        // status
        if($status == 'pending'){
            $data = $data->where('statusBayar','N');
        }else if($status == 'settlement'){
            $data = $data->where('statusBayar','Y')->where('expired',0);
        }else if($status == 'expired'){
            $data = $data->where('expired',1);
        }

        // callback
        if($status_callback == "success"){
            $data = $data->where('status_callback','success');
        }else if($status_callback == "fail"){
            $data = $data->where('status_callback','fail');
        }

        if($user != null && $user != ""){
            $data = $data->where($table.".created_by", $user);
        }
        $data = $data->whereNull('deleted_at')->orderBy($table.'.created_at','desc')->paginate(20);
    	return response()->json($data);
    }

    public function detail($id)
    {
        if(Auth::User()->sandbox == 1){
            $table = "transaction_sb";
        }else{
            $table = "transaction";
        }

        $data = DB::table($table)->select([$table.".*",'users.name as nama_user'])
                ->join('users','users.id','=',$table.".created_by")
                ->where($table.'.id', $id)->first();

        if($data == null){
            return redirect('/transaction/');
        }
        return view('transaction.detail')->with('data', $data);
    }

    public function updateCallback(Request $req)
    {
        if($req->type == 'settlement'){
            $column = "callback_url";
        }else{
            $column = "callback_expired";
        }

        if(Auth::User()->sandbox == 1){
            $table = "transaction_sb";
        }else{
            $table = "transaction";
        }

        DB::table($table)->where('id',$req->id)->update([$column."" => $req->url]);

        return response()->json([
            'status' => true,
            'messages' => 'sukses'
        ]);
    }

    public function deleteTransaction(Request $req)
    {
        $id = $req->id;
        if(Auth::User()->sandbox == 1){
            $table = "transaction_sb";
            $briva = new Briva(1);
        }else{
            $table = "transaction";
            $briva = new Briva(0);
        }

        $data = DB::table($table)->where('id', $id)->first();
  
        if($data == null){
            return response()->json([
                'status' => false,
                'messages' => 'Transaction not Found'
            ]); 
        }else{
            $response = $briva->BrivaDelete($data->custCode);
            if(
                isset($response['status']) && $response['status']
                && isset($response['responseDescription']) && $response['responseDescription'] == "Success"
            ){
                DB::table($table)->where('id', $id)->update(['deleted_at'=>date('Y-m-d H:i:s')]);
            }
            return response()->json($response);
        }
    }

    public function callbackAgain(Request $req)
    {
        $transaction = new Transaction;
        $id = $req->id;
        if(Auth::User()->sandbox == 1){
            $table = "transaction_sb";
        }else{
            $table = "transaction";
        }

        $data = DB::table($table)->where('id', $id)->first();
  
        if($data == null){
            return response()->json([
                'status' => false,
                'messages' => 'Transaction not Found'
            ]); 
        }else{
            if($data->expired == 1){
                $response = $transaction->callbackPayment($data, 'expired', Auth::User()->sandbox);
                return response()->json($response);
            }else if($data->statusBayar == 'N'){
                return response()->json([
                    'status' => false,
                    'messages' => 'Transaksi belum dibayarkan'
                ]);
            }else if($data->status_callback == 'success'){
                return response()->json([
                    'status' => false,
                    'messages' => 'Status callback sudah dilakukan sebelumnya'
                ]);
            }else if($data->status_callback == 'fail'){
                $response = $transaction->callbackPayment($data, 'settlement', Auth::User()->sandbox);
                return response()->json($response);
            }else{
                return response()->json([
                    'status' => false,
                    'messages' => 'Data invalid'
                ]); 
            }
        }
    }
}
