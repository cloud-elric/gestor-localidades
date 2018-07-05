// $(document).on({
// 	'change' : function(e) {
// 		var _URL = window.URL || window.webkitURL;
// 		var file, img;
		
// 		console.log(this.files[0]);
// 		if (file = this.files[0]) {
			
// 			img = new Image();
// 			img.onload = function() {
// 				//alert(this.width + " " + this.height);
// 				if(this.width < 1000 && this.height < 1000){
// 					console.log("tu imagen ha sido cargada correctamente");
// 					swal("Imagen correcta!"," ","success")
// 				}else{
// 					console.log("error imagen no tiene el tamaño permitido");
// 					swal("Error al cargar imagen!", "Debe ser menor de 1000px x 1000px", 'warning');
// 					fileopen.parentNode.replaceChild(clone, fileopen);
// 				}
// 			};
// 			img.onerror = function() {
// 				//alert( "not a valid file: " + file.type);
// 	        };
// 	        img.src = _URL.createObjectURL(file);
// 		}else{
// 			console.log("error");
// 		}
// 	}	
// },'#entusuarios-image');  


