/** frontend/common-plugins.js */

/* make it safe to use console.log always */
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

/* http://davidwalsh.name/jquery-top-link */
jQuery.fn.topLink=function(settings){settings=jQuery.extend({min:1,fadeSpeed:200},settings);return this.each(function(){var el=$(this);el.hide();$(window).scroll(function(){if($(window).scrollTop()>=settings.min){el.fadeIn(settings.fadeSpeed);}else{el.fadeOut(settings.fadeSpeed);}});});};

/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */jQuery.cookie=function(a,b,c){if(arguments.length>1&&String(b)!=="[object Object]"){c=jQuery.extend({},c);if(b===null||b===undefined)c.expires=-1;if(typeof c.expires=="number"){var d=c.expires,e=c.expires=new Date;e.setDate(e.getDate()+d)}return b=String(b),document.cookie=[encodeURIComponent(a),"=",c.raw?b:encodeURIComponent(b),c.expires?"; expires="+c.expires.toUTCString():"",c.path?"; path="+c.path:"",c.domain?"; domain="+c.domain:"",c.secure?"; secure":""].join("")}c=b||{};var f,g=c.raw?function(a){return a}:decodeURIComponent;return(f=(new RegExp("(?:^|; )"+encodeURIComponent(a)+"=([^;]*)")).exec(document.cookie))?g(f[1]):null};

/*
 Mosaic - Sliding Boxes and Captions jQuery Plugin
 Version 1.0.1
 www.buildinternet.com/project/mosaic

 By Sam Dunn / One Mighty Roar (www.onemightyroar.com)
 Released under MIT License / GPL License
 */

(function(a){if(!a.omr){a.omr=new Object()}a.omr.mosaic=function(c,b){var d=this;d.$el=a(c);d.el=c;d.$el.data("omr.mosaic",d);d.init=function(){d.options=a.extend({},a.omr.mosaic.defaultOptions,b);d.load_box()};d.load_box=function(){if(d.options.preload){a(d.options.backdrop,d.el).hide();a(d.options.overlay,d.el).hide();a(window).load(function(){if(d.options.options.animation=="fade"&&a(d.options.overlay,d.el).css("opacity")==0){a(d.options.overlay,d.el).css("filter","alpha(opacity=0)")}a(d.options.overlay,d.el).fadeIn(200,function(){a(d.options.backdrop,d.el).fadeIn(200)});d.allow_hover()})}else{a(d.options.backdrop,d.el).show();a(d.options.overlay,d.el).show();d.allow_hover()}};d.allow_hover=function(){switch(d.options.animation){case"fade":a(d.el).hover(function(){a(d.options.overlay,d.el).stop().fadeTo(d.options.speed,d.options.opacity)},function(){a(d.options.overlay,d.el).stop().fadeTo(d.options.speed,0)});break;case"slide":startX=a(d.options.overlay,d.el).css(d.options.anchor_x)!="auto"?a(d.options.overlay,d.el).css(d.options.anchor_x):"0px";startY=a(d.options.overlay,d.el).css(d.options.anchor_y)!="auto"?a(d.options.overlay,d.el).css(d.options.anchor_y):"0px";var f={};f[d.options.anchor_x]=d.options.hover_x;f[d.options.anchor_y]=d.options.hover_y;var e={};e[d.options.anchor_x]=startX;e[d.options.anchor_y]=startY;a(d.el).hover(function(){a(d.options.overlay,d.el).stop().animate(f,d.options.speed)},function(){a(d.options.overlay,d.el).stop().animate(e,d.options.speed)});break}};d.init()};a.omr.mosaic.defaultOptions={animation:"fade",speed:150,opacity:1,preload:0,anchor_x:"left",anchor_y:"bottom",hover_x:"0px",hover_y:"0px",overlay:".mosaic-overlay",backdrop:".mosaic-backdrop"};a.fn.mosaic=function(b){return this.each(function(){(new a.omr.mosaic(this,b))})}})(jQuery);

/*
 jQuery Waypoints - v1.1.5
 Copyright (c) 2011-2012 Caleb Troughton
 Dual licensed under the MIT license and GPL license.
 https://github.com/imakewebthings/jquery-waypoints/blob/master/MIT-license.txt
 https://github.com/imakewebthings/jquery-waypoints/blob/master/GPL-license.txt
 */
