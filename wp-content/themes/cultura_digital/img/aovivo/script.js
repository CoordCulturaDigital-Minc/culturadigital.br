sfHover = function() {
	var sfEls = document.getElementById("block-menu-primary-links").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);

	function retornarMes(mes) {
		if (mes=="01") {
			return "jan";
		} else if (mes=="02") {
			return "fev";
		} else if (mes=="03") {
			return "mar";
		} else if (mes=="04") {
			return "abr";
		} else if (mes=="05") { 
			return "mai";
		} else if (mes=="06") {
			return "jun";
		} else if (mes=="07") {
			return "jul";
		} else if (mes=="08") {
			return "ago";
		} else if (mes=="09") {
			return "set";
		} else if (mes=="10") {
			return "out";
		} else if (mes=="11") {
			return "nov";
		} else if (mes=="12") {
			return "dez";
		} else {
			return "";
		}
	}

function checkEmail(myForm){
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(myForm)) {
		return true;
	}
	else {
		return false;
	}
}

function inicializar() {
	
	$(".search-advanced").removeClass("collapsed");
	$(".search-advanced .fieldset-wrapper").css("display","block");;
	
	$("#edit-submitted-email").blur(function () {
		if (!checkEmail($(this).val())) {
			$(this).css("border","1px red solid");
			window.alert("Email inválido");
		} else {
			$(this).css("border","1px black solid");
		}
	});

	$("div").pngFix();

	$(".views-field-field-data-value .field-content .date-display-single").each(function () {
		dataOriginal = $(this).html().split("/");
		mes = retornarMes(dataOriginal[1]);
		$(this).html(dataOriginal[0] + mes);
	});


	if ($("#edit-name").val()=="Visitante") {
		$("#edit-name").val("");
	}

	
	function textLabel(texto, item, cor, cor2) {
		txtText = $(item);

		txtText.val(texto);
		txtText.css("color", cor);

		txtText.click(function() {
			if ($(this).val()==texto) {
				$(this).val("");
				$(this).css("color", cor2);
			}
		});

		txtText.blur(function () {
			if ($(this).val()=="") {
				$(this).val(texto);
				$(this).css("color",cor);
			}
		});
	}
	
	$("#views-exposed-form-ListaDeEventos-page-1 select[name=tid] option:first").html("Todos");
	if ($.browser.msie) {
		$("#views-exposed-form-ListaDeEventos-page-1 .views-exposed-widget:first select").css("top","-15px");
		$("#views-exposed-form-ListaDeEventos-page-1 .views-exposed-widget:first select").css("position","relative");
	}

	$("#views-exposed-form-ListaDeEventos-page-1 #edit-submit").css("position","relative");
	$("#views-exposed-form-ListaDeEventos-page-1 #edit-submit").css("left","-40px");
	$("#views-exposed-form-ListaDeEventos-page-1 #edit-submit").css("top","-5px");

	textLabel("buscar...", "#search-block-form #edit-search-block-form-1-wrapper #edit-search-block-form-1", "#bbb", "#000");

	textLabel("digite seu e-mail...", "#block-block-8 .txtEmail", "#bbb", "#000");


	if(($.browser.msie==true && $.browser.version>=7) || $.browser.msie!=true) {

			$(".imgpb").mouseover(function () {
				$(this).parent().children(".imgnormal").fadeIn("slow");
			});

			$(".imgnormal").mouseout(function () {
				$(this).fadeOut("slow");
			});

			$(".view-homeCalendario .views-row-odd .views-field-field-imagem-fid").mouseover(function () {
				$(this).parent().children(".views-field-field-imagem-fid-1").fadeIn("slow");
			});

			$(".view-homeCalendario .views-row-even .views-field-field-imagem-fid").mouseover(function () {
				$(this).parent().children(".views-field-field-imagem-fid-1").fadeIn("slow");
			});

			$(".views-field-field-imagem-fid-1").mouseout(function () {
				$(this).fadeOut("slow");
			});

			$('#formcalendario form select').combobox({
				comboboxContainerClass: "comboboxContainer",
				comboboxValueContainerClass: "comboboxValueContainer",
				comboboxValueContentClass: "comboboxValueContent",
				comboboxDropDownClass: "comboboxDropDownContainer",
				comboboxDropDownButtonClass: "comboboxDropDownButton",
				comboboxDropDownItemClass: "comboboxItem",
				comboboxDropDownItemHoverClass: "comboboxItemHover",
				comboboxDropDownGroupItemHeaderClass: "comboboxGroupItemHeader",
				comboboxDropDownGroupItemContainerClass: "comboboxGroupItemContainer",
				animationType: "slide",
				width: "100px"
			});

	} else {

		if ($("#main-inner").css("height").replace("px", "") < 580) {
			$("#main-inner").css("height", "580px");
		}
		
		$(".node-type-especial").css("left","10px");
	}

	$(".mes, .ano, .termos").click(function () {
		$(".mes .comboboxDropDownContainer, .ano .comboboxDropDownContainer, .termos .comboboxDropDownContainer").css("left","0px");
	});


	$('.ajaxcal a').click(function () {
		tb_show("Eventos do dia", $(this).attr("href"), "evento");
		return false;
	});

	$('a.lightbox').click(function () {
		tb_show("", $(this).attr("href"), "imagem");
		return false;
	});

	$("#block-block-8 form").submit(function() {
		email = $(this).children(".txtEmail").attr("value");
		window.open("http://www.cpflcultura.com.br/listas?p=subscribe&email=" + encodeURI(email), "news", "width=500,height=500,scrollbars=1,status=1");
		return false;
	});

}