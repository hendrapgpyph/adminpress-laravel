var pageTotal = 0;
var pageAktif = 0;
var pageCount = 0;
var jumlahSeluruh = 0;
var indexContent = 0;

$(document).ready(function(){
    loadListStaff();
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
          pageCount = 0;
          pageAktif = 0;
          loadListStaff();
        }
    });
    function pageGanti(index){
      pageAktif = index;
      pageCount = index;
      loadListStaff(index);
    }

    function changePage(e){        
        pageGanti(parseInt($(e).val()));
    }


    function pervPage(){
    if (pageCount != 0) {
      pageAktif--;
      pageCount = pageCount-1;
      loadListStaff();
    }
  }

  function nextPage(){
    if (indexContent != jumlahSeluruh) {
      pageAktif++;
      pageCount = pageCount+1;
      loadListStaff();
    }
  }
  function pageIndex(index){
      pageAktif = index;
      pageCount = index;
      loadListStaff();
    }
  function cariPeserta(){
    pageAktif = 0;
    pageCount = 0;
    loadListStaff();
 } 

  function pageShow(index){
    pageAktif = index;
    pageCount = index;
    loadListStaff();
  }


  function loadListStaff(){
    var txt ="";
    page      = pageCount;
    var no    = (parseInt(page)*20)+1;
    var awal  = no;
    $.getJSON(link+'/users/jsonListStaff?page='+page,function(data){      
      jumlahSeluruh = data[0];
      pageTotal = parseInt(Math.ceil(jumlahSeluruh/20));
      $.each(data[1], function(key, val){
        txt += `<tr>
                <td>${key+1}</td>
                <td>Hendra Setiawan</td>
                <td>085737779404</td>
                <td>admin</td>
                <td>
                  <a style="padding: 5px 10px" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                   <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                      <a class="dropdown-item" href="#">Dropdown link</a>
                      <a class="dropdown-item" href="#">Dropdown link</a>
                  </div>
                </td>
              </tr>`;
        indexContent = key+1;
      });
    }).done(function(){
      $('#displayPage').html((awal)+'-'+indexContent+'/'+jumlahSeluruh);
      $('#displayPage2').html((awal)+'-'+indexContent+'/'+jumlahSeluruh);
      $('#tbodyListStaff').html(txt);
      setPaging(5,2);
      if (jumlahSeluruh == 0) {
        $('#tbodyListStaff').html("<td colspan='9'><center>Data tidak ditemukan</center></td>");
      }
    });
  }