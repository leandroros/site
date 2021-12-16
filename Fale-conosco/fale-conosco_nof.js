// Begin XHTML adjustment
$(document).ready(function(){
	if (jQuery.browser.msie && jQuery.browser.version.substr(0, 2) == "6.") {
		$(".nof-clearfix").each(function (i) {
			$(this).append("<div style='clear:both'/>");
			$(this).removeClass("nof-clearfix");
		});
	}
});

// End XHTML adjustment

// Begin Navigation Bars
var ButtonsImageMapping = [];
ButtonsImageMapping["NavigationBar1"] = {
	"Bot�odenavega��o1" : { image: "../Home_Np_regular.png", rollover: "../Home_NRp_regularOver.png", w: 120, h: 57 },
	"Bot�odenavega��o2" : { image: "../Sobre_Np_regular.png", rollover: "../Sobre_NRp_regularOver.png", w: 120, h: 57, opening: "bottom", offsetX: 0, offsetY: 57 },
	"Bot�odenavega��o6" : { image: "../Depoimentos_Ns_regular.png", rollover: "../Depoimentos_NRs_regularOver.png", w: 120, h: 46 },
	"Bot�odenavega��o7" : { image: "../Servi-os_Ns_regular.png", rollover: "../Servi-os_NRs_regularOver.png", w: 120, h: 46 },
	"Bot�odenavega��o3" : { image: "../Fale-conosco_Hp_highlighted.png", rollover: "../Fale-conosco_HRp_highlightedOver.png", w: 120, h: 57, opening: "bottom", offsetX: 0, offsetY: 57 },
	"Bot�odenavega��o8" : { image: "../Localiza--o_Ns_regular.png", rollover: "../Localiza--o_NRs_regularOver.png", w: 120, h: 46 },
	"Bot�odenavega��o4" : { image: "../Eventos_Np_regular.png", rollover: "../Eventos_NRp_regularOver.png", w: 120, h: 57, opening: "bottom", offsetX: 0, offsetY: 57 },
	"Bot�odenavega��o9" : { image: "../Calend-rio_Ns_regular.png", rollover: "../Calend-rio_NRs_regularOver.png", w: 120, h: 46 },
	"Bot�odenavega��o10" : { image: "../Galeria-de-Fotos_Ns_regular.png", rollover: "../Galeria-de-Fotos_NRs_regularOver.png", w: 120, h: 46 },
	"Bot�odenavega��o11" : { image: "../Fotos_Ns_regular.png", rollover: "../Fotos_NRs_regularOver.png", w: 120, h: 46 },
	"Bot�odenavega��o5" : { image: "../Perguntas-Frequentes_Np_regular.png", rollover: "../Perguntas-Frequentes_NRp_regularOver.png", w: 120, h: 57 }
};

$(document).ready(function(){
	$.fn.nofNavBarOptions({ navBarId: "NavigationBar1", rollover: true, autoClose: true });
	$("#NavigationBar1").nofNavBar({isMain: true, orientation: "horizontal" });
	$("#NavigationBar1_1").nofNavBar({isMain: false, orientation: "vertical", opening: "right", offsetX: 120, offsetY: 0 });
	$("#NavigationBar1_2").nofNavBar({isMain: false, orientation: "vertical", opening: "right", offsetX: 120, offsetY: 0 });
	$("#NavigationBar1_3").nofNavBar({isMain: false, orientation: "vertical", opening: "right", offsetX: 120, offsetY: 0 });
	$("#NavigationBar1_4").nofNavBar({isMain: false, orientation: "vertical", opening: "right", offsetX: 120, offsetY: 0 });
	$("#NavigationBar1 ul").hide();
});


// End Navigation Bars