(function($,k,m,i,d){var e=$(i),g="waypoint.reached",b=function(o,n){o.element.trigger(g,n);if(o.options.triggerOnce){o.element[k]("destroy")}},h=function(p,o){var n=o.waypoints.length-1;while(n>=0&&o.waypoints[n].element[0]!==p[0]){n-=1}return n},f=[],l=function(n){$.extend(this,{element:$(n),oldScroll:0,waypoints:[],didScroll:false,didResize:false,doScroll:$.proxy(function(){var q=this.element.scrollTop(),p=q>this.oldScroll,s=this,r=$.grep(this.waypoints,function(u,t){return p?(u.offset>s.oldScroll&&u.offset<=q):(u.offset<=s.oldScroll&&u.offset>q)}),o=r.length;if(!this.oldScroll||!q){$[m]("refresh")}this.oldScroll=q;if(!o){return}if(!p){r.reverse()}$.each(r,function(u,t){if(t.options.continuous||u===o-1){b(t,[p?"down":"up"])}})},this)});$(n).scroll($.proxy(function(){if(!this.didScroll){this.didScroll=true;i.setTimeout($.proxy(function(){this.doScroll();this.didScroll=false},this),$[m].settings.scrollThrottle)}},this)).resize($.proxy(function(){if(!this.didResize){this.didResize=true;i.setTimeout($.proxy(function(){$[m]("refresh");this.didResize=false},this),$[m].settings.resizeThrottle)}},this));e.load($.proxy(function(){this.doScroll()},this))},j=function(n){var o=null;$.each(f,function(p,q){if(q.element[0]===n){o=q;return false}});return o},c={init:function(o,n){this.each(function(){var u=$.fn[k].defaults.context,q,t=$(this);if(n&&n.context){u=n.context}if(!$.isWindow(u)){u=t.closest(u)[0]}q=j(u);if(!q){q=new l(u);f.push(q)}var p=h(t,q),s=p<0?$.fn[k].defaults:q.waypoints[p].options,r=$.extend({},s,n);r.offset=r.offset==="bottom-in-view"?function(){var v=$.isWindow(u)?$[m]("viewportHeight"):$(u).height();return v-$(this).outerHeight()}:r.offset;if(p<0){q.waypoints.push({element:t,offset:null,options:r})}else{q.waypoints[p].options=r}if(o){t.bind(g,o)}if(n&&n.handler){t.bind(g,n.handler)}});$[m]("refresh");return this},remove:function(){return this.each(function(o,p){var n=$(p);$.each(f,function(r,s){var q=h(n,s);if(q>=0){s.waypoints.splice(q,1)}})})},destroy:function(){return this.unbind(g)[k]("remove")}},a={refresh:function(){$.each(f,function(r,s){var q=$.isWindow(s.element[0]),n=q?0:s.element.offset().top,p=q?$[m]("viewportHeight"):s.element.height(),o=q?0:s.element.scrollTop();$.each(s.waypoints,function(u,x){if(!x){return}var t=x.options.offset,w=x.offset;if(typeof x.options.offset==="function"){t=x.options.offset.apply(x.element)}else{if(typeof x.options.offset==="string"){var v=parseFloat(x.options.offset);t=x.options.offset.indexOf("%")?Math.ceil(p*(v/100)):v}}x.offset=x.element.offset().top-n+o-t;if(x.options.onlyOnScroll){return}if(w!==null&&s.oldScroll>w&&s.oldScroll<=x.offset){b(x,["up"])}else{if(w!==null&&s.oldScroll<w&&s.oldScroll>=x.offset){b(x,["down"])}else{if(!w&&o>x.offset){b(x,["down"])}}}});s.waypoints.sort(function(u,t){return u.offset-t.offset})})},viewportHeight:function(){return(i.innerHeight?i.innerHeight:e.height())},aggregate:function(){var n=$();$.each(f,function(o,p){$.each(p.waypoints,function(q,r){n=n.add(r.element)})});return n}};$.fn[k]=function(n){if(c[n]){return c[n].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof n==="function"||!n){return c.init.apply(this,arguments)}else{if(typeof n==="object"){return c.init.apply(this,[null,n])}else{$.error("Method "+n+" does not exist on jQuery "+k)}}}};$.fn[k].defaults={continuous:true,offset:0,triggerOnce:false,context:i};$[m]=function(n){if(a[n]){return a[n].apply(this)}else{return a.aggregate()}};$[m].settings={resizeThrottle:200,scrollThrottle:100};e.load(function(){$[m]("refresh")})})(jQuery,"waypoint","waypoints",window);

/*
 * jQuery showLoading plugin v1.0
 *
 * Copyright (c) 2009 Jim Keller
 * Context - http://www.contextllc.com
 *
 * Dual licensed under the MIT and GPL licenses.
 *
 */
