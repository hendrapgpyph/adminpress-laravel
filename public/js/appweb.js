var gambarUploaded = [];
$('.inputFoto').change(function(e){
  var input = this;
  setInputFoto(input);  
});

function setInputFoto(input)
{
  if (input.files && input.files[0]) {     
    var cekFile = checkFile(input);
    if (cekFile) {
      var type = input.files[0].type;
      var type_reg = /^image\/(jpg|png|jpeg)$/;
      if (!(type_reg.test(type))) {
            swal.fire(
              'Format gambar tidak valid!',
              '(Masukkan gambar dengan tipe : .jpg,.jpeg atau .png)',
              'warning'
            );
        $(input).val("");
        gambarUploaded[$(input).attr('name')] = null; 
      }else{
         Swal.fire({
          title: 'Sedang memproses gambar..',
          html: '',
          allowOutsideClick: false,
          onOpen: () => {
            swal.showLoading()
          }
        });
        var reader = new FileReader();
        reader.onload = function (readerEvent) {
              var image = new Image();
              image.onload = function (imageEvent) {

                  // Resize the image
                  var canvas = document.createElement('canvas'),
                      max_size = 544,// TODO : pull max size from a site config
                      width = image.width,
                      height = image.height;
                  if (width > height) {
                      if (width > max_size) {
                          height *= max_size / width;
                          width = max_size;
                      }
                  } else {
                      if (height > max_size) {
                          width *= max_size / height;
                          height = max_size;
                      }
                  }
                  canvas.width = width;
                  canvas.height = height;
                  canvas.getContext('2d').drawImage(image, 0, 0, width, height);
                  var dataUrl = canvas.toDataURL('image/jpeg');
                  var resizedImage = dataURLToBlob(dataUrl);
                  gambarUploaded[$(input).attr('name')] = null;
                  gambarUploaded[$(input).attr('name')] = resizedImage;
                  $('.'+$(input).attr('target')).find('a').attr('href', dataUrl);
                  $('.'+$(input).attr('target')).show();
                  swal.close();
              }
              image.src = readerEvent.target.result;
          }
        reader.readAsDataURL(input.files[0]);        
      }
    }else{
      $(input).val("");
      gambarUploaded[$(input).attr('name')] = null;
      swal.fire("Maksimal upload 50Mb!","","warning");
    }
  }else{
    $('.'+$(input).attr('target')).hide();
  }  
}

function setInputFoto2(urlPhoto,name)
{
   var image = new Image();
   image.crossOrigin = "*";
   image.src = urlPhoto;
   image.onload = function () {
      // Resize the image
      let canvas = document.createElement('canvas');
      canvas.width = image.width;
      canvas.height = image.height;
      canvas.getContext('2d').drawImage(image, 0, 0);      
      let dataUrl = canvas.toDataURL('image/jpeg');
      let resizedImage = dataURLToBlob(dataUrl);
      gambarUploaded[name] = null;
      gambarUploaded[name] = resizedImage;
      $('.'+$('input[name="'+name+'"]').attr('target')).find('a').attr('href', urlPhoto);
      $('.'+$('input[name="'+name+'"]').attr('target')).show();      
    }
}

var dataURLToBlob = function(dataURL) {
      var BASE64_MARKER = ';base64,';
      if (dataURL.indexOf(BASE64_MARKER) == -1) {
          var parts = dataURL.split(',');
          var contentType = parts[0].split(':')[1];
          var raw = parts[1];

          return new Blob([raw], {type: contentType});
      }

      var parts = dataURL.split(BASE64_MARKER);
      var contentType = parts[0].split(':')[1];
      var raw = window.atob(parts[1]);
      var rawLength = raw.length;

      var uInt8Array = new Uint8Array(rawLength);

      for (var i = 0; i < rawLength; ++i) {
          uInt8Array[i] = raw.charCodeAt(i);
      }

      return new Blob([uInt8Array], {type: contentType});
  }

checkFile = (e) => {
    var fileSize = byteToMB(e.files[0].size)
    if(fileSize <= 50) {
      return true;
    } 
    return false;
}

byteToMB = (byte) => {
  return byte / 1024 / 1024;
}

function number_format(number, decimals, decPoint, thousandsSep) { 
   number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
   var n = !isFinite(+number) ? 0 : +number
   var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
   var sep = (typeof thousandsSep === 'undefined') ? '.' : thousandsSep
   var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
   var s = ''

   var toFixedFix = function (n, prec) {
    var k = Math.pow(10, prec)
    return '' + (Math.round(n * k) / k)
      .toFixed(prec)
   }

   // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
   s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
   if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
   }
   if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
   }

   return s.join(dec);
}