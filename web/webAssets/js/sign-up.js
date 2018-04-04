var inputFile = $("#entusuarios-image");
var tamanioAdmitido = 3;
var tipoImagenesAdmitidas = ["image/jpeg", "image/png", "image/jpg", "image/gif"];
$(document).ready(function () {
    $(".js-img-avatar").on("click", function (e) {
        e.preventDefault();
        inputFile.trigger("click");
    });

    inputFile.on("change", function (e) {
        var file = this.files[0];
        if (!validarTipoImagen(file))
            return false

        if (!validarTamanioImagen(file))
            return false
            
        colocarImagen(file);

    });

    $('#entusuarios-txt_auth_item').on('change',function(e){
        if($(this).val() == 'usuario-cliente'){
            //console.log('Usuario colaborador');
            $("#select_clientes").css('display', 'block');
        }else{
            $("#select_clientes").css('display', 'none');            
            //console.log('No');            
        }
    });
});

function validarTipoImagen(jsfile) {
    var file = jsfile;
    var imagefile = file.type;
    var match = tipoImagenesAdmitidas;

    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]) || (imagefile == match[3]))) {
        swal("Espera", "Solo se admiten archivos con extensión .jpeg, .jpg, .png, .gif", "warning");
        return false;
    }

    return true;
}

function validarTamanioImagen(jsfile) {
    var file = jsfile;
    sizeImage = (file.size) / 1048576;

    if (sizeImage > tamanioAdmitido) {
        swal("Espera", "Tu imagen debe ser menor a " + tamanioAdmitido + "MB", "warning");
        return false;
    }
    return true;
}

function colocarImagen(jsfile) {
    var file = jsfile;
    var reader = new FileReader();

    // Set preview image into the popover data-content
    reader.onload = function (e) {
        console.log('colocar imagenbb');

<<<<<<< HEAD
        $('.js-image-preview').on("load", function(){
=======
        $('.js-image-preview').on("load", function () {
>>>>>>> dev

        }).attr('src', e.target.result);
    }
    reader.readAsDataURL(file);
}