/*!
 * jCarousel - Riding carousels with jQuery
 *   http://sorgalla.com/jcarousel/
 *
 * Copyright (c) 2006 Jan Sorgalla (http://sorgalla.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Built on top of the jQuery library
 *   http://jquery.com
 *
 * Inspired by the "Carousel Component" by Bill Scott
 *   http://billwscott.com/carousel/
 */
(function(c){var d={vertical:false,rtl:false,start:1,offset:1,size:null,scroll:3,visible:null,animation:"normal",easing:"swing",auto:0,wrap:null,showNavigation:"hover",showCaption:true,fit:false,initCallback:null,setupCallback:null,reloadCallback:null,itemLoadCallback:null,itemFirstInCallback:null,itemFirstOutCallback:null,itemLastInCallback:null,itemLastOutCallback:null,itemVisibleInCallback:null,itemVisibleOutCallback:null,animationStepCallback:null,buttonNextHTML:"<div></div>",buttonPrevHTML:"<div></div>",buttonNextEvent:"click",buttonPrevEvent:"click",buttonNextCallback:null,buttonPrevCallback:null,itemFallbackDimension:null},b=false;c(window).bind("load.jcarousel",function(){b=true});c.jcarousel=function(n,h){this.options=c.extend({},d,h||{});this.locked=false;this.autoStopped=false;this.container=null;this.clip=null;this.list=null;this.buttonNext=null;this.buttonPrev=null;this.buttonNextState=null;this.buttonPrevState=null;if(!h||h.rtl===undefined){this.options.rtl=(c(n).attr("dir")||c("html").attr("dir")||"").toLowerCase()=="rtl"}this.wh=!this.options.vertical?"width":"height";this.lt=!this.options.vertical?(this.options.rtl?"right":"left"):"top";var s="",q=n.className.split(" ");for(var l=0;l<q.length;l++){if(q[l].indexOf("jcarousel-skin")!=-1){c(n).removeClass(q[l]);s=q[l];break}}if(n.nodeName.toUpperCase()=="UL"||n.nodeName.toUpperCase()=="OL"){this.list=c(n);this.clip=this.list.parents(".jcarousel-clip");this.container=this.list.parents(".jcarousel-container")}else{this.container=c(n);this.list=this.container.find("ul,ol").eq(0);this.clip=this.container.find(".jcarousel-clip")}if(this.clip.size()===0){this.clip=this.list.wrap("<div></div>").parent()}if(this.container.size()===0){this.container=this.clip.wrap("<div></div>").parent()}if(this.container.parent()[0].nodeName=="TD"){this.container.parent()[0].style.width="auto"}if(s!==""&&this.container.parent()[0].className.indexOf("jcarousel-skin")==-1){this.container.wrap('<div class=" '+s+'"></div>')}this.buttonPrev=c(".jcarousel-prev",this.container);if(this.buttonPrev.size()===0&&this.options.buttonPrevHTML!==null){this.buttonPrev=c(this.options.buttonPrevHTML).appendTo(this.container)}this.buttonPrev.addClass(this.className("jcarousel-prev"));this.buttonNext=c(".jcarousel-next",this.container);if(this.buttonNext.size()===0&&this.options.buttonNextHTML!==null){this.buttonNext=c(this.options.buttonNextHTML).appendTo(this.container)}this.buttonNext.addClass(this.className("jcarousel-next"));if(this.options.showNavigation===false){this.buttonPrev.remove();this.buttonNext.remove()}else{if(typeof this.options.showNavigation==="string"&&this.options.showNavigation.toLowerCase()==="hover"){this.container.addClass("jcarousel-navigation-hover")}}if(this.options.showCaption===false){c(".jcarousel-caption",this.container).remove()}else{if(typeof this.options.showCaption==="string"&&this.options.showCaption.toLowerCase()==="hover"){this.container.addClass("jcarousel-caption-hover")}}this.clip.addClass(this.className("jcarousel-clip")).css({position:"relative"});this.list.addClass(this.className("jcarousel-list")).css({overflow:"hidden",position:"relative",top:0,margin:0,padding:0}).css((this.options.rtl?"right":"left"),0);this.container.addClass(this.className("jcarousel-container")).css({position:"relative"});if(!this.options.vertical&&this.options.rtl){this.container.addClass("jcarousel-direction-rtl").attr("dir","rtl")}var p=this.options.visible!==null?Math.ceil(this.clipping()/this.options.visible):null;var r=this.list.children("li");var t=this;if(r.size()>0){var f=0,k=this.options.offset;r.each(function(){t.format(this,k++);f+=t.dimension(this,p)});this.list.css(this.wh,(f+100)+"px");if(!h||h.size===undefined){this.options.size=r.size()}}this.list.find("img").on("load",function(e){return t._onImageLoad.apply(t,[e])}).each(function(){if(this.complete){c(this).trigger("load")}});this.container.css("display","block");this.buttonNext.css("display","block");this.buttonPrev.css("display","block");this.funcNext=function(){t.next();return false};this.funcPrev=function(){t.prev();return false};this.funcResize=function(){if(t.resizeTimer){clearTimeout(t.resizeTimer)}t.resizeTimer=setTimeout(function(){t.reload()},100)};if(this.options.initCallback!==null){this.options.initCallback(this,"init")}var m;(function g(){m=t.list.is(":visible");if(!m&&!t.locked){t.isVisible=m;t.lock();t.pauseAuto()}if(t.isVisible===false&&m&&t.locked){t.isVisible=null;t.unlock();if(t.hasSetup===null){var u=t.options.visible!==null?Math.ceil(t.clipping()/t.options.visible):null;
var e=t.list.children("li");var i=0,o=t.options.offset;e.each(function(){t.format(this,o++);i+=t.dimension(this,u)});t.list.css(t.wh,(i+100)+"px");t.list.find("img").load();t.setup()}else{if(t.autoStopped===false&&t.options.auto>0){t.reload();t.startAuto(t.options.auto)}}}setTimeout(g,100)})();if(!b&&a.isSafari()){this.buttons(false,false);c(window).bind("load.jcarousel",function(){t.setup()})}else{this.setup()}};var a=c.jcarousel;a.fn=a.prototype={jcarousel:"0.2.8"};a.fn.extend=a.extend=c.extend;a.fn.extend({setup:function(){this.first=null;this.last=null;this.prevFirst=null;this.prevLast=null;this.animating=false;this.timer=null;this.resizeTimer=null;this.tail=null;this.inTail=false;this.hasSetup=null;if(this.locked){return}this.list.css(this.lt,this.pos(this.options.offset)+"px");var e=this.pos(this.options.start,true);this.prevFirst=this.prevLast=null;this.animate(e,false);c(window).unbind("resize.jcarousel",this.funcResize).bind("resize.jcarousel",this.funcResize);if(this.options.setupCallback!==null){this.options.setupCallback(this)}this.hasSetup=true},reset:function(){this.list.empty();this.list.css(this.lt,"0px");this.list.css(this.wh,"10px");if(this.options.initCallback!==null){this.options.initCallback(this,"reset")}this.setup()},reload:function(){if(this.tail!==null&&this.inTail){this.list.css(this.lt,a.intval(this.list.css(this.lt))+this.tail)}this.tail=null;this.inTail=false;if(this.options.reloadCallback!==null){this.options.reloadCallback(this)}if(this.options.visible!==null){var g=this;var h=Math.ceil(this.clipping()/this.options.visible),f=0,e=0;this.list.children("li").each(function(j){f+=g.dimension(this,h);if(j+1<g.first){e=f}});this.list.css(this.wh,(f+100)+"px")}this.scroll(this.first,false)},lock:function(){this.locked=true;this.buttons()},unlock:function(){this.locked=false;this.buttons()},size:function(e){if(e!==undefined){this.options.size=e;if(!this.locked){this.buttons()}}return this.options.size},has:function(g,h){if(h===undefined||!h){h=g}if(this.options.size!==null&&h>this.options.size){h=this.options.size}for(var f=g;f<=h;f++){var k=this.get(f);if(!k.length||k.hasClass("jcarousel-item-placeholder")){return false}}return true},get:function(e){return c(">.jcarousel-item-"+e,this.list)},add:function(l,r){var m=this.get(l),h=0,g=c(r);if(m.length===0){var p,k=a.intval(l);m=this.create(l);while(true){p=this.get(--k);if(k<=0||p.length){if(k<=0){this.list.prepend(m)}else{p.after(m)}break}}}else{h=this.dimension(m)}if(g.get(0).nodeName.toUpperCase()=="LI"){m.replaceWith(g);m=g}else{m.empty().append(r)}var q=this;m.find("img").on("load",function(e){return q._onImageLoad.apply(q,[e])}).each(function(){if(this.complete){c(this).trigger("load")}});this.format(m.removeClass(this.className("jcarousel-item-placeholder")),l);var o=this.options.visible!==null?Math.ceil(this.clipping()/this.options.visible):null;var f=this.dimension(m,o)-h;if(l>0&&l<this.first){this.list.css(this.lt,a.intval(this.list.css(this.lt))-f+"px")}this.list.css(this.wh,a.intval(this.list.css(this.wh))+f+"px");return m},remove:function(f){var g=this.get(f);if(!g.length||(f>=this.first&&f<=this.last)){return}var h=this.dimension(g);if(f<this.first){this.list.css(this.lt,a.intval(this.list.css(this.lt))+h+"px")}g.remove();this.list.css(this.wh,a.intval(this.list.css(this.wh))-h+"px")},next:function(){if(this.tail!==null&&!this.inTail){this.scrollTail(false)}else{this.scroll(((this.options.wrap=="both"||this.options.wrap=="last")&&this.options.size!==null&&this.last==this.options.size)?1:this.first+this.options.scroll)}},prev:function(){if(this.tail!==null&&this.inTail){this.scrollTail(true)}else{this.scroll(((this.options.wrap=="both"||this.options.wrap=="first")&&this.options.size!==null&&this.first==1)?this.options.size:this.first-this.options.scroll)}},scrollTail:function(e){if(this.locked||this.animating||!this.tail){return}this.pauseAuto();var f=a.intval(this.list.css(this.lt));f=!e?f-this.tail:f+this.tail;this.inTail=!e;this.prevFirst=this.first;this.prevLast=this.last;this.animate(f)},scroll:function(f,e){if(this.locked||this.animating){return}this.pauseAuto();this.animate(this.pos(f),e)},pos:function(C,k){var n=a.intval(this.list.css(this.lt));if(this.locked||this.animating){return n}if(this.options.wrap!="circular"){C=C<1?1:(this.options.size&&C>this.options.size?this.options.size:C)}var z=this.first>C;var E=this.options.wrap!="circular"&&this.first<=1?1:this.first;var H=z?this.get(E):this.get(this.last);var B=z?E:E-1;var F=null,A=0,w=false,G=0,D;while(z?--B>=C:++B<C){F=this.get(B);w=!F.length;if(F.length===0){F=this.create(B).addClass(this.className("jcarousel-item-placeholder")).css(this.wh,this.dimension(H)+"px");H[z?"before":"after"](F);if(this.first!==null&&this.options.wrap=="circular"&&this.options.size!==null&&(B<=0||B>this.options.size)){D=this.get(this.index(B));if(D.length){F=this.add(B,D.clone(true))}}}H=F;G=this.dimension(F);if(w){A+=G}if(this.first!==null&&(this.options.wrap=="circular"||(B>=1&&(this.options.size===null||B<=this.options.size)))){n=z?n+G:n-G
}}var s=this.clipping(),u=[],h=0,t=0;H=this.get(C-1);B=C;while(++h){F=this.get(B);w=!F.length;if(F.length===0){F=this.create(B).addClass(this.className("jcarousel-item-placeholder")).css(this.wh,this.dimension(H)+"px");if(H.length===0){this.list.prepend(F)}else{H[z?"before":"after"](F)}if(this.first!==null&&this.options.wrap=="circular"&&this.options.size!==null&&(B<=0||B>this.options.size)){D=this.get(this.index(B));if(D.length){F=this.add(B,D.clone(true))}}}H=F;G=this.dimension(F);if(G===0){throw new Error("jCarousel: No width/height set for items. This will cause an infinite loop. Aborting...")}if(this.options.wrap!="circular"&&this.options.size!==null&&B>this.options.size){u.push(F)}else{if(w){A+=G}}t+=G;if(t>=s){break}B++}for(var r=0;r<u.length;r++){u[r].remove()}if(A>0){this.list.css(this.wh,this.dimension(this.list)+A+"px");if(z){n-=A;this.list.css(this.lt,a.intval(this.list.css(this.lt))-A+"px")}}var q=C+h-1;if(this.options.wrap!="circular"&&this.options.size&&q>this.options.size){q=this.options.size}if(B>q){h=0;B=q;t=0;while(++h){F=this.get(B--);if(!F.length){break}t+=this.dimension(F);if(t>=s){break}}}var o=q-h+1;if(this.options.wrap!="circular"&&o<1){o=1}if(this.inTail&&z){n+=this.tail;this.inTail=false}this.tail=null;if(this.options.wrap!="circular"&&q==this.options.size&&(q-h+1)>=1){var y=a.intval(this.get(q).css(!this.options.vertical?"marginRight":"marginBottom"));if((t-y)>s){this.tail=t-s-y}}if(k&&C===this.options.size&&this.tail){n-=this.tail;this.inTail=true}while(C-->o){n+=this.dimension(this.get(C))}this.prevFirst=this.first;this.prevLast=this.last;this.first=o;this.last=q;return n},animate:function(i,e){if(this.locked||this.animating){return}this.animating=true;var f=this;var g=function(){f.animating=false;if(i===0){f.list.css(f.lt,0)}if(!f.autoStopped&&(f.options.wrap=="circular"||f.options.wrap=="both"||f.options.wrap=="last"||f.options.size===null||f.last<f.options.size||(f.last==f.options.size&&f.tail!==null&&!f.inTail))){f.startAuto()}f.buttons();f.notify("onAfterAnimation");if(f.options.wrap=="circular"&&f.options.size!==null){for(var k=f.prevFirst;k<=f.prevLast;k++){if(k!==null&&!(k>=f.first&&k<=f.last)&&(k<1||k>f.options.size)){f.remove(k)}}}};this.notify("onBeforeAnimation");if(!this.options.animation||e===false){this.list.css(this.lt,i+"px");g()}else{var j=!this.options.vertical?(this.options.rtl?{right:i}:{left:i}):{top:i};var h={duration:this.options.animation,easing:this.options.easing,complete:g};if(c.isFunction(this.options.animationStepCallback)){h.step=this.options.animationStepCallback}this.list.animate(j,h)}},startAuto:function(f){if(f!==undefined){this.options.auto=f}if(this.options.auto===0){return this.stopAuto()}if(this.timer!==null){return}this.autoStopped=false;var e=this;this.timer=window.setTimeout(function(){e.next()},this.options.auto*1000)},stopAuto:function(){this.pauseAuto();this.autoStopped=true},pauseAuto:function(){if(this.timer===null){return}window.clearTimeout(this.timer);this.timer=null},buttons:function(g,f){if(g==null){g=!this.locked&&this.options.size!==0&&((this.options.wrap&&this.options.wrap!="first")||this.options.size===null||this.last<this.options.size);if(!this.locked&&(!this.options.wrap||this.options.wrap=="first")&&this.options.size!==null&&this.last>=this.options.size){g=this.tail!==null&&!this.inTail}}if(f==null){f=!this.locked&&this.options.size!==0&&((this.options.wrap&&this.options.wrap!="last")||this.first>1);if(!this.locked&&(!this.options.wrap||this.options.wrap=="last")&&this.options.size!==null&&this.first==1){f=this.tail!==null&&this.inTail}}var e=this;if(this.buttonNext.size()>0){this.buttonNext.unbind(this.options.buttonNextEvent+".jcarousel",this.funcNext);if(g){this.buttonNext.bind(this.options.buttonNextEvent+".jcarousel",this.funcNext)}this.buttonNext[g?"removeClass":"addClass"](this.className("jcarousel-next-disabled")).attr("disabled",g?false:true);if(this.options.buttonNextCallback!==null&&this.buttonNext.data("jcarouselstate")!=g){this.buttonNext.each(function(){e.options.buttonNextCallback(e,this,g)}).data("jcarouselstate",g)}}else{if(this.options.buttonNextCallback!==null&&this.buttonNextState!=g){this.options.buttonNextCallback(e,null,g)}}if(this.buttonPrev.size()>0){this.buttonPrev.unbind(this.options.buttonPrevEvent+".jcarousel",this.funcPrev);if(f){this.buttonPrev.bind(this.options.buttonPrevEvent+".jcarousel",this.funcPrev)}this.buttonPrev[f?"removeClass":"addClass"](this.className("jcarousel-prev-disabled")).attr("disabled",f?false:true);if(this.options.buttonPrevCallback!==null&&this.buttonPrev.data("jcarouselstate")!=f){this.buttonPrev.each(function(){e.options.buttonPrevCallback(e,this,f)}).data("jcarouselstate",f)}}else{if(this.options.buttonPrevCallback!==null&&this.buttonPrevState!=f){this.options.buttonPrevCallback(e,null,f)}}this.buttonNextState=g;this.buttonPrevState=f},notify:function(e){var f=this.prevFirst===null?"init":(this.prevFirst<this.first?"next":"prev");this.callback("itemLoadCallback",e,f);
if(this.prevFirst!==this.first){this.callback("itemFirstInCallback",e,f,this.first);this.callback("itemFirstOutCallback",e,f,this.prevFirst)}if(this.prevLast!==this.last){this.callback("itemLastInCallback",e,f,this.last);this.callback("itemLastOutCallback",e,f,this.prevLast)}this.callback("itemVisibleInCallback",e,f,this.first,this.last,this.prevFirst,this.prevLast);this.callback("itemVisibleOutCallback",e,f,this.prevFirst,this.prevLast,this.first,this.last)},callback:function(j,m,e,k,h,g,f){if(this.options[j]==null||(typeof this.options[j]!="object"&&m!="onAfterAnimation")){return}var n=typeof this.options[j]=="object"?this.options[j][m]:this.options[j];if(!c.isFunction(n)){return}var o=this;if(k===undefined){n(o,e,m)}else{if(h===undefined){this.get(k).each(function(){n(o,this,k,e,m)})}else{var p=function(q){o.get(q).each(function(){n(o,this,q,e,m)})};for(var l=k;l<=h;l++){if(l!==null&&!(l>=g&&l<=f)){p(l)}}}}},create:function(e){return this.format("<li></li>",e)},format:function(k,h){k=c(k);var g=k.get(0).className.split(" ");for(var f=0;f<g.length;f++){if(g[f].indexOf("jcarousel-")!=-1){k.removeClass(g[f])}}k.addClass(this.className("jcarousel-item")).addClass(this.className("jcarousel-item-"+h)).css({"float":(this.options.rtl?"right":"left"),"list-style":"none"}).attr("jcarouselindex",h);return k},className:function(e){return e+" "+e+(!this.options.vertical?"-horizontal":"-vertical")},dimension:function(h,i){var g=c(h);if(i==null){return !this.options.vertical?((g.innerWidth()+a.intval(g.css("margin-left"))+a.intval(g.css("margin-right"))+a.intval(g.css("border-left-width"))+a.intval(g.css("border-right-width")))||a.intval(this.options.itemFallbackDimension)):((g.innerHeight()+a.intval(g.css("margin-top"))+a.intval(g.css("margin-bottom"))+a.intval(g.css("border-top-width"))+a.intval(g.css("border-bottom-width")))||a.intval(this.options.itemFallbackDimension))}else{var f=!this.options.vertical?i-a.intval(g.css("marginLeft"))-a.intval(g.css("marginRight")):i-a.intval(g.css("marginTop"))-a.intval(g.css("marginBottom"));c(g).css(this.wh,f+"px");return this.dimension(g)}},clipping:function(){return !this.options.vertical?this.clip[0].offsetWidth-a.intval(this.clip.css("borderLeftWidth"))-a.intval(this.clip.css("borderRightWidth")):this.clip[0].offsetHeight-a.intval(this.clip.css("borderTopWidth"))-a.intval(this.clip.css("borderBottomWidth"))},index:function(e,f){if(f==null){f=this.options.size}return Math.round((((e-1)/f)-Math.floor((e-1)/f))*f)+1},_onImageLoad:function(f){var h=c(f.target);var e=h.closest("li");var i={};var j={};var k,g;i.w=h.width();i.h=h.height();j.w=e.width();j.h=e.height();k=i.w/2;g=i.h/2;if(this.options.fit===true){if(i.h<j.h){h.addClass("jcarousel-fit-height");k=h.width()/2;g=0}if(i.w<j.w){h.addClass("jcarousel-fit-width");k=0;g=h.height()/2}}h.css("margin-left","-"+k+"px");h.css("margin-top","-"+g+"px")}});a.extend({defaults:function(e){return c.extend(d,e||{})},intval:function(e){e=parseInt(e,10);return isNaN(e)?0:e},windowLoaded:function(){b=true},isSafari:function(){var g=navigator.userAgent.toLowerCase(),f=/(chrome)[ \/]([\w.]+)/.exec(g)||/(webkit)[ \/]([\w.]+)/.exec(g)||[],e=f[1]||"";return e==="webkit"}});c.fn.jcarousel=function(g){if(typeof g=="string"){var e=c(this).data("jcarousel"),f=Array.prototype.slice.call(arguments,1);return e[g].apply(e,f)}else{return this.each(function(){var h=c(this).data("jcarousel");if(h){if(g){c.extend(h.options,g)}h.reload()}else{c(this).data("jcarousel",new a(this,g))}})}}})(jQuery);