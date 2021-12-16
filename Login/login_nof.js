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
	"Botãodenavegação1" : { image: "../Home_Np_Regular_1.jpg", rollover: "../Home_NRp_RegularOver_1.jpg", w: 101, h: 36 },
	"Botãodenavegação2" : { image: "../Login_Hp_Highlighted_1.jpg", rollover: "../Login_HRp_HighlightedOver_1.jpg", w: 101, h: 36, opening: "bottom", offsetX: 0, offsetY: 36 },
	"Botãodenavegação4" : { image: "../Adm_Ns_Regular.png", rollover: "../Adm_NRs_RegularOver.png", w: 101, h: 21 },
	"Botãodenavegação5" : { image: "../Senha_Ns_Regular.png", rollover: "../Senha_NRs_RegularOver.png", w: 101, h: 21 },
	"Botãodenavegação6" : { image: "../cadastro_Ns_Regular.png", rollover: "../cadastro_NRs_RegularOver.png", w: 101, h: 21 },
	"Botãodenavegação3" : { image: "../Tabiban_Np_Regular_1.jpg", rollover: "../Tabiban_NRp_RegularOver_1.jpg", w: 101, h: 36 }
};

$(document).ready(function(){
	$.fn.nofNavBarOptions({ navBarId: "NavigationBar1", rollover: true, autoClose: true });
	$("#NavigationBar1").nofNavBar({isMain: true, orientation: "horizontal" });
	$("#NavigationBar1_1").nofNavBar({isMain: false, orientation: "vertical", opening: "right", offsetX: 101, offsetY: 0 });
	$("#NavigationBar1 ul").hide();
});


// End Navigation Bars

