/*!
* jQuery Cookie Plugin
* https://github.com/carhartl/jquery-cookie
*
* Copyright 2011, Klaus Hartl
* Dual licensed under the MIT or GPL Version 2 licenses.
* http://www.opensource.org/licenses/mit-license.php
* http://www.opensource.org/licenses/GPL-2.0
*/
(function($) {
    $.cookie = function(key, value, options) 
    {
        if (arguments.length > 1 && (!/Object/.test(Object.prototype.toString.call(value)) || value === null || value === undefined)) 
        {
            options = $.extend({}, options);

            if (value === null || value === undefined) 
            {
                options.expires = -1;
            }

            if (typeof options.expires === 'number') 
            {
                var days = options.expires, t = options.expires = new Date();
                t.setDate(t.getDate() + days);
            }

            value = String(value);

            return (document.cookie = 
            [
                encodeURIComponent(key), '=', options.raw ? value : encodeURIComponent(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path ? '; path=' + options.path : '',
                options.domain ? '; domain=' + options.domain : '',
                options.secure ? '; secure' : ''
            ].join(''));
        }
        options = value || {};
        var decode = options.raw ? function(s) { return s; } : decodeURIComponent;

        var pairs = document.cookie.split('; ');
        for (var i = 0, pair; pair = pairs[i] && pairs[i].split('='); i++) 
        {
            if (decode(pair[0]) === key) return decode(pair[1] || ''); // IE saves cookies with empty string as "c; ", e.g. without "=" as opposed to EOMB, thus pair[1] may be undefined
        }
        return null;
    };
})(jQuery);

$(document).ready(function()
{
   if ($.cookie('cookie_notice') == null) 
   {
      $('#cookie_policy').show();
   } 
   $('#cookie_button').click(function(e) 
   {
      e.preventDefault();
      var timestamp = (new Date()).getTime();
      $.cookie('cookie_notice', timestamp, { expires: new Date(timestamp + (31536000 * 1000)), path: '/'});
      $('#cookie_policy').fadeOut("slow");
   });
});
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
