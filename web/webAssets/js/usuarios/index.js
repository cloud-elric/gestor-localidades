
$(document).on({
    'change': function(){
        
        var elemento = $(this);
        var token = $(this).data("token");
        $.ajax({
            url:baseUrl+"usuarios/activar-usuario?token="+token,
            success: function(r){
                if(r.status=="success"){
                    
                }else{
                    swal("Problema al guardar","No se pudo activar al usuario: "+ r.message, "error");
                }
            },
            error:function(x,y,z){
                swal("Problema al guardar","No se pudo activar al usuario: "+ y, "error");
            }
            
        });
    }
}, ".btn-active:not(.active)");

$(document).on({
    'click': function(){
        
        var elemento = $(this);
        var token = $(this).data("token");
        $.ajax({
            url:baseUrl+"usuarios/bloquear-usuario?token="+token,
            success: function(r){
                if(r.status=="success"){
                    
                }else{
                    swal("Problema al guardar","No se pudo bloquear al usuario: "+ r.message, "error");
                }
            },
            error:function(x,y,z){
                swal("Problema al guardar","No se pudo bloquear al usuario: "+ y, "error");
            }
            
        });
    }
}, ".btn-inactive:not(.active)");