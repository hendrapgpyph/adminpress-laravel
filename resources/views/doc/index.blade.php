@extends('layouts.appweb')
@section('title')
{{ config('app.name') }} | Documentation
@endsection
@section("content")
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">Documentation</h3>
    </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Documentation</a></li>
            <li class="breadcrumb-item active">Index</li>
        </ol>
    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container">
  <div class="row">
    <div class="col-md-12">
        <div class="card card-default">
        <div class="card-header">
            <div class="card-actions">
                <a class="" data-action="collapse"><i class="ti-minus"></i></a>
                <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3>BRIVA Documentation</h3>
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-12">
                    <h3>Setup</h3>
                </div>
                <div class="col-md-12">
                    <p>Terdapat 2 end poin dalam menggunakan JINOM BRIVA yaitu</p>
                    <ol>
                        <li><b>Sandbox</b> : {{url('/')}}/api/briva/sandbox/.. (route)</li>
                        <li><b>Production</b> : {{url('/')}}/api/briva/production/.. (route)</li>
                    </ol>
                    <p>
                </div>
                <div class="col-md-12">
                    <h4>PENTING!</h4>
                    <ol>
                        <li>
                            Dalam mengolah response, perlu diperhatikan bahwa response akan selalu me return <b>status</b> yang bernilai <i>false</i> jika terjadi kesalahan dan <i>true</i> jika API berhasil dijalankan serta <b>errDesc</b> untuk menampilkan pesan error
<pre>
{
    "status": false,
    "errDesc": "Data Customer Tidak Ditemukan",
    "responseCode": "14",
    "data": {
        "institutionCode": "J104408",
        "brivaNo": "77777",
        "custCode": "213105020231"
    }
}
</pre>
                        </li>
                        <li>
                            Pada API Callback, anda <b>WAJIB</b> me return status 200 untuk menandakan bahwa API Callback telah berhasil di jalankan.
<pre>
&lt;?php
    return response('success',200);
?&gt;
</pre>
                        </li>
                    </ol>
                </div>
                <div class="col-md-12">
                    <br>
                    <h3>Authorization</h3>
                </div>
                <div class="col-md-12">
                    <p>
                        Authentifikasi JINOM BRIVA menggunakan <b>Bearer token</b>, anda bisa menggunakan API Token yang bisa anda dapatkan di <b>Profile</b>
                    </p>
                    <img style="width:350px" src="{{url('/img/doc1.png')}}"></div>
                </div>
                <div class="col-md-12">
<pre>
    $.ajaxSetup({
        headers: {
            Authorization: "Bearer {YOUR_TOKEN}",
        },
     });
