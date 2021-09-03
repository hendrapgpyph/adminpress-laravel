@extends('layouts.appweb')
@section('title')
{{ config('app.name') }}
@endsection
@section("content")
<style>
    .datepicker-dropdown{
        z-index: 100 !important;
    }
</style>
<!-- ============================================================== -->
 <!-- Bread crumb and right sidebar toggle -->
 <!-- ============================================================== -->
 <div class="row page-titles">
     <div class="col-md-5 align-self-center">
         <h3 class="text-themecolor">Dashboard</h3>
     </div>
     <div class="col-md-7 align-self-center">
         <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
             <li class="breadcrumb-item active">Dashboard</li>
         </ol>
     </div>
     {{-- <div>
         <button class="right-side-toggle waves-effect waves-light btn-inverse btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
     </div> --}}
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
             <div class="row">
                 <div class="col-md-4">
                     <select class="form-control form-control-sm" id="user_id" onchange="loadDataDashboard()">
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
         <div class="col-md-4">
            <div class="card card-inverse card-info">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="m-r-20 align-self-center">
                            <h1 class="text-white"><i class="ti-pie-chart"></i></h1></div>
                        <div>
                            <h4 class="card-title">Total Payment</h4>
                            <div class="card-subtitle form-material">
                               <input type="text" class="form-control" id="min-date" style="background-color: #0000ff00;color: white;" value="{{date('M Y')}}" readonly>
                            </div> 
                            <input type="hidden" id="timepicker" value="{{date('Y-m')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 align-self-center">
                            <h2 class="font-light text-white d-wo">
                              <span id="d-total_payment">Rp. 0</span>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="card card-inverse" style="background: #28a745">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="m-r-20 align-self-center">
                            <h1 class="text-white"><i class="ti-pie-chart"></i></h1></div>
                        <div>
                            <h4 class="card-title">Settlement</h4>
                            <div class="card-subtitle form-material">
                               <input type="text" class="form-control" id="min-date2" style="background-color: #0000ff00;color: white;" value="{{date('M Y')}}" readonly>
                            </div> 
                            <input type="hidden" id="timepicker2" value="{{date('Y-m')}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 align-self-center">
                            <h2 class="font-light text-white d-wo">
                              <span id="d-settlement">Rp. 0</span>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="card card-inverse card-danger" style="height: 165px;">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="m-r-20 align-self-center">
                            <h1 class="text-white"><i class="ti-pie-chart"></i></h1></div>
                        <div>
                            <h4 class="card-title">Callback Failure</h4>                   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 align-self-center">
                            <br>
                            <h1 class="font-light text-white" id="d-callback">
                             0
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-actions">
                      <a class="" data-action="collapse"><i class="ti-minus"></i></a>
                      <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                    </div>
                    <h4 class="card-title m-b-0">Grafik</h4>
                    <div class="col-md-12 mt-3">
                        <div class="row">
                          <div class="col-md-8">
                            <div class="row">
                              <div class="col-md-3">
                                <label style="margin: 0;padding-top: 10px;">Periode</label>
                              </div>
                              <div class="col-md-3 form-material">
                                <input type="text" class="form-control" id="min-date3" value="{{date('M Y')}}" style="padding: 0px 10px;" readonly>
                              </div>
                              <input type="hidden" id="timepicker3" value="{{date('Y-m')}}">
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="amp-pxl m-t-5" style="height: 210px;">
                            <canvas id="chart1" style="height: 210px!important;width: 100%;"></canvas>
                        </div>
                        <div>
                        <center>
                          <div style="background-color: #009efb;width: 50px;height: 10px;display: inline-block;"></div> Total Payment
                          <div style="background-color: #28a745;width: 50px;height: 10px;display: inline-block;"></div> Settlement
                        </center>
                      </div>
                    </div>
                </div>
             </div>
         </div>
     </div>
 </div>
 @endsection
 @section("javascript")
 <script src="{{url('/template/assets/plugins/Chart.js/Chart.min.js')}}"></script>
 <script src="{{url('/js/dashboard/index.js')}}"></script>
 @endsection

