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
    <div class="col-12">
      <div class="card card-default">
        <div class="card-header">
          <div class="card-actions">
              <a class="" data-action="collapse"><i class="ti-minus"></i></a>
              <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
          </div>
          <h4 class="card-title m-b-0">List Transaction</h4></div>
        <div class="card-body collapse show">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-5">
                        <select class="form-control form-control-sm" id="user_id" onchange="changeUser()">
                            <option value="">Seluruh</option>
                            @foreach ($user as $val)
                                <option value="{{$val->id}}" {{$val->id == Auth::User()->id?'selected':''}}>{{$val->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
            <div class="col-md-12 tableTransaksi">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-4">
                      <input class="form-control form-control-sm input-daterange-datepicker" autocomplete="off" id="daterange" type="text" name="daterange" value="" placeholder="Range tanggal.." />
                    </div>
                    <div class="col-md-3">
                      <select class="form-control form-control-sm" id="status" onchange="cariTransaksi()">
                        <option value="">Seluruh status</option>
                        <option value="pending">Pending</option>
                        <option value="settlement">Settlement</option>
                        <option value="expired">Expired</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <select class="form-control form-control-sm" id="status_callback" onchange="cariTransaksi()">
                        <option value="">Seluruh callback</option>
                        <option value="success">Callback Success</option>
                        <option value="fail">Callback Fail</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <hr>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari...." id="cariTransaksi">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-info btn-sm" onclick="cariTransaksi()"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                   <div class="mailbox-controls">
                      <div class="float-right">                    
                      <span id="displayPage">0-0/0</span>
                        <div class="btn-group" id="btnPaging1">
                          
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="col-12">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover small">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>BrivaNo</th>
                          <th>Name</th>
                          <th>Amount</th>
                          <th>Expired</th>
                          <th><center>Status</center></th>
                          <th><center>Callback</center></th>
                        </tr>
                      </thead>
                      <tbody id="tbodyListTransaksi">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
                 <div class="col-md-12">
                    <div class="mailbox-controls">
                        <div class="float-right">                    
                        <span id="displayPage2">0-0/0</span>
                          <div class="btn-group" id="btnPaging2">
                          
                          </div>
                        </div>
                      </div>
                  </div>
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
<script src="{{url('/template/assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{url('/template/assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{url('js/transaction/index.js')}}"></script>
@endsection