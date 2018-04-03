$(document).ready(function(){
    
    $('.run-slide-panel').slidePanel({
        closeSelector:".slidePanel-close",
        template: function template(options) {
            return '<div class="' + options.classes.base + ' ' + options.classes.base + '-' + options.direction + '">\n                  <div class="' + options.classes.base + '-scrollable">\n                    <div><div class="' + options.classes.content + '"></div></div>\n                  </div>\n                  <div class="' + options.classes.base + '-handler"></div>\n                </div>';
        },
        afterLoad: function(options){
            $('.slidePanel-scrollable').asScrollable({
                namespace: 'scrollable',
                contentSelector: '>',
                containerSelector: '>'
            });

            $('*[data-plugin="dropify"]').dropify();

            generarSelected();
        }
    });

    $(document).on({'click': function(e){
            e.preventDefault();
            var idT = $(this).data('id');
            var formulario = $('#form-tarea-'+idT); 
            if(formulario.find('.file_tarea')){
                if($('#form-tarea-'+idT+" .file_tarea").val() == ""){
                    swal('Espera', 'Debes agregar un archivo', 'warning');
                    return false;
                }
            }
            formulario.submit();
        }
    },'.submit_tarea');


    $(document).on({'submit': function(e){
            e.preventDefault();
            
            var formData = new FormData(this);
            var boton = $(this).find('.submit_tarea');
            var l = Ladda.create(boton.get(0));
            l.start();
            $.ajax({
                type:'POST',
                url: $(this).attr('action'),
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success:function(data){
                    if(data.status=="success"){
                        swal("Perfecto", data.message, "success");
                        console.log(data.message);

                        if(data.result.url){
                            $('.js_descargar_archivo-'+data.result.idT+" .url_documento").html(data.result.url);
                            $(".dropify-clear").trigger("click");
                        }
                        
                        
                    }else{
                        swal("Espera", data.message, "error");
                    }
                    l.stop();
                },
                error: function(jqXHR, textStatus, errorThrown){
                    swal("Espera", "Ocurrio un problema: "+textStatus, "error");
                    l.stop();
                }
            });
        }
    }, '.formClass');
      
});

$(window).on('load', function() {
    var url = window.location.href;
    if(url.search("tokenLoc") > 0){
        var myParam = location.search.split('tokenLoc=')[1]
        //console.log("Url con token="+myParam);
        $("#js_ver_localidades_"+myParam).click();
    }
});

function generarSelected(){
    
    $("input, textarea").click(function() {
        $(this).focus();
      });
        $('.plugin-selective-tareas').each(function () {
            
            var elemento = $(this);
            var token = elemento.data("localidad");
            console.log($("#json-colaboradores-"+token).data("colaboradores"));
            //console.log(elemento.data('json'));
            elemento.selective({
              closeOnSelect: true , 
              namespace: 'addMember',
              selected: elemento.data('json'),
              local: $("#json-colaboradores-"+token).data("colaboradores"),
              onAfterSelected: function(e){
                  //alert(elemento.val());
              },
              onAfterItemAdd: function(e){
                var idTar = elemento.data('id');
                var idUser = elemento.val();
                $('*[data-tareakey=\"'+idTar+'\"] .addMember-trigger-button').hide();
                $.ajax({
                    url: baseUrl+'localidades/asignar-usuarios-tareas',
                    data: {idT: idTar, idU: idUser},
                    dataType: 'json',
                    type: 'POST',
                    success: function(resp){
                        if(resp.status == 'success'){
                            console.log('Asignacion de tarea correcta');
                        }
                    }
                });
              },
              onAfterItemRemove: function(e){
                var idTar = elemento.data('id');
                var idUser = elemento.val();
                if(idUser.length==0){
                    idUser = -1;
                }

                $('*[data-tareakey=\"'+idTar+'\"] .addMember-trigger-button').show();
                $.ajax({
                    url: baseUrl+'localidades/remover-asignacion-tarea',
                    data: {idT: idTar, idU: idUser},
                    dataType: 'json',
                    type: 'POST',
                    success: function(resp){
                        if(resp.status == 'success'){
                            console.log('Eliminacion correcta');
                        }
                    }
                });
              },
              buildFromHtml: false,
              tpl: {
                optionValue: function optionValue(data) {
                  return data.id;
                },
                frame: function frame() {
                  return '<div class=\"' + this.namespace + '\">                ' + this.options.tpl.items.call(this) + '                <div class=\"' + this.namespace + '-trigger\">                ' + this.options.tpl.triggerButton.call(this) + '                <div class=\"' + this.namespace + '-trigger-dropdown\">                ' + this.options.tpl.list.call(this) + '                </div>                </div>                </div>';
            
                  // i++;
                },
                triggerButton: function triggerButton() {
                    var isAsignado = 'block';
                    
                    if(elemento.data('json').length>0){
                        var isAsignado = 'none';
                    }
                  return '<div style=\"display:'+isAsignado+'\" class=\"' + this.namespace + '-trigger-button\"><i class=\"wb-plus\"></i></div>';
                },
                listItem: function listItem(data) {
                  return '<li class=\"' + this.namespace + '-list-item\"><img class=\"avatar\" src=\"' + data.avatar + '\">' + data.name + '</li>';
                },
                item: function item(data) {
                  return '<li class=\"' + this.namespace + '-item\"><img class=\"avatar\" src=\"' + data.avatar + '\" title=\"' + data.name + '\">' + this.options.tpl.itemRemove.call(this) + '</li>';
                },
                itemRemove: function itemRemove() {
                  return '<span class=\"' + this.namespace + '-remove\"><i class=\"wb-minus-circle\"></i></span>';
                },
                option: function option(data) {
                  return '<option value=\"' + this.options.tpl.optionValue.call(this, data) + '\">' + data.name + '</option>';
                }
              }
            });
        });
   
}

