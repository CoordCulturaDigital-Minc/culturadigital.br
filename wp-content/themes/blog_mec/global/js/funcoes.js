// JavaScript Document

function aumentarFonte(id){
	document.getElementById(id).style.fontSize='15px';
}
function diminuirFonte(id){
	document.getElementById(id).style.fontSize='12px';
}
function nova_janela(obj) {
   if (obj.value!='0'){
      window.open(obj.value);
   }
}

jQuery(function() {
  jQuery('.gallery #box_post ul.gallery li a').lightBox();
});