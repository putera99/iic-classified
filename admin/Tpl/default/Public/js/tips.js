	// 获取元素坐标
	/*
===========================================================
popHint(Element, Hint, {Type, Event});
===========================================================
Element：弹出对象。根据它来定位的。
Hint：弹出的信息。
Type：弹出类型。其实说类型是不对的。只是定义个图标而已...(可自己在样式里加很多很多"类型")
Event：关闭触发事件。(默认click=document.onmousedown,blur=Element.onblur) 一样可以自己定义很多事件. 
*/
	var getCoords = function(node)
	{
		var x = node.offsetLeft;
		var y = node.offsetTop;
		var parent = node.offsetParent;
		while (parent != null){
			x += parent.offsetLeft;
			y += parent.offsetTop;
			parent = parent.offsetParent;
		}
		return {x: x, y: y};
	},
	// 事件操作(可保留原有事件)
	eventListeners = [],
	v
	findEventListener = function(node, event, handler)
	{
		var i;
		for (i in eventListeners){
			if (eventListeners[i].node == node && eventListeners[i].event == event && eventListeners[i].handler == handler){
				return i;
			}
		}
		return null;
	},
	myAddEventListener = function(node, event, handler)
	{
		if (findEventListener(node, event, handler) != null){
			return;
		}
		if (!node.addEventListener){
			node.attachEvent('on' + event, handler);
		}else{
			node.addEventListener(event, handler, false);
		}
		eventListeners.push({node: node, event: event, handler: handler});
	},
	removeEventListenerIndex = function(index)
	{
		var eventListener = eventListeners[index];
		delete eventListeners[index];
		if (!eventListener.node.removeEventListener){
			eventListener.node.detachEvent('on' + eventListener.event,
			eventListener.handler);
		}else{
			eventListener.node.removeEventListener(eventListener.event,
			eventListener.handler, false);
		}
	},
	myRemoveEventListener = function(node, event, handler)
	{
		var index = findEventListener(node, event, handler);
		if (index == null) return;
		removeEventListenerIndex(index);
	},
	cleanupEventListeners = function()
	{
		var i;
		for (i = eventListeners.length; i > 0; i--){
			if (eventListeners[i] != undefined){
				removeEventListenerIndex(i);
			}
		}
	},appendElement = function(tagName, Attribute, strHtml, refNode) {
	var cEle = document.createElement(tagName);
	// 属性值
	for (var i in Attribute){
		cEle.setAttribute(i, Attribute[i]);
	}
	cEle.innerHTML = strHtml;
	
	refNode.appendChild(cEle);
	return cEle;
	};
	function popHint(obj, msg, initValues) {
	var
	_obj = obj,
	_objHint = $byid("popHint"),
	_objHintIframe = $byid("popHintIframe"),
	_msg = msg,
	_init = initValues;
	// 初始化失败...
	if(_obj==undefined || _msg==undefined || _msg=="") return;
	// 设置初始值
	_init = _init==undefined ? {_type : "wrong", _event : "click"} : _init;
	// obj如果不可见。设置弹出对象为obj父元素
	if(_obj.style.display=='none' || _obj.style.visibility=='hidden' || _obj.getAttribute('type')=='hidden') _obj = _obj.parentNode;
	var
	_type = null,
	_event = null,
	_place = getCoords(_obj),
	_marTop = null,
	_objText = $byid("popHintText"),
	_objHintIframe = $byid("popHintIframe"),
	// 初始化
	init = function() {
		var _hint = _obj.getAttribute("hint");
		if(_hint=="false") return;
		// 有的时候initValues不为空.但是只设置一个值...避免发生错误.再次设置初始值...
		_type = _init._type==undefined ? "wrong" : _init._type;
		_type = _type.toLowerCase();
		_event = _init._event==undefined ? "click" : _init._event;
		_event = _event.toLowerCase();
		// 好了.输出...
		var _Html = "<div id=\"popHeader\">" +
					"	<div class=\"popLeft\"></div>" +
					"	<div id=\"popHintText\"></div>" +
					"	<div class=\"popRight\"></div>" +
					"</div>"+
					"<div class=\"popAngle\"><span></span></div>";
		var _HtmlIframe = "<iframe id='msg_div_all_Iframe' style='display:none;'></iframe>";
		if(_objHint==null) {
			_objHint = appendElement("div", {"id" : "popHint"}, _Html, document.body);
			_objHint.style.display = "none";
			_objText = $byid("popHintText");
		}
		if(_objHintIframe==null) {
			try{	//IE
			document.body.appendChild(document.createElement("<iframe id='popHintIframe'></iframe>"));
			_objHintIframe = $byid("popHintIframe");
		}catch(e)
		{}
		}
		show();
	},
	// 显示
	show = function() {
		_objHint.style.display = "";
		_marTop = _objHint.offsetHeight;
		
		_msg = "<span class=\"popIcon "+ _type +"\"></span>"+ _msg;
		_objText.innerHTML = _msg;
		_objHint.style.zIndex=10;
		_objHint.style.left = _place.x +"px";
		_objHint.style.top = (_place.y-_marTop+8) +"px";
		if(document.all)
		{
			_objHintIframe.style.left=_place.x +"px";
			_objHintIframe.style.zIndex=9;
			_objHintIframe.style.top = (_place.y-_marTop+8) +"px";
			_objHintIframe.style.width=$byid("popHintText").offsetWidth+"px";
			_objHintIframe.style.height=$byid("popHintText").offsetHeight+"px";
			_objHintIframe.style.position="absolute";
			_objHintIframe.style.display="";
		}
		
		// 关闭触发事件
		switch(_event) {
			case "blur" :
				myAddEventListener(_obj, 'blur', hide);
				break;
			//default :
			case "click" :
				myAddEventListener(document, 'mousedown', hide);
				break;
			//这里可以自己扩展很多事件...
		}

	},
	// 关闭
	hide = function() {
		_objHint.style.display = "none";
		_objText.innerHTML = "";
		// 移除关闭触发事件
		myRemoveEventListener(_obj, 'blur', hide);
		myRemoveEventListener(document, 'mousedown', hide);
		if(document.all){_objHintIframe.style.display="none";}
	};
	
	init();
}

