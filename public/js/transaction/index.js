var pageTotal = 0;
var pageAktif = 0;
var jumlahSeluruh = 0;
var indexContent = 0;

$(document).ready(function(){
    loadTransaction();
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
          loadTransaction();
        }
    });
    function pageGanti(index){
      pageAktif = index;
      loadTransaction(index);
    }

    function changePage(e){
        pageGanti(parseInt($(e).val()));
    }


    function pervPage(){
    if (pageAktif != 0) {
      pageAktif--;
      loadTransaction();
    }
  }

  function nextPage(){
    if (indexContent != jumlahSeluruh) {
      pageAktif++;
      loadTransaction();
    }
  }
  function pageIndex(index){
      pageAktif = index;
      loadTransaction();
    }
  function cariTransaksi(){
	 	pageAktif = 0;
	  loadTransaction();
 }

  function pageShow(index){
	  pageAktif = index;
	  loadTransaction();
	}

// staff
  function loadTransaction(){
    $('.tableTransaksi').loading('toggle');
    var txt ="";
    page      = pageAktif + 1;
    var no    = (parseInt(pageAktif)*20)+1;
    var awal  = no;
    var cari = $('#cariTransaksi').val();
    let range = $("#daterange").val();
    let status = $("#status").val();
    let status_callback = $("#status_callback").val();
    let user = $("#user_id").val();

    $.getJSON(link+'/transaction/jsonListTransaksi?page='+page+'&cari='+cari+"&range="+range+"&status="+status+"&status_callback="+status_callback+"&user="+user,function(data){
        jumlahSeluruh = data.total;
        pageTotal = parseInt(Math.ceil(jumlahSeluruh/20));
        $.each(data.data, function(key, val){
            txt += `<tr>
                      <td>${no}</td>
                      <td>
                        <a href="${link+'/transaction/detail/'+val.id}">${val.brivaNo+"-"+val.custCode}</a>`;
              if($("#user_id").val() == null || $("#user_id").val() == ""){
                txt += `<br>${val.nama_user}`;
              }
                txt += `<br><i style="font-size: 10px;">${val.created_at}</i>
                      </td>`;
            txt += `<td>
                        <a href="${link+'/transaction/detail/'+val.id}">${val.nama}</a><br>
                          <i style="font-size : 10px;">${val.keterangan}</i>
                      </td>
                      <td>
                        Rp. ${number_format(val.amount)}
                      </td>
                      <td>
                        ${val.expiredDate}
                      </td>
                      <td align="center">`;
            if(val.expired == 1){
              txt += `<a href="#!" class="badge badge-danger">Expired</a>`;
            }else if(val.statusBayar == 'Y'){
              txt += `<a href="#!" class="badge badge-success">Settlement</a>`;
            }else if(val.statusBayar == 'N'){
              txt += `<a href="#!" class="badge badge-warning">Pending</a>`;
            }
            txt += `</td>
                    <td align="center">`;
            if(val.status_callback == 'success'){
              txt += `<a href="#!" class="badge badge-success">Success</a>`;
            }else if(val.status_callback == 'fail'){
              txt += `<a href="#!" class="badge badge-danger">Fail</a>`;
            }else{
              txt += `-`;
            }
            txt += `</td>
                    </tr>`;
            indexContent = no;
            no++;
      });
    }).done(function(){
      $('#displayPage').html((awal)+'-'+indexContent+'/'+jumlahSeluruh);
      $('#displayPage2').html((awal)+'-'+indexContent+'/'+jumlahSeluruh);
      $('#tbodyListTransaksi').html(txt);
      $('.tableTransaksi').loading('toggle');
      // $('[data-toggle="tooltip"]').tooltip();
      setPaging(5,2);
      if (jumlahSeluruh == 0) {
        $('#tbodyListTransaksi').html("<td colspan='8'><center>Data tidak ditemukan</center></td>");
      }
    });
  }

  // Daterange picker
  $('.input-daterange-datepicker').daterangepicker({
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-danger',
        cancelClass: 'btn-inverse',
        autoUpdateInput: false
    },function(date_1, date_2) {
        $('input[name="daterange"]').val(date_1.format('YYYY/MM/DD')+" - "+date_2.format('YYYY/MM/DD'));
        cariTransaksi();
});


function changeUser(){
  cariTransaksi();
}