jQuery.fn.showLoading=function(options){var indicatorID;var settings={'addClass':'','beforeShow':'','afterShow':'','hPos':'center','vPos':'center','indicatorZIndex':5001,'overlayZIndex':5000,'parent':'','marginTop':0,'marginLeft':0,'overlayWidth':null,'overlayHeight':null};jQuery.extend(settings,options);var loadingDiv=jQuery('<div></div>');var overlayDiv=jQuery('<div></div>');if(settings.indicatorID){indicatorID=settings.indicatorID;}
else{indicatorID=jQuery(this).attr('id');}
  jQuery(loadingDiv).attr('id','loading-indicator-'+indicatorID);jQuery(loadingDiv).addClass('loading-indicator');if(settings.addClass){jQuery(loadingDiv).addClass(settings.addClass);}
  jQuery(overlayDiv).css('display','none');jQuery(document.body).append(overlayDiv);jQuery(overlayDiv).attr('id','loading-indicator-'+indicatorID+'-overlay');jQuery(overlayDiv).addClass('loading-indicator-overlay');if(settings.addClass){jQuery(overlayDiv).addClass(settings.addClass+'-overlay');}
  var overlay_width;var overlay_height;var border_top_width=jQuery(this).css('border-top-width');var border_left_width=jQuery(this).css('border-left-width');border_top_width=isNaN(parseInt(border_top_width))?0:border_top_width;border_left_width=isNaN(parseInt(border_left_width))?0:border_left_width;var overlay_left_pos=jQuery(this).offset().left+parseInt(border_left_width);var overlay_top_pos=jQuery(this).offset().top+parseInt(border_top_width);if(settings.overlayWidth!==null){overlay_width=settings.overlayWidth;}
  else{overlay_width=parseInt(jQuery(this).width())+parseInt(jQuery(this).css('padding-right'))+parseInt(jQuery(this).css('padding-left'));}
  if(settings.overlayHeight!==null){overlay_height=settings.overlayWidth;}
  else{overlay_height=parseInt(jQuery(this).height())+parseInt(jQuery(this).css('padding-top'))+parseInt(jQuery(this).css('padding-bottom'));}
  jQuery(overlayDiv).css('width',overlay_width.toString()+'px');jQuery(overlayDiv).css('height',overlay_height.toString()+'px');jQuery(overlayDiv).css('left',overlay_left_pos.toString()+'px');jQuery(overlayDiv).css('position','absolute');jQuery(overlayDiv).css('top',overlay_top_pos.toString()+'px');jQuery(overlayDiv).css('z-index',settings.overlayZIndex);if(settings.overlayCSS){jQuery(overlayDiv).css(settings.overlayCSS);}
  jQuery(loadingDiv).css('display','none');jQuery(document.body).append(loadingDiv);jQuery(loadingDiv).css('position','absolute');jQuery(loadingDiv).css('z-index',settings.indicatorZIndex);var indicatorTop=overlay_top_pos;if(settings.marginTop){indicatorTop+=parseInt(settings.marginTop);}
  var indicatorLeft=overlay_left_pos;if(settings.marginLeft){indicatorLeft+=parseInt(settings.marginTop);}
  if(settings.hPos.toString().toLowerCase()=='center'){jQuery(loadingDiv).css('left',(indicatorLeft+((jQuery(overlayDiv).width()-parseInt(jQuery(loadingDiv).width()))/2)).toString()+'px');}
  else if(settings.hPos.toString().toLowerCase()=='left'){jQuery(loadingDiv).css('left',(indicatorLeft+parseInt(jQuery(overlayDiv).css('margin-left'))).toString()+'px');}
  else if(settings.hPos.toString().toLowerCase()=='right'){jQuery(loadingDiv).css('left',(indicatorLeft+(jQuery(overlayDiv).width()-parseInt(jQuery(loadingDiv).width()))).toString()+'px');}
  else{jQuery(loadingDiv).css('left',(indicatorLeft+parseInt(settings.hPos)).toString()+'px');}
  if(settings.vPos.toString().toLowerCase()=='center'){jQuery(loadingDiv).css('top',(indicatorTop+((jQuery(overlayDiv).height()-parseInt(jQuery(loadingDiv).height()))/2)).toString()+'px');}
  else if(settings.vPos.toString().toLowerCase()=='top'){jQuery(loadingDiv).css('top',indicatorTop.toString()+'px');}
  else if(settings.vPos.toString().toLowerCase()=='bottom'){jQuery(loadingDiv).css('top',(indicatorTop+(jQuery(overlayDiv).height()-parseInt(jQuery(loadingDiv).height()))).toString()+'px');}
  else{jQuery(loadingDiv).css('top',(indicatorTop+parseInt(settings.vPos)).toString()+'px');}
  if(settings.css){jQuery(loadingDiv).css(settings.css);}
  var callback_options={'overlay':overlayDiv,'indicator':loadingDiv,'element':this};if(typeof(settings.beforeShow)=='function'){settings.beforeShow(callback_options);}
  jQuery(overlayDiv).show();jQuery(loadingDiv).show();if(typeof(settings.afterShow)=='function'){settings.afterShow(callback_options);}
  return this;};jQuery.fn.hideLoading=function(options){var settings={};jQuery.extend(settings,options);if(settings.indicatorID){indicatorID=settings.indicatorID;}
else{indicatorID=jQuery(this).attr('id');}
  jQuery(document.body).find('#loading-indicator-'+indicatorID).remove();jQuery(document.body).find('#loading-indicator-'+indicatorID+'-overlay').remove();return this;};
