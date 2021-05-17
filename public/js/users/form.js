$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
//

$('#formStaff').submit(function (e) {
    e.preventDefault();
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
            var formData = new FormData(this);
            $('#formStaff .inputFoto').each(function () {
                console.log(gambarUploaded[$(this).attr('name')]);
                if (typeof gambarUploaded[$(this).attr('name')] != 'undefined') {
                    formData.set($(this).attr('name'), gambarUploaded[$(this).attr('name')]);
                }
            });
            var linkProses = $(this).attr('action');
            $.ajax({
                url: linkProses,
                type: 'POST',
                data: formData,
                success: function (data) {
                    Swal.fire(
                        'Proses berhasil',
                        '',
                        'success'
                    )
                },
                error: function (xhr, textStatus, error) {
                    // var w = window.open();
                    // var html = xhr.responseText;
                    // $(w.document.body).html(html);
                    Swal.fire(
                        'Proses gagal',
                        '',
                        'error'
                    );
                },
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
            }).done(function () {
              // if (roleUser == "Admin") {
              //   window.location.href = link + "/users";                
              // }else{
                location.reload();
              // }
            });
        }
    });
});

function resetToken(){
    Swal.fire({
        title: 'Reset API Token?',
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
            let link = $("meta[name=baseurl]").attr('content');
            let token = $("meta[name='csrf-token']").attr('content');
            $.post(link+"/users/reset_token",{
                id : $("input[name='id']").val(),
                _token : token
            })
            .done(function(response){
                Swal.fire(
                    'Reset Token berhasil',
                    '',
                    'success'
                );
                $("#api_token").val(response.api_token);
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