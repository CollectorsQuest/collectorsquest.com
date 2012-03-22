/** frontend/common-plugins.js */

/* make it safe to use console.log always */
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

/* http://davidwalsh.name/jquery-top-link */
jQuery.fn.topLink=function(settings){settings=jQuery.extend({min:1,fadeSpeed:200},settings);return this.each(function(){var el=$(this);el.hide();$(window).scroll(function(){if($(window).scrollTop()>=settings.min){el.fadeIn(settings.fadeSpeed);}else{el.fadeOut(settings.fadeSpeed);}});});};
