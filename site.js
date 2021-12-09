$(document).ready(function()
{
   $('#wb_Text1').addClass('visibility-hidden');
   $('#wb_Text6').addClass('visibility-hidden');
   $('#wb_Text7').addClass('visibility-hidden');
   $('#wb_Text9').addClass('visibility-hidden');
   $('#wb_Team1').addClass('visibility-hidden');
   $('#wb_Team2').addClass('visibility-hidden');
   $('#wb_Team3').addClass('visibility-hidden');
   $('#wb_Name1').addClass('visibility-hidden');
   $('#wb_Name2').addClass('visibility-hidden');
   $('#wb_Name3').addClass('visibility-hidden');
   function skrollrInit()
   {
      skrollr.init({forceHeight: false, mobileCheck: function() { return false; }, smoothScrolling: false});
   }
   skrollrInit();
   $("a[href*='#intro']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#intro').offset().top-50 }, 600, 'easeInQuad');
   });
   function onScrollText1()
   {
      var $obj = $("#wb_Text1");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Text1', 'transform-tada', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Text1', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Text1').inViewPort(true))
   {
      $('#wb_Text1').addClass("in-viewport");
   }
   onScrollText1();
   $(window).scroll(function(event)
   {
      onScrollText1();
   });
   $("a[href*='#team']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#team').offset().top-50 }, 600, 'easeInQuad');
   });
   function onScrollText6()
   {
      var $obj = $("#wb_Text6");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Text6', 'transform-tada', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Text6', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Text6').inViewPort(true))
   {
      $('#wb_Text6').addClass("in-viewport");
   }
   onScrollText6();
   $(window).scroll(function(event)
   {
      onScrollText6();
   });
   function onScrollText7()
   {
      var $obj = $("#wb_Text7");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Text7', 'transform-bounce-in-down', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Text7', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Text7').inViewPort(true))
   {
      $('#wb_Text7').addClass("in-viewport");
   }
   onScrollText7();
   $(window).scroll(function(event)
   {
      onScrollText7();
   });
   function onScrollText9()
   {
      var $obj = $("#wb_Text9");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Text9', 'transform-bounce-in-down', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Text9', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Text9').inViewPort(true))
   {
      $('#wb_Text9').addClass("in-viewport");
   }
   onScrollText9();
   $(window).scroll(function(event)
   {
      onScrollText9();
   });
   function onScrollTeam1()
   {
      var $obj = $("#wb_Team1");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Team1', 'transform-spring', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Team1', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Team1').inViewPort(true))
   {
      $('#wb_Team1').addClass("in-viewport");
   }
   onScrollTeam1();
   $(window).scroll(function(event)
   {
      onScrollTeam1();
   });
   function onScrollTeam2()
   {
      var $obj = $("#wb_Team2");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Team2', 'transform-spring', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Team2', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Team2').inViewPort(true))
   {
      $('#wb_Team2').addClass("in-viewport");
   }
   onScrollTeam2();
   $(window).scroll(function(event)
   {
      onScrollTeam2();
   });
   function onScrollTeam3()
   {
      var $obj = $("#wb_Team3");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Team3', 'transform-spring', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Team3', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Team3').inViewPort(true))
   {
      $('#wb_Team3').addClass("in-viewport");
   }
   onScrollTeam3();
   $(window).scroll(function(event)
   {
      onScrollTeam3();
   });
   function onScrollName1()
   {
      var $obj = $("#wb_Name1");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Name1', 'transform-scale-in', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Name1', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Name1').inViewPort(true))
   {
      $('#wb_Name1').addClass("in-viewport");
   }
   onScrollName1();
   $(window).scroll(function(event)
   {
      onScrollName1();
   });
   function onScrollName2()
   {
      var $obj = $("#wb_Name2");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Name2', 'transform-scale-in', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Name2', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Name2').inViewPort(true))
   {
      $('#wb_Name2').addClass("in-viewport");
   }
   onScrollName2();
   $(window).scroll(function(event)
   {
      onScrollName2();
   });
   function onScrollName3()
   {
      var $obj = $("#wb_Name3");
      if (!$obj.hasClass("in-viewport") && $obj.inViewPort(false))
      {
         $obj.addClass("in-viewport");
         AnimateCss('wb_Name3', 'transform-scale-in', 100, 1000);
      }
      else
      if ($obj.hasClass("in-viewport") && !$obj.inViewPort(true))
      {
         $obj.removeClass("in-viewport");
         AnimateCss('wb_Name3', 'animate-fade-out', 0, 0);
      }
   }
   if (!$('#wb_Name3').inViewPort(true))
   {
      $('#wb_Name3').addClass("in-viewport");
   }
   onScrollName3();
   $(window).scroll(function(event)
   {
      onScrollName3();
   });
   $("a[href*='#contact']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#wb_contact').offset().top-50 }, 600, 'easeInQuad');
   });
});