</pre>
<pre>
    $request_headers = array(
        "Authorization:Bearer " . {YOUR_TOKEN},
    );
    $urlPost = {URL};
    $chPost = curl_init();
    curl_setopt($chPost, CURLOPT_URL,$urlPost);
    curl_setopt($chPost, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($chPost, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($chPost, CURLINFO_HEADER_OUT, true);
    curl_setopt($chPost, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($chPost, CURLOPT_RETURNTRANSFER, true);
    $resultPost = curl_exec($chPost);
    curl_close($chPost);
</pre>
                </div>

                {{-- CREATE TRANSACTION --}}
                <div class="col-md-12">
                    <br>
                    <hr>
                    <h3>Create Transaction</h3>
                </div>
                <div class="col-md-12">
                    <table style="width: 100%">
                        <tr>
                            <td>End Poin</td>
                            <td>:</td>
                            <td>{{url('/')}}/api/briva/sandbox/create</td>
                        </tr>
                        <tr>
                            <td>Method</td>
                            <td>:</td>
                            <td><b>POST</b></td>
                        </tr>
                    </table>
                    <h3>Parameter</h3>
                    <table class="table table-sm" style="width: 100%">
                        <tr>
                            <th>Field</th>
                            <th>Datatype</th>
                            <th>Max size</th>
                            <th>Desc</th>
                        </tr>
                        <tr>
                            <td>name</td>
                            <td>String</td>
                            <td>40</td>
                            <td>Nama Customer</td>
                        </tr>
                        <tr>
                            <td>amount</td>
                            <td>Numeric</td>
                            <td>-</td>
                            <td>Nominal transaksi</td>
                        </tr>
                        <tr>
                            <td>keterangan</td>
                            <td>String</td>
                            <td>40</td>
                            <td>Keterangan transaksi</td>
                        </tr>
                        <tr>
                            <td>expired</td>
                            <td>Datetime (nullable)</td>
                            <td>-</td>
                            <td>Tanggal kadaluarsa transaksi. Default expired : + 1 jam <br>(dapat dikosongkan)</td>
                        </tr>
                    </table>
                    <table style="width: 100%">
                        <tr>
                            <td>Response</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12">
<pre>
    {
        "status": true,
        "responseDescription": "Success",
        "responseCode": "00",
        "data": {
            "institutionCode": "J104408",
            "brivaNo": "77777",
            "custCode": "218705520707",
            "nama": "Testing BRIVA",
            "amount": "10000",
            "keterangan": "",
            "expiredDate": "2021-05-20 14:44:19"
        }
    }
</pre>
                </div>
                {{-- END CREATE TRANSACTION --}}

                {{-- UPDATE TRANSACTION --}}
                <div class="col-md-12">
                    <br>
                    <hr>
                    <h3>Update Transaction</h3>
                </div>
                <div class="col-md-12">
                    <table style="width: 100%">
                        <tr>
                            <td>End Poin</td>
                            <td>:</td>
                            <td>{{url('/')}}/api/briva/sandbox/update</td>
                        </tr>
                        <tr>
                            <td>Method</td>
                            <td>:</td>
                            <td><b>POST</b></td>
                        </tr>
                    </table>
                    <h3>Parameter</h3>
                    <table class="table table-sm" style="width: 100%">
                        <tr>
                            <th>Field</th>
                            <th>Datatype</th>
                            <th>Max size</th>
                            <th>Desc</th>
                        </tr>
                        <tr>
                            <td>customercode</td>
                            <td>String</td>
                            <td>13</td>
                            <td>Customer Code yang di dapatkan saat create transaction</td>
                        </tr>
                        <tr>
                            <td>name</td>
                            <td>String</td>
                            <td>40</td>
                            <td>Nama Customer</td>
                        </tr>
                        <tr>
                            <td>amount</td>
                            <td>Numeric</td>
                            <td>-</td>
                            <td>Nominal transaksi</td>
                        </tr>
                        <tr>
                            <td>keterangan</td>
                            <td>String</td>
                            <td>40</td>
                            <td>Keterangan transaksi</td>
                        </tr>
                        <tr>
                            <td>expired</td>
                            <td>Datetime (nullable)</td>
                            <td>-</td>
                            <td>Tanggal kadaluarsa transaksi. <br>(dapat dikosongkan)</td>
                        </tr>
                    </table>
                    <table style="width: 100%">
                        <tr>
                            <td>Response</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12">
<pre>
    {
        "status": true,
        "responseDescription": "Success",
        "responseCode": "00",
        "data": {
            "institutionCode": "J104408",
            "brivaNo": "77777",
            "custCode": "218705520707",
            "nama": "Testing BRIVA",
            "amount": "10000",
            "keterangan": "",
            "expiredDate": "2021-05-20 14:44:19"
        }
    }
</pre>
                </div>
                {{-- END UPDATE TRANSACTION --}}

                 {{-- DELETE TRANSACTION --}}
                 <div class="col-md-12">
                    <br>
                    <hr>
                    <h3>Delete Transaction</h3>
                </div>
                <div class="col-md-12">
                    <table style="width: 100%">
                        <tr>
                            <td>End Poin</td>
                            <td>:</td>
                            <td>{{url('/')}}/api/briva/sandbox/delete</td>
                        </tr>
                        <tr>
                            <td>Method</td>
                            <td>:</td>
                            <td><b>POST</b></td>
                        </tr>
                    </table>
                    <h3>Parameter</h3>
                    <table class="table table-sm" style="width: 100%">
                        <tr>
                            <th>Field</th>
                            <th>Datatype</th>
                            <th>Max size</th>
                            <th>Desc</th>
                        </tr>
                        <tr>
                            <td>customercode</td>
                            <td>String</td>
                            <td>13</td>
                            <td>Customer code yang di dapatkan saat create transaction</td>
                        </tr>
                    </table>
                    <table style="width: 100%">
                        <tr>
                            <td>Response</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12">
<pre>
    {
        "status": true,
        "responseDescription": "Success",
        "responseCode": "00",
        "data": {
            "institutionCode": "J104408",
            "brivaNo": "77777",
            "custCode": "213605220921"
        }
    }  
</pre>
                </div>
                {{-- END DELETE TRANSACTION --}}

                 {{-- DETAIL TRANSACTION --}}
                 <div class="col-md-12">
                    <br>
                    <hr>
                    <h3>Detail Transaction</h3>
                </div>
                <div class="col-md-12">
                    <table style="width: 100%">
                        <tr>
                            <td>End Poin</td>
                            <td>:</td>
                            <td>{{url('/')}}/api/briva/sandbox/getDetail</td>
                        </tr>
                        <tr>
                            <td>Method</td>
                            <td>:</td>
                            <td><b>POST</b></td>
                        </tr>
                    </table>
                    <h3>Parameter</h3>
                    <table class="table table-sm" style="width: 100%">
                        <tr>
                            <th>Field</th>
                            <th>Datatype</th>
                            <th>Max size</th>
                            <th>Desc</th>
                        </tr>
                        <tr>
                            <td>customercode</td>
                            <td>String</td>
                            <td>13</td>
                            <td>Customer code yang di dapatkan saat create transaction</td>
                        </tr>
                    </table>
                    <table style="width: 100%">
                        <tr>
                            <td>Response</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12">
<pre>
    {
        "status": true,
        "responseDescription": "Success",
        "responseCode": "00",
        "data": {
            "institutionCode": "J104408",
            "BrivaNo": "77777",
            "CustCode": "213105020607",
            "Nama": "HENDRA SETIAWANNN",
            "Amount": "120000.00",
            "Keterangan": "TESTING PEMBAYARAN",
            "statusBayar": "N",
            "expiredDate": "2021-05-21 11:53:43",
            "lastUpdate": "2021-05-20 10:55:24.000"
        }
    } 
</pre>
                </div>
                {{-- DETAIL TRANSACTION --}}



            </div>
        </div>
    </div>
  </div>
</div>
@endsection
@section("javascript")
<script>
    function myFunction() {
        var copyText = document.getElementById("api_token");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        
        Toast.fire({
            icon: 'success',
            title: 'Copied'
        });
    }
</script>
@endsection