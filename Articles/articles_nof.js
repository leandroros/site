// Begin XHTML adjustment
$(document).ready(function(){
	if (jQuery.browser.msie && jQuery.browser.version.substr(0, 2) == "6.") {
		$(".nof-clearfix").each(function (i) {
			$(this).append("<div style='clear:both'/>");
			$(this).removeClass("nof-clearfix");
		});
	}
	if (jQuery.browser.safari){
		$(".nof-lyr>br:first").each(function () {
			$(this).replaceWith("<div style='height:0px'>&nbsp;</div>");
		});
	}
});

// End XHTML adjustment

// Begin Navigation Bars
var ButtonsImageMapping = [];
ButtonsImageMapping["NavigationBar1"] = {
	"Botãodenavegação1" : { image: "../Home_Np_regular_1.png", rollover: "../Home_NRp_regularOver_1.png", w: 142, h: 50 },
	"Botãodenavegação2" : { image: "../About_Np_regular_1.png", rollover: "../About_NRp_regularOver_1.png", w: 142, h: 50 },
	"Botãodenavegação3" : { image: "../Property_Np_regular_1.png", rollover: "../Property_NRp_regularOver_1.png", w: 142, h: 50 },
	"Botãodenavegação4" : { image: "../Articles_Hp_highlighted_1.png", rollover: "../Articles_HRp_highlightedOver_1.png", w: 142, h: 50 },
	"Botãodenavegação5" : { image: "../Contact_Np_regular_1.png", rollover: "../Contact_NRp_regularOver_1.png", w: 142, h: 50 }
};

$(document).ready(function(){
	$.fn.nofNavBarOptions({ navBarId: "NavigationBar1", rollover: true, autoClose: true });
	$("#NavigationBar1").nofNavBar({isMain: true, orientation: "horizontal" });
	$("#NavigationBar1 ul").hide();
});


// End Navigation Bars