$(document).on({
    'click' : function(e) {
   
        var token = $(this).data("token");
        $("#wrktareas-id_localidad").val(token);
        
        $("#modal-crear-tarea").modal("show");
     
    }
   }, '.js-open-modal-tarea');

   $(document).on({
    'beforeSubmit' : function() {
        var l = Ladda.create(document.getElementById("js-btn-guardar-tarea"));
        l.start();
        
        var data = $("#form-guardar-tarea").serialize();
        $.ajax({
            url:baseUrl+"tareas/crear-tarea",
            data: data,
            method: "POST",
            success:function(r){
                if(r.status=="success"){
                    $("#modal-crear-tarea").modal("hide");
                    document.getElementById("form-guardar-tarea").reset();
                    //message();

                    $(".js-tareas-contenedor-"+$("#wrktareas-id_localidad").val()).append(r.result);
                    generarSelected();
                }else{
                    swal("Espera", "Ocurrio un problema", "error");
                }
                l.stop();
            },
            error:function (){
                swal("Espera", "Ocurrio un problema", "error");
                l.stop();
            }
        });
     return false;
    }
   }, '#form-guardar-tarea');

   function message(){
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

      toastr.success('Tarea guardada');
   }


$(document).on({'change': function(e){
    var elemento = $(this);
    var token = elemento.data("token");
    if(elemento.prop("checked")){
        completarTarea(token, elemento);
    }else{
        noCompletarTarea(token, elemento);
    } 
}
}, '.js-completar-tarea');

$(document).on({'click': function(e){
    e.preventDefault();
    var motivo = $('#motivo_archivar_localidad').val();
    var data = $('#js_archivar_localidad').data('url');
    console.log(data + "&mot=" + motivo);

    swal({
        title: "Confirmación",
        text: "¿Estas seguro de archivar esta localidad?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Sí, archivar",
        cancelButtonText: "No",
        closeOnConfirm: true,
    }, function(){
        $.ajax({
            url: baseUrl + data + "&mot=" + motivo,
            success:function(r){
                if(r.status=="success"){
                    $('tr[data-key="'+r.message+'"]').remove();
                }else{
                    swal("Espera", "Ocurrio un problema", "error");
                } 
            },
            error:function (){
                swal("Espera", "Ocurrio un problema", "error");
            }
        });
    });
}
}, '#js_aceptar_archivar');

function completarTarea(token, elemento){
    swal({
        title: "Confirmación",
        text: "¿Estas seguro de marcar esta tarea como completada?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Sí, esta completa",
        cancelButtonText: "No",
        closeOnConfirm: true,
        //closeOnCancel: false
    },
        function () {
            elemento.prop("checked", true);
            $.ajax({
                url:baseUrl+"tareas/completar-tarea?token="+token,
             
                success:function(r){
                    if(r.status=="success"){
                       
                    }else{
                        swal("Espera", "Ocurrio un problema", "error");
                    }
                    
                },
                error:function (){
                    swal("Espera", "Ocurrio un problema", "error");
                }
            });
        });
        elemento.prop("checked", false);
        return false;
}

function noCompletarTarea(token,  elemento){
    swal({
        title: "Confirmación",
        text: "¿Estas seguro de marcar esta tarea como incompleta?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: true,
        //closeOnCancel: false
    },
        function () {
            elemento.prop("checked", false);
            $.ajax({
                url:baseUrl+"tareas/descompletar-tarea?token="+token,
             
                success:function(r){
                    if(r.status=="success"){
                       
                    }else{
                        swal("Espera", "Ocurrio un problema", "error");
                    }
                    
                },
                error:function (){
                    swal("Espera", "Ocurrio un problema", "error");
                }
            });
        });

        elemento.prop("checked", true);
        return false;
}