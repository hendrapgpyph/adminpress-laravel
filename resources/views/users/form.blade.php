 @extends('layouts.appweb')
  @section('title')
 BRIVA | Users
 @endsection
 @section("content")
 <style type="text/css">
   .select2-container--default .select2-selection--multiple {      
      padding: 0px 10px;
  }
 </style>
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
              <li class="breadcrumb-item active">Form</li>
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
        <div class="card">
          <div class="card-header">
            <h4 class="m-b-0">Form Users</h4></div>
          <div class="card-body">
            <form action="{{url('/users/processForm')}}" id="formStaff" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="{{isset($data->id)?$data->id:''}}">
              @csrf
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Masukkan nama..." value="{{isset($data->name)?$data->name:''}}" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" name="telepon" class="form-control form-control-sm" placeholder="Masukkan Telepon..." value="{{isset($data->telepon)?$data->telepon:''}}" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email</label>
                    <div class="controls">
                      <input type="email" name="email" class="form-control form-control-sm" placeholder="Masukkan email..." value="{{isset($data->email)?$data->email:''}}" data-validation-required-message="This field is required" required>
                    </div> 
                  </div>               
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Foto</label>
                    <div class="input-group">
                      <input type="file" target="fotoProfile_preview" name="fotoProfile" accept="image/*" class="form-control form-control-sm inputFoto">
                       <div class="input-group-append fotoProfile_preview" style="display: {{isset($data->foto) && $data->foto != null?'':'none'}};">
                          <a href="{{isset($data->foto)?url('/uploadgambar/'.$data->foto):'#!'}}" class="btn btn-primary btn-sm fancybox"><i class="fa fa-image py-2"></i></a>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <hr>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Password</label>
                    <div class="controls">
                      <input type="password" name="password" class="form-control form-control-sm" {{isset($data->id)?'':'required'}}>
                    </div>
                  </div>                
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Ulangi Password</label>
                    <div class="controls">
                      <input type="password" name="password2" data-validation-match-match="password" class="form-control form-control-sm" {{isset($data->id)?'':'required'}}>
                    </div>
                  </div>
                </div>              
                <div class="col-md-12">
                  <hr>
                </div>
                <div class="col-md-12">
                  <div class="float-right">
                    <div class="btn-group">
                      <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                    <div class="btn-group">
                      <a href="{{url('/users')}}" class="btn btn-danger">Batal</a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
  @section("javascript")
  <script src="{{url('/js/users/form.js')}}"></script>
  @endsection