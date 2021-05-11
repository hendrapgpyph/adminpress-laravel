var pageTotal = 0;
var pageAktif = 0;
var jumlahSeluruh = 0;
var indexContent = 0;

$(document).ready(function(){
    loadUsers();
});

function setPaging(show,between){
      // show : itu buat nampilin di awal atau akhirnya mau tampil berapa
      // contoh : show = 5 ( 1,2,3,4,5,...,20 ) atau (1,...,16,17,18,19,20)
      // between : untuk ngasik tiap kiri dan kanan berapa index kalau ditengah
      // contoh : between = 2 (1,...,6,7,[8],9,10,...,20)
      between = parseInt(between+1);
      var txt = "";
      txt += `<div class="btn-group">`;
      txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pervPage();'><i class='fa fa-chevron-left'></i></button>";
      if (pageAktif<(parseInt(show)-1) || pageAktif == (parseInt(show)-1) && pageTotal<=pageAktif) {
        for (var i = 0; i < (pageTotal); i++) {
            if (i<parseInt(show)) {
              txt = txt+"<button type='button' class='btn ";
              if (i == pageAktif) {
                txt = txt+"btn-info ";
              }else{
                txt = txt+"btn-secondary ";
              }
              txt = txt+"btn-sm' onclick='pageGanti("+i+")'>"+(i+1)+"</button>";
            }
        }
        if (pageTotal>parseInt(show)) {
          txt = txt+"<button type='button' class='btn btn-secondary btn-sm'>...</button>";
          txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti("+(pageTotal-1)+")'>"+(pageTotal)+"</button>";
        }
      }else if(pageAktif - ((pageTotal)-parseInt(show))>0){
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti(0)'>1</button>";
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        for (var i = 0; i < (pageTotal); i++) {
            if (i>((pageTotal-1)-parseInt(show))) {
              txt = txt+"<button type='button' class='btn ";
              if (i == pageAktif) {
                txt = txt+"btn-info ";
              }else{
                txt = txt+"btn-secondary ";
              }
              txt = txt+"btn-sm' onclick='pageGanti("+i+")'>"+(i+1)+"</button>";
            }
        }
      }else{
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti(0)'>1</button>";
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        for (var i = 0; i < (pageTotal); i++) {
            if (i>(pageAktif-between) && i<(pageAktif+between)) {
              txt = txt+"<button type='button' class='btn ";
              if (i == pageAktif) {
                txt = txt+"btn-info ";
              }else{
                txt = txt+"btn-secondary ";
              }
              txt = txt+"btn-sm' onclick='pageGanti("+i+")'>"+(i+1)+"</button>";
            }
        }
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm'>...</button>";
        txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='pageGanti("+(pageTotal-1)+")'>"+(pageTotal)+"</button>";
      }

      txt = txt+"<button type='button' class='btn btn-secondary btn-sm' onclick='nextPage();'><i class='fa fa-chevron-right'></i></button>";
      txt += `</div>`;
      txt = txt+"<select class='select2 jumpPage form-control form-control-sm' onchange='changePage(this)' style='width:60px;'>";
      for(var j = 0;j<pageTotal;j++){
        var selectedPage = '';
        if (j == pageAktif) {
          selectedPage = 'selected';
        }
        txt = txt+"<option value='"+j+"' "+selectedPage+">"+(j+1)+"</option>";
      }
      txt = txt+"</select>";
      $('#btnPaging1').html(txt);
      $('#btnPaging2').html(txt);
      $('.select2').select2();
    }

    $(document).keypress(function(e) {
        if(e.which == 13) {
          // $('#buttonSearchnya').focus();
          pageAktif = 0;
          loadUsers();
        }
    });
    function pageGanti(index){
      pageAktif = index;
      loadUsers(index);
    }

    function changePage(e){
        pageGanti(parseInt($(e).val()));
    }


    function pervPage(){
    if (pageAktif != 0) {
      pageAktif--;
      loadUsers();
    }
  }

  function nextPage(){
    if (indexContent != jumlahSeluruh) {
      pageAktif++;
      loadUsers();
    }
  }
  function pageIndex(index){
      pageAktif = index;
      loadUsers();
    }
  function cariStaff(){
	 	pageAktif = 0;
	  loadUsers();
 }

  function pageShow(index){
	  pageAktif = index;
	  loadUsers();
	}

// staff
  function loadUsers(){
    $('.tableStaff').loading('toggle');
    var txt ="";
    page      = pageAktif + 1;
    var no    = (parseInt(pageAktif)*20)+1;
    var awal  = no;
    var cari = $('#cariStaff').val();
    $.getJSON(link+'/users/jsonListStaff?page='+page+'&cari='+cari,function(data){
    // console.log(data)
      jumlahSeluruh = data.total;
      pageTotal = parseInt(Math.ceil(jumlahSeluruh/20));
      $.each(data.data, function(key, val){

        
        txt += `<tr>
                  <td align="left">${no}</td>
                  <td align="left"><a href="${link+'/users/form?id='+val.id}">${val.name}</a></td>
                  <td align="left">${val.telepon}</td>
                    <td align="left">
                        <a href="${link+'/users/form?id='+val.id}" class="text-primary" data-placement="top" data-toggle="tooltip" title="Edit">
                          <i class="ti-marker-alt"></i>
                        </a>
                        <a href="#!" onclick="deleteStaff(${val.id})" class="text-danger" data-placement="top" title="Delete" data-toggle="tooltip">
                          <i class="ti-trash"></i>
                        </a>
                      </td>
                </tr>
                `;
        indexContent = no;
        no++;
      });
    }).done(function(){
      $('#displayPage').html((awal)+'-'+indexContent+'/'+jumlahSeluruh);
      $('#displayPage2').html((awal)+'-'+indexContent+'/'+jumlahSeluruh);
      $('#tbodyListStaff').html(txt);
      $('.tableStaff').loading('toggle');
      // $('[data-toggle="tooltip"]').tooltip();
      setPaging(5,2);
      if (jumlahSeluruh == 0) {
        $('#tbodyListStaff').html("<td colspan='4'><center>Data tidak ditemukan</center></td>");
      }
    });
  }

  deleteStaff = (id) =>{
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
            $.getJSON(link+"/users/hapusStaff?id="+id, function(data){
               pageAktif = 0;
                Swal.fire(
                  'Proses berhasil',
                  '',
                  'success'
                )
               loadUsers();
            });
          
        }
      });
  }
