@extends('layouts.appweb')
@section('title')
{{ config('app.name') }} | Transaction
@endsection
@section("content")
<!-- Daterange picker plugins css -->
<link href="{{url('/template/assets/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
<link href="{{url('/template/assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-themecolor">Transaction</h3>
    </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Transaction</a></li>
            <li class="breadcrumb-item active">Detail</li>
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
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <div class="card-actions">
              <a class="" data-action="collapse"><i class="ti-minus"></i></a>
              <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
          </div>
          <h4 class="card-title m-b-0">Detail Transaction</h4></div>
        <div class="card-body collapse show">
            <div class="row">
                <div class="col-md-12">
                    <input type="hidden" id="transaction_id" value="{{$data->id}}"> 
                    <table class="table">
                        <tr>
                            <td>Users</td><td>:</td>
                            <td>{{$data->nama_user}}</td>
                        </tr>
                        <tr>
                            <td>Created at</td><td>:</td>
                            <td>{{date('d M Y H:i', strtotime($data->created_at))}}</td>
                        </tr>
                        <tr>
                            <td>Briva No</td><td>:</td>
                            <td>{{$data->brivaNo."-".$data->custCode}}</td>
                        </tr>
                        <tr>
                            <td>Name</td><td>:</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                        <tr>
                            <td>Keterangan</td><td>:</td>
                            <td>{{$data->keterangan}}</td>
                        </tr>
                        <tr>
                            <td>Amount</td><td>:</td>
                            <td>Rp. {{number_format($data->amount,0,',','.')}}</td>
                        </tr>
                        <tr>
                            <td>Expired date</td><td>:</td>
                            <td>{{date('d M Y H:i', strtotime($data->expiredDate))}}</td>
                        </tr>
                        <tr>
                            <td>Status</td><td>:</td>
                            <td>
                                @if($data->expired == 1)
                                    <h3><span class="badge badge-danger">Expired</span></h3>
                                @elseif($data->statusBayar == 'N')
                                    <h3><span class="badge badge-warning">Pending</span></h3>
                                @elseif($data->statusBayar == 'Y')
                                    <h3><span class="badge badge-success">Settlement</span></h3>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Callback Settlement</td><td>:</td>
                            <td>
                                @if($data->status_callback == 'success')
                                    {{$data->callback_url}}
                                @else
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm callback-settlement" value="{{$data->callback_url}}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="updateCallback('callback-settlement')">Update</button>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Callback Expired</td><td>:</td>
                            <td>
                                @if($data->status_callback == 'success')
                                    {{$data->callback_expired}}
                                @else
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm callback-expired" value="{{$data->callback_expired}}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="updateCallback('callback-expired')">Update</button>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Status Callback</td><td>:</td>
                            <td>
                                @if($data->status_callback == 'success')
                                    <h3><span class="badge badge-success">Success</span></h3>
                                @elseif($data->status_callback == 'fail')
                                    <h3 style="display: inline-block"><span class="badge badge-danger">Fail</span></h3>
                                    <button class="btn btn-default btn-sm" onclick="calllbackAgain({{$data->id}})"><i class="fa fa-refresh"></i></button>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-12">
                    <div class="btn group">
                        <a href="{{url('/transaction')}}" class="btn btn-outline-danger">
                           Kembali
                       </a>
                    </div>
                    <div class="btn-group pull-right">
                        {{-- <button type="button" class="btn btn-danger" onclick="deleteTransaction({{$data->id}})">
                            Delete Transaction
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section("javascript")
<script>
    function updateCallback(classname)
    {
        if($("."+classname).val() == "" || $("."+classname).val() == null){
            $("."+classname).focus();
            return false;
        }

        Swal.fire({
            title: 'Apa anda Yakin?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: 'Loading..',
                    html: '',
                    allowOutsideClick: false,
                    onOpen: () => {
                    swal.showLoading()
                    }
                });
                let update = { _token : $('meta[name=csrf-token]').attr('content'), 
                                id : $("#transaction_id").val(), 
                                url : $("."+classname).val(),
                                type : (classname == 'callback-settlement' ? 'settlement' : 'expired')
                            };
                $.post("{{url('/transaction/update_callback')}}", update, function(result){
                    Swal.fire(
                        result.messages,
                        '',
                        'success'
                    ) 
                }).catch((err) => {
                    Swal.fire(
                        'Proses gagal',
                        '',
                        'error'
                    );   
                });
            }
        });
    }

    function deleteTransaction(id){
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: 'Loading..',
                    html: '',
                    allowOutsideClick: false,
                    onOpen: () => {
                    swal.showLoading()
                    }
                });

                let update = { _token : $('meta[name=csrf-token]').attr('content'), 
                                id : $("#transaction_id").val(),
                            };
                $.post("{{url('/transaction/delete_transaction')}}", update, function(result){
                    if(result.status){
                        Swal.fire(
                            'Sukses',
                            '',
                            'success'
                        ) 
                        window.history.back();
                    }else{
                        Swal.fire(
                            result.messages,
                            '',
                            'error'
                        )
                    }
                }).catch((err) => {
                    Swal.fire(
                        'Proses gagal',
                        '',
                        'error'
                    );   
                });
            }
        });
    }

    function calllbackAgain(id){
        Swal.fire({
            title: 'Jalankan Callback?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: 'Loading..',
                    html: '',
                    allowOutsideClick: false,
                    onOpen: () => {
                    swal.showLoading()
                    }
                });
                let update = { _token : $('meta[name=csrf-token]').attr('content'), 
                                id : $("#transaction_id").val(),
                            };
                $.post("{{url('/transaction/callback_again')}}", update, function(result){
                    if(result.status){
                        Swal.fire(
                            result.messages,
                            '',
                            'success'
                        ) 
                        location.reload();
                    }else{
                        Swal.fire(
                            result.messages,
                            '',
                            'error'
                        )
                    }
                }).catch((err) => {
                    Swal.fire(
                        'Proses gagal',
                        '',
                        'error'
                    );   
                });


            }
        });
    }
</script>
@endsection