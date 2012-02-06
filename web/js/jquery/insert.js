/*
 * Insert Script Plugin
 *
 * Copyright (c) 2008 Kevin Martin (http://synarchydesign.com/insert)
 * Licensed under the GPL license:
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.insert=function(a){var b=[];var c=[];if(typeof a=='object'){b=a;a=b.src!==undefined?b.src:false;a=a===false&&b.href!==undefined?b.href:a;a=a===false?file2:false}if(typeof a=='string'&&a.length){var d=a.lastIndexOf('.');var e=a.replace('\\','/').lastIndexOf('/')+1;var f=a.substring(d+1,a.length)}switch(f){case'js':c={elm:'script',type:'text/javascript',src:a};break;case'css':c={elm:'link',rel:'stylesheet',type:'text/css',href:a};break;default:c={elm:'link'};break}c.id='script-'+(typeof a=='string'&&a.length?a.substring(e,d):Math.round(Math.rand()*100));for(var i in b){c[i]=b[i]}b=c;var g=document.createElement(b.elm);delete b.elm;for(i in b){g.setAttribute(i,b[i])}jQuery('head').append(g);return jQuery('#'+b.id)};