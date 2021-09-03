<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Briva extends Model
{
	private $client_id;
	private $secret_id;
	private $timestamp;

	private $institutionCode;
    private $url;
    private $coorporate_code;

    public $sandbox=1;
	
	function __construct($sandbox)
	{
		parent::__construct();
		$this->timestamp = date("Y-m-d\TH:i:s.000\Z", strtotime("-8 hour", strtotime(date("Y-m-d H:i:s"))));
        // dd($this->timestamp);
        $this->sandbox = $sandbox;
        $this->setVariable();
	}

    public function setVariable()
    {
        $this->client_id = config('services')["briva"][($this->sandbox == 1?"sandbox":"production")]["client_id"];
        $this->secret_id = config('services')["briva"][($this->sandbox == 1?"sandbox":"production")]["secret_id"];
        
        $this->institutionCode = config("services")["briva"][($this->sandbox == 1?"sandbox":"production")]["institutionCode"];
        $this->url = config("services")["briva"][($this->sandbox == 1?"sandbox":"production")]["url"];
        $this->coorporate_code = config("services")["briva"][($this->sandbox == 1?"sandbox":"production")]["coorporate_code"];
    }

	private function BRIVAgenerateToken(){
        $url = $this->url."/oauth/client_credential/accesstoken?grant_type=client_credentials";
        $data = "client_id=".$this->client_id."&client_secret=".$this->secret_id;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  //for updating we have to use PUT method.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $json = json_decode($result, true);
        $accesstoken = $json['access_token'];

        return $accesstoken;
    }

    /*Generate signature*/
    private function BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret){
        $payloads = "path=$path&verb=$verb&token=Bearer $token&timestamp=$timestamp&body=$payload";
        $signPayload = hash_hmac('sha256', $payloads, $secret, true);
        return base64_encode($signPayload);
    }

    public function BrivaGet($custCode,$brivaNo=null){
        if ($brivaNo == null) {
          $brivaNo = $this->coorporate_code;
        }
        $timestamp = $this->timestamp;
        $secret = $this->secret_id;
        //generate token
        $token = $this->BRIVAgenerateToken();

        $institutionCode = $this->institutionCode;
        $brivaNo = "$brivaNo";
        $custCode = "$custCode";

        $payload = null;
        $path = "/v1/briva/".$institutionCode."/".$brivaNo."/".$custCode;
        $verb = "GET";
        //generate signature
        $base64sign = $this->BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);

        $request_headers = array(
                            "Authorization:Bearer " . $token,
                            "BRI-Timestamp:" . $timestamp,
                            "BRI-Signature:" . $base64sign,
                        );

        $urlPost = $this->url."/v1/briva/".$institutionCode."/".$brivaNo."/".$custCode;
        $chPost = curl_init();
        curl_setopt($chPost, CURLOPT_URL,$urlPost);
        curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
        curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
        $resultPost = curl_exec($chPost);
        $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
        curl_close($chPost);

        $jsonPost = json_decode($resultPost, true);
        return $jsonPost;
    }

    public function BrivaCreate($customerCode,$name,$amount,$keterangan="",$expiredDate=null){
        $timestamp = $this->timestamp;
        $secret = $this->secret_id;
        //generate token
        $token = $this->BRIVAgenerateToken();
        if ($expiredDate == null) {
        	$expiredDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + 60*60);
        }
        $institutionCode = $this->institutionCode; 
        $brivaNo = $this->coorporate_code;       
        $custCode = "$customerCode";
        $nama = "$name";
        $amount= "$amount";
        $keterangan="$keterangan";
        $expiredDate="$expiredDate";

        $datas = array('institutionCode' => $institutionCode ,
            'brivaNo' => $brivaNo,
            'custCode' => $custCode,
            'nama' => $nama,
            'amount' => $amount,
            'keterangan' => $keterangan,
            'expiredDate' => $expiredDate);
        
	      $payload = json_encode($datas, true);
	      $path = "/v1/briva";
	      $verb = "POST";
	      //generate signature
	      $base64sign = $this->BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);
	      $request_headers = array(
	                          "Content-Type:"."application/json",
	                          "Authorization:Bearer " . $token,
	                          "BRI-Timestamp:" . $timestamp,
	                          "BRI-Signature:" . $base64sign,
	                      );

	      $urlPost = $this->url."/v1/briva";
	      $chPost = curl_init();
	      curl_setopt($chPost, CURLOPT_URL,$urlPost);
	      curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
	      curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "POST"); 
	      curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
	      curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
	      curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
	      $resultPost = curl_exec($chPost);
	      $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
	      curl_close($chPost);


	      $jsonPost = json_decode($resultPost, true);
          return $jsonPost; 
    }

    public function BrivaUpdate($customerCode,$name,$amount,$keterangan="",$expiredDate=null){        
        $timestamp = $this->timestamp;
        $secret = $this->secret_id;
        //generate token
        $token = $this->BRIVAgenerateToken();

        $institutionCode = $this->institutionCode;
        $brivaNo = $this->coorporate_code;       
        $custCode = "$customerCode";
        $nama = "$name";
        $amount= "$amount";
        $keterangan="$keterangan";
        $expiredDate="$expiredDate";
        if ($expiredDate == null) {
        	$expiredDate = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) + 60*60);
        }

        $datas = array('institutionCode' => $institutionCode ,
                                'brivaNo' => $brivaNo,
                                'custCode' => $custCode,
                                'nama' => $nama,
                                'amount' => $amount,
                                'keterangan' => $keterangan,
                                'expiredDate' => $expiredDate);

            $payload = json_encode($datas, true);
            $path = "/v1/briva";
            $verb = "PUT";
            $base64sign = $this->BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);
            // var_dump($timestamp);
            // die();

            $request_headers = array(
                                "Content-Type:"."application/json",
                                "Authorization:Bearer " . $token,
                                "BRI-Timestamp:" . $timestamp,
                                "BRI-Signature:" . $base64sign,
                            );

            $urlPost = $this->url."/v1/briva";
            $chPost = curl_init();
            curl_setopt($chPost, CURLOPT_URL,$urlPost);
            curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "PUT"); 
            curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
            curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
            $resultPost = curl_exec($chPost);
            $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
            curl_close($chPost);

            $jsonPost = json_decode($resultPost, true);

            return $jsonPost;
    }

    public function BrivaUpdateStatus($customerCode,$status="Y"){        
        $timestamp = $this->timestamp;
        $secret = $this->secret_id;
        //generate token
        $token = $this->BRIVAgenerateToken();

       	$institutionCode = $this->institutionCode;
        $brivaNo = $this->coorporate_code;
        $custCode = "$customerCode";
        $statusBayar = "$status";

        $datas = array('institutionCode' => $institutionCode ,
        'brivaNo' => $brivaNo,
        'custCode' => $custCode,
        'statusBayar'=> $statusBayar);

            $payload = json_encode($datas, true);
            $path = "/v1/briva/status";
            $verb = "PUT";
            $base64sign = $this->BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);

            $request_headers = array(
                                "Content-Type:"."application/json",
                                "Authorization:Bearer " . $token,
                                "BRI-Timestamp:" . $timestamp,
                                "BRI-Signature:" . $base64sign,
                            );

            $urlPost = $this->url."/v1/briva/status";
            $chPost = curl_init();
            curl_setopt($chPost, CURLOPT_URL,$urlPost);
            curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
            curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "PUT"); 
            curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
            curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
            $resultPost = curl_exec($chPost);
            $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
            curl_close($chPost);

            $jsonPost = json_decode($resultPost, true);

            return $jsonPost;
    }

    public function getReportBriva(){
        $institutionCode = $this->institutionCode;
        $brivaNo = $this->coorporate_code;
        $timestamp = $this->timestamp;
        $secret = $this->secret_id;
        //generate token
        $token = $this->BRIVAgenerateToken();

        $brivaNo = $brivaNo;
        $endDate = date('Ymd');
        $startDate = date('Ymd', strtotime('-1 day', strtotime($endDate)));
        
        $payload = null;
        $path = "/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate;
        $verb = "GET";
        //generate signature
        $base64sign = $this->BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);

        $request_headers = array(
                            "Authorization:Bearer " . $token,
                            "BRI-Timestamp:" . $timestamp,
                            "BRI-Signature:" . $base64sign,
                        );

        $urlPost = $this->url."/v1/briva/report/".$institutionCode."/".$brivaNo."/".$startDate."/".$endDate;
        $chPost = curl_init();
        curl_setopt($chPost, CURLOPT_URL,$urlPost);
        curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
        curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
        $resultPost = curl_exec($chPost);
        $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
        curl_close($chPost);

        $jsonPost = json_decode($resultPost, true);
        return $jsonPost;

    }

    public function BrivaDelete($custCode){
        $timestamp = $this->timestamp;
        $secret = $this->secret_id;
        //generate token
        $token = $this->BRIVAgenerateToken();

        $institutionCode = $this->institutionCode;
        $brivaNo = $this->coorporate_code;

        $payload = "institutionCode=".$institutionCode."&brivaNo=".$brivaNo."&custCode=".$custCode;
        $path = "/v1/briva";
        $verb = "DELETE";
        //generate signature
        $base64sign = $this->BRIVAgenerateSignature($path,$verb,$token,$timestamp,$payload,$secret);

        $request_headers = array(
                            "Authorization:Bearer " . $token,
                            "BRI-Timestamp:" . $timestamp,
                            "BRI-Signature:" . $base64sign,
                        );

        $urlPost = $this->url."/v1/briva";
        $chPost = curl_init();
        curl_setopt($chPost, CURLOPT_URL,$urlPost);
        curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
        curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
        $resultPost = curl_exec($chPost);
        $httpCodePost = curl_getinfo($chPost, CURLINFO_HTTP_CODE);
        curl_close($chPost);

        $jsonPost = json_decode($resultPost, true);
        return $jsonPost;
    }
}

?>