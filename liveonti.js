$(document).ready(function()
{
   $("a[href*='#welcome']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#wb_welcome').offset().top }, 600, 'easeOutSine');
   });
   $("a[href*='#infoBlock1']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#wb_infoBlock1').offset().top }, 600, 'easeOutSine');
   });
   $("a[href*='#chooseUs']").click(function(event)
   {
      event.preventDefault();
      $('html, body').stop().animate({ scrollTop: $('#wb_chooseUs').offset().top }, 600, 'easeOutSine');
   });
});
var datenow = new Date(); 
var timenow = datenow.getTime(); 
datenow.setTime(timenow); 
var hournow = datenow.getHours(); 
var greeting = document.getElementById('greeting');
if (hournow >= 18)
{ 
   greeting.innerHTML = "Boa Noite !"; 
}
else 
if (hournow >= 12) 
{
   greeting.innerHTML = "Boa Tarde !"; 
}
else 
{
   greeting.innerHTML = "Bom Dia !"; 
}
var disabled_message = "Botão Direto do mouse sem ultilidade";
document.oncontextmenu = function() 
{ 
   return false; 
}
document.onmousedown = function md(e) 
{ 
  try 
  { 
     if (event.button==2||event.button==3) 
     {
        if (disabled_message != '')
           alert(disabled_message);
        return false; 
     }
  }  
  catch (e) 
  { 
     if (e.which == 3) return false; 
  } 
}
