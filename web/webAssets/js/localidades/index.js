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
            }
        }); 
      
});


$(document).on({
    'click' : function(e) {
   
     e.preventDefault();
     var elemento = $(this);
     var token = elemento.data('token');
     var padre = elemento.parents('.obras-escritas-text-cont') 
     padre.css('display', 'none');
     elemento.addClass('pendiente-eliminar');
     
     
     eliminarElemento(token, elemento);
     
    }
   }, '.obras-escritas-texto-delete');