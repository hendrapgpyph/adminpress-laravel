 @extends('layouts.appweb')
  @section('title')
 CRO App | Users
 @endsection
 @section("content")
 <!-- ============================================================== -->
  <!-- Bread crumb and right sidebar toggle -->
  <!-- ============================================================== -->
  <div class="row page-titles">
      <div class="col-md-5 align-self-center">
          <h3 class="text-themecolor">Users</h3>
      </div>
      <div class="col-md-7 align-self-center">
          <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
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
            <h4 class="card-title m-b-0">List Users</h4></div>
          <div class="card-body collapse show">
            <div class="row">
              @if(Auth::user()->role == "Admin")
                <div class="col-12">
                  <div class="btn-group float-right">
                    <a href="{{url('/users/form')}}" class="btn btn-sm btn-info">
                    <i class="fa fa-plus"></i> Tambah Users
                    </a>
                  </div>
                </div>
              @endif
              <div class="col-md-12">
                <br>
              </div>
              <div class="col-md-12 tableStaff">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-8">
                        <div class="input-group">
                          <input type="text" class="form-control form-control-sm" placeholder="Cari users..." id="cariStaff">
                          <div class="input-group-append">
                            <button type="button" class="btn btn-info btn-sm" onclick="cariStaff()"><i class="fa fa-search"></i></button>
                          </div>
                        </div>
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
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody id="tbodyListStaff">
                          
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
  <script src="{{url('js/users/index.js')}}"></script>
  @endsection