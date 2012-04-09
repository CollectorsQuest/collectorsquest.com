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