<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Briva;
use DB;

class Transaction extends Model
{
    use SoftDeletes;
    protected $table = "transaction";
    protected $fillable = [
        'institutionCode',
        'brivaNo',
        'custCode',
        'nama',
        'amount',
        'keterangan',
        'expiredDate',
        'paymentDate',
        'tellerid',
        'no_rek',
        'created_at',
        'created_by',
        'updated_at',
        'deleted_at',
        'statusBayar',
        'callback_url',
        'callback_expired',
    ];
    protected $primaryKey = "id";

    public function callbackPayment($trans, $type, $sandbox)
    {
        try {
            DB::beginTransaction();
                // fetch into BRIVA
                if($sandbox == 0){
                    $briva = new Briva(0);
                }else{
                    $briva = new Briva(1);
                }

                $response = $briva->BrivaGet($trans->custCode);
                if(
                    isset($response['status']) && $response['status']
                    && isset($response['responseDescription']) && $response['responseDescription'] == "Success"
                    && isset($response['data'])
                ){
                    $params = $response['data'];
                    $callback_url = null;
                    if($type == 'settlement'){
                        if(isset($trans->callback_url) && $trans->callback_url != null){
                            $callback_url = $trans->callback_url;
                        }
                    }else if($type == 'expired'){
                        if(isset($trans->callback_expired) && $trans->callback_expired != null){
                            $callback_url = $trans->callback_expired;
                        }
                    }
                
                    if($callback_url != null){
                        $urlPost = $callback_url;
                        $chPost = curl_init();
                        curl_setopt($chPost, CURLOPT_URL,$urlPost);
                        curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "POST"); 
                        curl_setopt($chPost, CURLOPT_POSTFIELDS, $params);
                        curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
                        curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
                        $resultPost = curl_exec($chPost);
                        $resultPost = json_decode($resultPost);
                        $httpcode = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
                        curl_close($chPost);
                        // update callback
                        $update = [];
                        if($httpcode == 200){
                            $update['status_callback'] = 'success';
                        }else{
                            $update['status_callback'] = 'fail';
                        }
                        // update when expired
                        if($type == 'expired'){
                            $update['expired'] = 1;
                        }
    
                        if($sandbox == 1){
                            $table = "transaction_sb";
                        }else{
                            $table = "transaction";
                        }
                        DB::table($table)->where('id', $trans->id)->update($update);
                        
                        DB::commit();
                        if($update['status_callback'] == 'success'){
                            return ['status' => true,'messages'=>'Success running callback'];
                        }else{
                            return ['status' => false,'messages'=>'Error when running callback'];
                        }
                    }
                
                    DB::commit();
                    return ['status' => false,'messages'=>'callback not found'];
                }else{
                    return ['status' => false,'messages'=>'Error when getting data BRIVA'];
                }
        }catch(\Exception $e) {
            DB::rollBack();
            return ['status' => false,'messages'=>'Error when running callback'];
        }
    }
}
