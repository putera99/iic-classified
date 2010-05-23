﻿/*!
 * lhgcore Dialog Plugin v3.0.2
 * Date : 2010-04-21 09:03:11
 * Copyright (c) 2009 - 2010 By Li Hui Gang
 */
(function(a){function E(){for(var f=a("script"),r="",c=0,d=f.length;c<d;c++)if(f[c].src.indexOf("lhgdialog.js")>=0){r=document.querySelector?f[c].src:f[c].getAttribute("src",4);break}return r.substr(0,r.lastIndexOf("/")+1)}function w(){return a.browser.ie?a.browser.i7?"":"javascript:''":"javascript:void(0);"}function A(){v||(v=999);return++v}function B(){var f=a.root(m);a(q).css({width:Math.max(f.scrollWidth,f.clientWidth||0)-1+"px",height:Math.max(f.scrollHeight,f.clientHeight||0)-1+"px"})}function x(f){f=f||window;if("pageXOffset"in f)return{x:f.pageXOffset||0,y:f.pageYOffset||0};else{f=a.root(f.document);return{x:f.scrollLeft||0,y:f.scrollTop||0}}}function y(f){f=f||window;f=a.root(f.document);return{w:f.clientWidth||0,h:f.clientHeight||0}}function F(){a(k).remove();k=null;if(q){a(q).remove();q=null}}for(var j=window,q,m,k,v;j.parent!=j;)j=j.parent;m=j.document;a.fn.dialog=function(f){var r=false;if(this[0])r=new a.dialog(f,this[0]);return r};a.dialog=function(f,r){var c=this.opt=a.extend({height:300,width:400,id:"lhgdlgId",event:"click",link:false,btns:true,drag:true,resize:true,title:"lhgdialog",regDragWindow:[]},f||{});if(c.SetTopWindow){j=c.SetTopWindow;m=j.document}a("#lhgdlgcss",m)[0]||a("head",m).prepend('<link type="text/css" href="'+E()+'lhgdialog.css" id="lhgdlgcss" rel="stylesheet"/>');var d=this,z="",C,D=a.browser.ie&&!a.browser.i7?'<iframe hideFocus="true" frameborder="0" src="'+w()+'" style="position:absolute;z-index:-1;width:100%;height:100%;top:0px;left:0px;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0)"></iframe>':"";if(c.html&&typeof c.html==="string")z=c.html;else if(c.page)z=['<iframe width="100%" height="100%" id="lhgfrm" src="',c.page,'" frameborder="0" scrolling="auto"></iframe>'].join("");C=['<div id="',c.id,'" class="lhgdig" style="width:',c.width,"px;height:",c.height,'px;"><div id="top"><span class="t_m" id="drag"><em id="txt">',c.title,'</em></span><div id="xbtn" class="xbtn"></div></div><span class="t_l"></span><span class="t_r"></span><div id="middle"><span class="m_l" id="m_l"></span><span class="m_r"></span><div id="context"><div id="inbox">',z,"</div>",c.btns?'<div id="btns"></div>':"",'</div></div><div id="foot"><div class="b_m"></div></div><span class="b_l"></span><span class="b_r" id="drop"></span>',D,'<div id="throbber"><span>loading...</span></div></div>'].join("");k||(k=a('<div id="cDiv" style="position:absolute;top:0px;left:0px;border:1px solid #000;background-color:#999;display:none;"></div>',m).css("opacity",0.3).appendTo("body").bind("contextmenu",function(b){b.preventDefault()})[0]);this.ShowDialog=function(){if(!a("#"+c.id,m)[0]){c.cover&&this.ShowCover();var b=y(j),e=x(j),o=c.top?c.top:Math.max(e.y+(b.h-c.height-20)/2,0);b=c.left?c.left:Math.max(e.x+(b.w-c.width-20)/2,0);this.dlg=a(C,m).css({top:o+"px",left:b+"px",zIndex:A()}).appendTo(m.body)[0];this.reSizeContent();this.setDialog(this.dlg);c.html&&c.cusfn&&c.cusfn();c.drag&&this.initDrag(a("#drag",this.dlg)[0]);c.resize&&this.initSize(a("#drop",this.dlg)[0]);if(c.link||c.html)a("#throbber",this.dlg).css("display","none")}};this.ShowCover=function(){if(!q){var b=['<div style="position:absolute;top:0px;left:0px;background-color:#fff;">',D,"</div>"].join("");q=a(b,m).css("opacity",0.5).appendTo(m.body)[0]}a(j).bind("resize",B);B();a(q).css({display:"",zIndex:A()})};this.cancel=function(){var b=a("#lhgfrm",d.dlg)[0];if(b){d.opt.link||a(b.contentWindow).unbind("load");b.src=w()}d.regWindow=[];a(d.dlg).remove();d.dlg=null;if(q)if(d.opt.parent&&d.opt.parent.opt.cover)q.style.zIndex=parseInt(d.opt.parent.dlg.style.zIndex,10)-1;else q.style.display="none"};this.reload=function(b,e){b=b||window;d.cancel();b.location.href=e?e:b.location.href};this.reSizeContent=function(b){var e=this.dlg;b=b?b:e.offsetHeight;var o=a("#top",e)[0].offsetHeight,p=a("#foot",e)[0].offsetHeight;b=b-o-p;a("#middle,#throbber",e).css("height",b+"px");a("#context,#throbber",e).css("width",e.offsetWidth-a("#m_l",e)[0].offsetWidth*2+"px");a("#inbox",e).css("height",b-(c.btns?a("#btns",e)[0].offsetHeight:0)+"px");a.browser.ie&&m.compatMode!=="CSS1Compat"&&a("#context",e).css("top","0px");a.browser.i8&&c.page&&a("#lhgfrm",e).css("height",a("#inbox",e)[0].style.height)};this.reDialogSize=function(b,e){a(this.dlg).css({width:b+"px",height:e+"px"});this.reSizeContent(a.browser.ie&&!a.browser.i7?e:"")};this.setDialog=function(b){this.win=window;this.top=j;a(b).bind("contextmenu",function(e){e.preventDefault()}).bind("mousedown",d.setIndex);a("#xbtn",b).hover(function(){a(this).addClass("xbtnover")},function(){a(this).removeClass("xbtnover")}).click(d.cancel);c.html&&c.html.nodeType&&a("#inbox",b).html(c.html);this.regWindow=[window];c.regDragWindow.length>0&&this.regWindow.push(c.regDragWindow);j!=window&&this.regWindow.push(j);if(c.page&&!c.link){this.inwin=a("#lhgfrm",b)[0].contentWindow;this.inwin.dg=this;this.regWindow.push(this.inwin);b=this.inwin.document;a(a.browser.ie?b:this.inwin).bind("mousedown",d.setIndex);a(this.inwin).bind("load",function(){d.indoc=d.inwin.document;a("#throbber",d.dlg).css("display","none")})}};this.setIndex=function(b){d.dlg.style.zIndex=parseInt(v,10)+1;v=parseInt(d.dlg.style.zIndex,10);b.stopPropagation()};this.addBtn=function(b,e,o){a("#"+b,this.dlg)[0]?a("#"+b,this.dlg).html(e).click(o):a('<div class="button" id="'+b+'">'+e+"</div>",m).click(o).hover(function(){a(this).addClass("btnover")},function(){a(this).removeClass("btnover")}).appendTo("#btns")};this.removeBtn=function(b){(b=a("#"+b,this.dlg)[0])&&a(b).remove()};this.initDrag=function(b){function e(h){h={x:h.screenX,y:h.screenY};g={x:g.x+(h.x-p.x),y:g.y+(h.y-p.y)};p=h;if(c.rang){if(g.x<i.x)g.x=i.x;if(g.y<i.y)g.y=i.y;if(g.x+s>l.w+i.x)g.x=l.w+i.x-s;if(g.y+t>l.h+i.y)g.y=l.h+i.y-t}a(k).css({left:g.x+"px",top:g.y+"px"})}function o(){for(var h=0,u=n.length;h<u;h++){a(n[h].document).unbind("mousemove",e);a(n[h].document).unbind("mouseup",o)}a.browser.ie&&k.releaseCapture();k.style.display="none";b=p=null;a(d.dlg).css({left:g.x+"px",top:g.y+"px"})}var p,t,s,g,n=this.regWindow,l=y(j),i=x(j);a(b).bind("mousedown",function(h){if(h.target.id!=="xbtn"){s=d.dlg.offsetWidth;t=d.dlg.offsetHeight;g={x:d.dlg.offsetLeft,y:d.dlg.offsetTop};p={x:h.screenX,y:h.screenY};a(k).css({width:s+"px",height:t+"px",left:g.x+"px",top:g.y+"px",zIndex:parseInt(v,10)+2,display:""});for(var u=0,G=n.length;u<G;u++){a(n[u].document).bind("mousemove",e);a(n[u].document).bind("mouseup",o)}h.preventDefault();a.browser.ie&&k.setCapture()}})};this.initSize=function(b){function e(i){i={x:i.screenX,y:i.screenY};l={w:i.x-p.x,h:i.y-p.y};if(l.w<200)l.w=200;if(l.h<100)l.h=100;a(k).css({width:l.w+"px",height:l.h+"px",top:g.y+"px",left:g.x+"px"})}function o(){for(var i=0,h=n.length;i<h;i++){a(n[i].document).unbind("mousemove",e);a(n[i].document).unbind("mouseup",o)}a.browser.ie&&k.releaseCapture();d.reDialogSize(l.w,l.h);k.style.display="none";b=p=null}var p,t,s,g,n=this.regWindow,l;y(j);x(j);a(b).bind("mousedown",function(i){s=d.dlg.offsetWidth;t=d.dlg.offsetHeight;g={x:d.dlg.offsetLeft,y:d.dlg.offsetTop};p={x:i.screenX-s,y:i.screenY-t};a(k).css({width:s+"px",height:t+"px",left:g.x+"px",top:g.y+"px",zIndex:parseInt(v,10)+2,display:""});for(var h=0,u=n.length;h<u;h++){a(n[h].document).bind("mousemove",e);a(n[h].document).bind("mouseup",o)}i.preventDefault();a.browser.ie&&k.setCapture()})};this.cleanDialog=function(){if(d.dlg){var b=a("#lhgfrm",d.dlg)[0];if(b){d.opt.link||a(b.contentWindow).unbind("load");b.src=w()}a(d.dlg).remove();d.dlg=null}};a(window).bind("unload",this.cleanDialog);r&&a(r).bind(c.event,function(){d.ShowDialog()})};a(window).bind("unload",F)})(lhgcore);