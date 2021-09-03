<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Request\CreateBrivaRequest;
use App\Jobs\ProcessBriva;
use App\Briva;
use App\Transaction;
use App\Transactionsb;
use DB;
use Auth;

class BrivaController extends Controller
{
    private $briva;
    private $type_api;

    public function __construct(Request $req)
    {
        if($req->route('type') == 'production'){
            $this->briva = new Briva(0);
            $this->type_api = 'production';
        }else if($req->route('type') == 'sandbox'){
            $this->briva = new Briva(1);
            $this->type_api = "sandbox";
        }else{
            $response["status"] = false;
            $response["errDesc"] = "URI not found";
            return response()->json($response);
        }
    }

    public function create(Request $req)
    {
        try {
            DB::beginTransaction();
            $name           = $req->name;
            $amount         = (int)$req->amount;
            $keterangan     = $req->get('keterangan',null);
            $expiredDate    = $req->get('expired',null);
            $customerCode   = $req->get('customerCode',null);
            
            if($customerCode == null){
                do {
                    $customerCode   = date('y').rand(10,99).date('m').rand(0,9).date('d').rand(100,999);
                    $response = $this->briva->BrivaCreate($customerCode,$name,$amount,$keterangan,$expiredDate);
                } while (isset($response['responseCode']) && $response['responseCode'] == 13);
            }
            
            if(
                isset($response['status']) && $response['status']
                && isset($response['responseDescription']) && $response['responseDescription'] == "Success"
                && isset($response['data'])
            ){
                $data = $response['data'];
                $insert = [
                    'institutionCode' => $data['institutionCode'],
                    'brivaNo' => $data['brivaNo'],
                    'custCode' => $data['custCode'],
                    'nama' => $data['nama'],
                    'amount' => (int)$data['amount'],
                    'keterangan' => $data['keterangan'],
                    'expiredDate' => $data['expiredDate'],
                    'created_by' => Auth::User()->id,
                    'callback_url' => ($this->type_api == 'production'?Auth::User()->callback_url:Auth::User()->callback_url_sb),
                    'callback_expired' => ($this->type_api == 'production'?Auth::User()->callback_expired:Auth::User()->callback_expired_sb)
                ];
                
                if($this->type_api == 'production'){
                    Transaction::create($insert);
                }else{
                    Transactionsb::create($insert);
                }
            }else{
                $response['status'] = false;
                $response['errDesc'] = (isset($response['errDesc']) ? $response['errDesc'] : "Terjadi Kesalahan saat mengakses BRIVA");
            }

            DB::commit();
        }catch(\Exception $e) {
            $response["status"] = false;
            $response["errDesc"] = $e->getMessage();
            DB::rollBack();
        }
        
        return response()->json($response);
    }

    public function update(Request $req)
    {
        try {
            DB::beginTransaction();
            $customerCode   = $req->customercode;
            $name           = $req->name;
            $amount         = $req->amount;
            $keterangan     = $req->get('keterangan',null);
            $expiredDate    = $req->get('expired',null);

            $response = $this->briva->BrivaUpdate($customerCode,$name,$amount,$keterangan,$expiredDate);
            if(
                isset($response['status']) && $response['status']
                && isset($response['responseDescription']) && $response['responseDescription'] == "Success"
                && isset($response['data'])
            ){
                $data = $response['data'];
                if($this->type_api == 'production'){
                    $trans = Transaction::where('custCode', $data['custCode'])->where('statusBayar','N')->first();
                }else{
                    $trans = Transactionsb::where('custCode', $data['custCode'])->where('statusBayar','N')->first();
                }
                if($trans != null){
                    $trans->institutionCode = $data['institutionCode'];
                    $trans->brivaNo = $data['brivaNo'];
                    $trans->custCode = $data['custCode'];
                    $trans->nama = $data['nama'];
                    $trans->amount = (int)$data['amount'];
                    $trans->keterangan = $data['keterangan'];
                    $trans->expiredDate = $data['expiredDate'];
                    $trans->save();
                }else{
                    $insert = [
                        'institutionCode' => $data['institutionCode'],
                        'brivaNo' => $data['brivaNo'],
                        'custCode' => $data['custCode'],
                        'nama' => $data['nama'],
                        'amount' => (int)$data['amount'],
                        'keterangan' => $data['keterangan'],
                        'expiredDate' => $data['expiredDate'],
                        'created_by' => Auth::User()->id,
                        'callback_url' => ($this->type_api == 'production'?Auth::User()->callback_url:Auth::User()->callback_url_sb),
                        'callback_expired' => ($this->type_api == 'production'?Auth::User()->callback_expired:Auth::User()->callback_expired_sb)
                    ];

                    if($this->type_api == 'production'){
                        $trans = Transaction::create([$insert]);
                    }else{
                        $trans = Transactionsb::create([$insert]);
                    }
                }
            }else{
                $response['status'] = false;
                $response['errDesc'] = (isset($response['errDesc']) ? $response['errDesc'] : "Terjadi Kesalahan saat mengakses BRIVA");
            }

            DB::commit();
        }catch(\Exception $e) {
            $response["status"] = false;
            $response["errDesc"] = $e->getMessage();
            DB::rollBack();
        }

        return response()->json($response);
    }

