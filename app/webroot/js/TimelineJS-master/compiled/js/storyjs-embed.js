/*!
	StoryJS
	Designed and built by Zach Wise at VéritéCo

	This Source Code Form is subject to the terms of the Mozilla Public
	License, v. 2.0. If a copy of the MPL was not distributed with this
	file, You can obtain one at http://mozilla.org/MPL/2.0/.
*//* **********************************************
     Begin LazyLoad.js
********************************************** *//*jslint browser: true, eqeqeq: true, bitwise: true, newcap: true, immed: true, regexp: false *//*
LazyLoad makes it easy and painless to lazily load one or more external
JavaScript or CSS files on demand either during or after the rendering of a web
page.

Supported browsers include Firefox 2+, IE6+, Safari 3+ (including Mobile
Safari), Google Chrome, and Opera 9+. Other browsers may or may not work and
are not officially supported.

Visit https://github.com/rgrove/lazyload/ for more info.

Copyright (c) 2011 Ryan Grove <ryan@wonko.com>
All rights reserved.

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the 'Software'), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

@module lazyload
@class LazyLoad
@static
@version 2.0.3 (git)
*/
function getEmbedScriptPath(e){var t=document.getElementsByTagName("script"),n="",r="";for(var i=0;i<t.length;i++)t[i].src.match(e)&&(n=t[i].src);n!=""&&(r="/");return n.split("?")[0].split("/").slice(0,-1).join("/")+r}function createStoryJS(e,t){function n(){LoadLib.js(E.js,r)}function r(){b.js=!0;E.lang!="en"?LazyLoad.js(w.locale,i):b.language=!0;a()}function i(){b.language=!0;a()}function s(){b.css=!0;a()}function o(){b.font.css=!0;a()}function u(){b.font.js=!0;a()}function a(){if(b.checks>40)return;b.checks++;if(b.js&&b.css&&b.font.css&&b.font.js&&b.language){if(!b.finished){b.finished=!0;l()}}else b.timeout=setTimeout("onloaded_check_again();",250)}function f(){var e="storyjs-embed";h=document.createElement("div");E.embed_id!=""?p=document.getElementById(E.embed_id):p=document.getElementById("timeline-embed");p.appendChild(h);h.setAttribute("id",E.id);if(E.width.toString().match("%"))p.style.width=E.width.split("%")[0]+"%";else{E.width=E.width-2;p.style.width=E.width+"px"}if(E.height.toString().match("%")){p.style.height=E.height;e+=" full-embed";p.style.height=E.height.split("%")[0]+"%"}else if(E.width.toString().match("%")){e+=" full-embed";E.height=E.height-16;p.style.height=E.height+"px"}else{e+=" sized-embed";E.height=E.height-16;p.style.height=E.height+"px"}p.setAttribute("class",e);p.setAttribute("className",e);h.style.position="relative"}function l(){VMM.debug=E.debug;c=new VMM.Timeline(E.id);c.init(E);v&&VMM.bindEvent(global,onHeadline,"HEADLINE")}var c,h,p,d,v=!1,m="2.24",g="1.7.1",y="",b={timeout:"",checks:0,finished:!1,js:!1,css:!1,jquery:!1,has_jquery:!1,language:!1,font:{css:!1,js:!1}},w={base:embed_path,css:embed_path+"css/",js:embed_path+"js/",locale:embed_path+"js/locale/",font:{}},E={version:m,debug:!1,type:"timeline",id:"storyjs",embed_id:"timeline-embed",embed:!0,width:"100%",height:"100%",source:"",lang:"en",font:"default",css:w.css+"timeline.css?"+m,js:"",api_keys:{google:"",flickr:"",twitter:""},gmap_key:""},S=[];if(typeof e=="object")for(d in e)Object.prototype.hasOwnProperty.call(e,d)&&(E[d]=e[d]);typeof t!="undefined"&&(E.source=t);if(typeof url_config=="object"){v=!0;E.source.match("docs.google.com")||E.source.match("json")||E.source.match("storify")||(E.source="https://docs.google.com/spreadsheet/pub?key="+E.source+"&output=html")}if(E.js.match("locale")){E.lang=E.js.split("locale/")[1].replace(".js","");E.js=w.js+"timeline-min.js?"+m}if(!E.js.match("/")){E.css=w.css+E.type+".css?"+m;E.js=w.js+E.type;E.debug?E.js+=".js?"+m:E.js+="-min.js?"+m;E.id="storyjs-"+E.type}E.lang.match("/")?w.locale=E.lang:w.locale=w.locale+E.lang+".js?"+m;f();LoadLib.css(E.css,s);if(E.font=="default"){b.font.js=!0;b.font.css=!0}else{var x;if(E.font.match("/")){x=E.font.split(".css")[0].split("/");w.font.name=x[x.length-1];w.font.css=E.font}else{w.font.name=E.font;w.font.css=w.font.css+E.font+".css?"+m}LoadLib.css(w.font.css,o);for(var T=0;T<S.length;T++)if(w.font.name==S[T].name){w.font.google=!0;WebFontConfig={google:{families:S[T].google}}}w.font.google?LoadLib.js(w.font.js,u):b.font.js=!0}try{b.has_jquery=jQuery;b.has_jquery=!0;if(b.has_jquery){var y=parseFloat(jQuery.fn.jquery);y<parseFloat(g)?b.jquery=!1:b.jquery=!0}}catch(N){b.jquery=!1}b.jquery?n():LoadLib.js(w.jquery,n);this.onloaded_check_again=function(){a()}}LazyLoad=function(e){function t(t,n){var r=e.createElement(t),i;for(i in n)n.hasOwnProperty(i)&&r.setAttribute(i,n[i]);return r}function n(e){var t=f[e],n,r;if(t){n=t.callback;r=t.urls;r.shift();l=0;if(!r.length){n&&n.call(t.context,t.obj);f[e]=null;c[e].length&&i(e)}}}function r(){var t=navigator.userAgent;u={async:e.createElement("script").async===!0};(u.webkit=/AppleWebKit\//.test(t))||(u.ie=/MSIE/.test(t))||(u.opera=/Opera/.test(t))||(u.gecko=/Gecko\//.test(t))||(u.unknown=!0)}function i(i,l,h,p,d){var v=function(){n(i)},m=i==="css",g=[],y,b,w,E,S,x;u||r();if(l){l=typeof l=="string"?[l]:l.concat();if(m||u.async||u.gecko||u.opera)c[i].push({urls:l,callback:h,obj:p,context:d});else for(y=0,b=l.length;y<b;++y)c[i].push({urls:[l[y]],callback:y===b-1?h:null,obj:p,context:d})}if(f[i]||!(E=f[i]=c[i].shift()))return;a||(a=e.head||e.getElementsByTagName("head")[0]);S=E.urls;for(y=0,b=S.length;y<b;++y){x=S[y];if(m)w=u.gecko?t("style"):t("link",{href:x,rel:"stylesheet"});else{w=t("script",{src:x});w.async=!1}w.className="lazyload";w.setAttribute("charset","utf-8");if(u.ie&&!m)w.onreadystatechange=function(){if(/loaded|complete/.test(w.readyState)){w.onreadystatechange=null;v()}};else if(m&&(u.gecko||u.webkit))if(u.webkit){E.urls[y]=w.href;o()}else{w.innerHTML='@import "'+x+'";';s(w)}else w.onload=w.onerror=v;g.push(w)}for(y=0,b=g.length;y<b;++y)a.appendChild(g[y])}function s(e){var t;try{t=!!e.sheet.cssRules}catch(r){l+=1;l<200?setTimeout(function(){s(e)},50):t&&n("css");return}n("css")}function o(){var e=f.css,t;if(e){t=h.length;while(--t>=0)if(h[t].href===e.urls[0]){n("css");break}l+=1;e&&(l<200?setTimeout(o,50):n("css"))}}var u,a,f={},l=0,c={css:[],js:[]},h=e.styleSheets;return{css:function(e,t,n,r){i("css",e,t,n,r)},js:function(e,t,n,r){i("js",e,t,n,r)}}}(this.document);LoadLib=function(e){function t(e){var t=0,r=!1;for(t=0;t<n.length;t++)n[t]==e&&(r=!0);if(r)return!0;n.push(e);return!1}var n=[];return{css:function(e,n,r,i){t(e)||LazyLoad.css(e,n,r,i)},js:function(e,n,r,i){t(e)||LazyLoad.js(e,n,r,i)}}}(this.document);var WebFontConfig;if(typeof embed_path=="undefined"||typeof embed_path=="undefined")var embed_path=getEmbedScriptPath("storyjs-embed.js").split("js/")[0];(function(){typeof url_config=="object"?createStoryJS(url_config):typeof timeline_config=="object"?createStoryJS(timeline_config):typeof storyjs_config=="object"?createStoryJS(storyjs_config):typeof config=="object"&&createStoryJS(config)})()
