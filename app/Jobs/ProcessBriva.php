<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Transaction;
use App\Transactionsb;
use DB;

class ProcessBriva implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $type;
    public $type_api;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $type, $type_api)
    {
        $this->data     = $data;
        $this->type     = $type;
        $this->type_api = $type_api;
        // $this->handle();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $params = $this->data;
            if($this->type_api == 'production'){
                $trans = Transaction::where('custCode', $params['custCode'])
                        ->where('statusBayar','N')
                        ->first();
            }else{
                $trans = Transactionsb::where('custCode', $params['custCode'])
                        ->where('statusBayar','N')
                        ->first();
            }
            if($trans != null){
                $callback_url = null;
                if($this->type == 'settlement'){
                    if(isset($trans->callback_url) && $trans->callback_url != null){
                        $callback_url = $trans->callback_url;
                    }
                }else if($this->type == 'expired'){
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
                    if($httpcode == 200){
                        $trans->status_callback = 'success';
                    }else{
                        $trans->status_callback = 'fail';
                    }
                    // update when expired
                    if($this->type == 'expired'){
                        $trans->expired = 1;
                    }
                    $trans->save();
                }
            }
            DB::commit();
        }catch(\Exception $e) {
            DB::rollBack();
        }
    }
}
