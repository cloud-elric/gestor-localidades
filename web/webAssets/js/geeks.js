
$(document).ready(function () {

    //   // Cargador en todos los botones con la clase ladda
    $("#form-ajax .ladda-button").on("click", function (e) {
        var l = Ladda.create(this);
        l.start();
        $('#form-ajax').submit();
        //   //   // Para deternerlo usar
        //   //   // var l = Ladda.create(this);
        //   //   // l.stop();
    });

    //Ladda.bind( '.ladda-button' );
    setTimeout(() => {
        if ($("body").css('opacity') == '0') {
            $("body").animsition('in');
        }
    }, 800);


    $('#form-ajax').on('ajaxComplete', function (e, jqXHR, textStatus) {
        var l = Ladda.create($("#form-ajax button[type=submit]").get(0));
        l.stop();
        return true;
    });

    $(".input-number").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });



});

function showToastr(mensaje, tipo) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-full-width",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    if (tipo == "success") {
        toastr.success(mensaje);
    }

    if (tipo == "info") {
        toastr.info(mensaje);
    }

    if (tipo == "warning") {
        toastr.warning(mensaje);
    }

    if (tipo == "error") {
        toastr.error(mensaje);
    }

}

function resetForm(form) {
    // clearing inputs
    var inputs = form.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        switch (inputs[i].type) {
            // case 'hidden':
            case 'text':
                $(inputs[i]).val("");
                break;
            case 'radio':
            case 'checkbox':
                inputs[i].checked = false;
        }
    }

    // clearing selects
    var selects = form.getElementsByTagName('select');
    for (var i = 0; i < selects.length; i++)
        selects[i].selectedIndex = 0;

    // clearing textarea
    var text = form.getElementsByTagName('textarea');
    for (var i = 0; i < text.length; i++)
        text[i].innerHTML = '';

    return false;
}


// Lanza la animación siempre que se cambie las pantallas
window.onbeforeunload = function (e) {
    console.log(e);
    $('.animsition').animsition('out', $('.animsition'), '');
}

