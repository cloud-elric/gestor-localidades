$(document).ready(function(){
    
        $('.run-slide-panel').slidePanel({
            closeSelector:".slidePanel-will-close",
            template: function template(options) {
                return '<div class="' + options.classes.base + ' ' + options.classes.base + '-' + options.direction + '">\n                  <div class="' + options.classes.base + '-scrollable">\n                    <div><div class="' + options.classes.content + '"></div></div>\n                  </div>\n                  <div class="' + options.classes.base + '-handler"></div>\n                </div>';
            },
            afterLoad: function(options){
                $('.slidePanel-scrollable').asScrollable({
                    namespace: 'scrollable',
                    contentSelector: '>',
                    containerSelector: '>'
                });
            }
        }); 
      
});