    public function paidInvoice(Request $req)
    {
        try {
            DB::beginTransaction();
            $customerCode   = $req->customercode;
            $response = $this->briva->BrivaUpdateStatus($customerCode);
            if(
                isset($response['status']) && $response['status']
                && isset($response['responseDescription']) && $response['responseDescription'] == "Success"
                && isset($response['data'])
            ){
                $data = $response['data'];
                if($this->type_api == 'production'){
                    $trans = Transaction::where('custCode', $data['custCode'])->first();
                }else{
                    $trans = Transactionsb::where('custCode', $data['custCode'])->first();
                }
                if($trans != null){
                    $trans->statusBayar = 'Y';
                    $trans->paymentDate = date('Y-m-d H:i:s');
                    $trans->save();
                }
            }else{
                $response['status'] = false;
                $response['errDesc'] = (isset($response['errDesc']) ? $response['errDesc'] : "Terjadi Kesalahan saat mengakses BRIVA");
            }

            DB::commit();
        }catch(\Exception $e) {
            $response["status"] = false;
            $response["errDesc"] = $e->getMessage();
            DB::rollBack();
        }
        return response()->json($response);
    }

    public function unpaidInvoice(Request $req)
    {
        try {
            DB::beginTransaction();
            $customerCode   = $req->customercode;
            $response = $this->briva->BrivaUpdateStatus($customerCode,'N');
            if(
                isset($response['status']) && $response['status']
                && isset($response['responseDescription']) && $response['responseDescription'] == "Success"
                && isset($response['data'])
            ){
                $data = $response['data'];
                if($this->type_api == 'production'){
                    $trans = Transaction::where('custCode', $data['custCode'])->first();
                }else{
                    $trans = Transactionsb::where('custCode', $data['custCode'])->first();
                }
                if($trans != null){
                    $trans->statusBayar = 'N';
                    $trans->paymentDate = NULL;
                    $trans->tellerid = NULL;
                    $trans->no_rek = NULL;
                    $trans->save();
                }
            }else{
                $response['status'] = false;
                $response['errDesc'] = (isset($response['errDesc']) ? $response['errDesc'] : "Terjadi Kesalahan saat mengakses BRIVA");
            }
            
            DB::commit();
        }catch(\Exception $e) {
            $response["status"] = false;
            $response["errDesc"] = $e->getMessage();
            DB::rollBack();
        }
        return response()->json($response);
    }

    public function getDetail(Request $req)
    {
        try {
            DB::beginTransaction();
            $customerCode   = $req->customercode;
            $response = $this->briva->BrivaGet($customerCode);
            DB::commit();
        }catch(\Exception $e) {
            $response["status"] = false;
            $response["errDesc"] = $e->getMessage();
            DB::rollBack();
        }
        return response()->json($response);
    }

    public function deleteInvoice(Request $req)
    {
        try {
            DB::beginTransaction();
            $customerCode   = $req->customercode;
            $response = $this->briva->BrivaDelete($customerCode);
            if(
                isset($response['status']) && $response['status']
                && isset($response['responseDescription']) && $response['responseDescription'] == "Success"
                && isset($response['data'])
            ){
                $data = $response['data'];
                if($this->type_api == 'production'){
                    $trans = Transaction::where('custCode', $data['custCode'])->first();
                }else{
                    $trans = Transactionsb::where('custCode', $data['custCode'])->first();
                }
                if($trans != null){
                    $trans->deleted_at = date('Y-m-d H:i:s');
                    $trans->save();
                }
            }else{
                $response['status'] = false;
                $response['errDesc'] = (isset($response['errDesc']) ? $response['errDesc'] : "Terjadi Kesalahan saat mengakses BRIVA");
            }
            DB::commit();
        }catch(\Exception $e) {
            $response["status"] = false;
            $response["errDesc"] = $e->getMessage();
            DB::rollBack();
        }
        return response()->json($response);
    }

    public function getUpdate(Request $req)
    {
        // get settlement payment BRIVA
        $response = $this->briva->getReportBriva();
        
        if(
            isset($response['status']) && $response['status']
            && isset($response['responseDescription']) && $response['responseDescription'] == "Success"
            && isset($response['data'])
        ){
                $data = $response['data'];
                if($this->type_api == 'production'){
                    $transaction = Transaction::where('statusBayar','N')->get();
                }else{
                    $transaction = Transactionsb::where('statusBayar','N')->get();
                }
                $array = [];
                foreach ($transaction as $key => $val) {
                    $array[] = $val->custCode;
                }
                
                foreach ($data as $key => $value) {
                    if(in_array($value['custCode'], $array)){
                        if($this->type_api == 'production'){
                            $trans = Transaction::where('custCode', $value['custCode'])
                                    ->where('statusBayar','N')
                                    ->first();
                        }else{
                            $trans = Transactionsb::where('custCode', $value['custCode'])
                                    ->where('statusBayar','N')
                                    ->first();
                        }
                        if($trans != null){
                            // add status settlement
                            $value['status'] = 'settlement';
                            $trans->paymentDate = $value['paymentDate'];
                            $trans->tellerid    = $value['tellerid'];
                            $trans->no_rek      = $value['no_rek'];
                            $trans->statusBayar = 'Y';
                            $trans->save();
                            ProcessBriva::dispatch($value,'settlement',$this->type_api);
                        }
                    }
                }
            
        }

        // get expired payment
        if($this->type_api == 'production'){
            $transaction = Transaction::where('expiredDate','<',date('Y-m-d H:i:s'))->where('expired',0)->where('statusBayar','N')->get();
        }else{
            $transaction = Transactionsb::where('expiredDate','<',date('Y-m-d H:i:s'))->where('expired',0)->where('statusBayar','N')->get();
        }
        foreach ($transaction as $key => $val) {
            $send = [];
            $send['status'] = 'expired';
            $send['custCode'] = $val->custCode;
            $send['brivaNo'] = $val->brivaNo;
            $send['nama'] = $val->nama;
            $send['amount'] = $val->amount;
            $send['keterangan'] = $val->keterangan;
            $send['expiredDate'] = $val->expiredDate;
            ProcessBriva::dispatch($send,'expired',$this->type_api);
        }
    }
}
