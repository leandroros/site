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
