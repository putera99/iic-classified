// +----------------------------------------------------------------------+
// | ThinkPHP                                                             |
// +----------------------------------------------------------------------+
// | Copyright (c) 2006 liu21st.com All rights reserved.                  |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the 'License');      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// | http://www.apache.org/licenses/LICENSE-2.0                           |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an 'AS IS' BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      |
// | implied. See the License for the specific language governing         |
// | permissions and limitations under the License.                       |
// +----------------------------------------------------------------------+
// | Author: liu21st <liu21st@gmail.com>                                  |
// +----------------------------------------------------------------------+
// $Id$

// Ajax for ThinkPHP
document.write("<div id='ThinkAjaxResult' class='ThinkAjax' ></div>");
var m = {
	'\b': '\\b',
	'\t': '\\t',
	'\n': '\\n',
	'\f': '\\f',
	'\r': '\\r'
};
var ThinkAjax = {
	method:'POST',			//发送方法
	bComplete:false,			//是否完成
	updateTip:'数据处理中～',	//后台处理中提示信息
	updateEffect:{'opacity': [0.1,0.85]},			//更新效果
	image:['','',''], // 图片配置 依次是处理中、成功和错误的显示图片
	tipTarget:'ThinkAjaxResult',	//提示信息对象
	showTip:true,	 // 是否显示提示信息，默认开启
	status:0, //返回状态码
	info:'',	//返回信息
	data:'',	//返回数据
	intval:0,
	options:{}, // 连贯操作的参数
	debug:false,
	activeRequestCount:0,
	// Ajax连接初始化
	getTransport: function() {//lee99
		http_request=false;
		if(window.XMLHttpRequest){//Mozilla浏览器
			http_request=new XMLHttpRequest();
			if (http_request.overrideMimeType){//设置MIME类别
				http_request.overrideMimeType("text/xml");
			}
		} else if(window.ActiveXObject){//IE浏览器
			var versions = ['Microsoft.XMLHTTP', 'MSXML.XMLHTTP', 'Microsoft.XMLHTTP', 'Msxml2.XMLHTTP.7.0', 'Msxml2.XMLHTTP.6.0', 'Msxml2.XMLHTTP.5.0', 'Msxml2.XMLHTTP.4.0', 'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP'];
			for (var i=0; i<versions.length; i++) {
				try {
					http_request = new ActiveXObject(versions[i]);//alert (versions[i]);
				} catch(e) {
					//alert(e.message);
				}
			}
		}
		if(!http_request){//异常，创建对象实例失败
			window.alert("创建XMLHttp对象失败！");
			return false;
		}
		return http_request;
	},
	// 连贯操作方法支持
	// ThinkAjax.url(...).params(...).tip(...).target(...).effect(...).response(...).send()
	tip:function (tips){
		this.options['tip']	=	tips;
		return this;
	},
	effect:function (effect){
		this.options['effect']	=	effect;
		return this;
	},
	target:function (taget){
		this.options['target']	=	target;
		return this;
	},
	response:function (response){
		this.options['response']	=	response;
		return this;
	},
	url:function (url){
		this.options['url']	=	url;
		return this;
	},
	params:function (vars){
		this.options['var']	=	vars;
		return this;
	},
	loading:function (target,tips,effect){
		if ($byid(target))
		{
			//var arrayPageSize = getPageSize();
			var arrayPageScroll = getPageScroll();
			$byid(target).style.display = 'block';
			$byid(target).style.top = (arrayPageScroll[1] +  'px');
			$byid(target).style.right = '0px';
			// 显示正在更新
			if ($byid('loader'))
			{
				$byid('loader').style.display = 'none';
			}
			if ('' != this.image[0])
			{
				$byid(target).innerHTML = '<IMG SRC="'+this.image[0]+'"  BORDER="0" ALT="loading..." align="absmiddle"> '+tips;
			}else{
				$byid(target).innerHTML = tips;
			}
			//使用更新效果
			//var myEffect = $byid(target).effects();
			//myEffect.custom(effect);
		}
	},

	ajaxResponse:function(request,target,response){
		// 获取ThinkPHP后台返回Ajax信息和数据
		// 此格式为ThinkPHP专用格式
		//alert(request.responseText);
		var str	=	request.responseText;
		str  = str.replace(/([\x00-\x1f\\"])/g, function (a, b) {
                    var c = m[b];
                    if (c) {
                        return c;
                    }else{
						return b;
					}
                     }) ;
		if (this.debug)
		{
			// 调试模式下面输出eval前的字符串
			alert(str);
		}
		$return =  eval('(' + str + ')');
		this.status = $return.status;
		this.info	 =	 $return.info;
		this.data = $return.data;

		// 处理返回数据
		// 需要在客户端定义ajaxReturn方法
		if (response == undefined)
		{
			try	{(ajaxReturn).apply(this,[this.data,this.status,this.info]);}
			catch (e){}

		}else {
			try	{ (response).apply(this,[this.data,this.status,this.info]);}
			catch (e){}
		}
		if ($byid(target))
		{
			// 显示提示信息
			if (this.showTip && this.info!= undefined && this.info!=''){
				if (this.status==1)
				{
					if ('' != this.image[1])
					{
						$byid(target).innerHTML	= '<IMG SRC="'+this.image[1]+'"  BORDER="0" ALT="success..." align="absmiddle"> <span style="color:blue">'+this.info+'</span>';
					}else{
						$byid(target).innerHTML	= '<span style="color:blue">'+this.info+'</span>';
					}

				}else{
					if ('' != this.image[2])
					{
						$byid(target).innerHTML	= '<IMG SRC="'+this.image[2]+'"  BORDER="0" ALT="error..." align="absmiddle"> <span style="color:red">'+this.info+'</span>';
					}else{
						$byid(target).innerHTML	= '<span style="color:red">'+this.info+'</span>';
					}
				}
			}
			// 提示信息停留5秒

			if (this.showTip)
			this.intval = window.setTimeout(function (){
				//var myFx = new Fx.Style(target, 'opacity',{duration:1000}).custom(1,0);
				$byid(target).style.display='none';
				},3000);

		}
	},
	// 发送Ajax请求
	send:function(url,pars,response,target,tips,effect)
	{
		var xmlhttp = this.getTransport();
		url = (url == undefined)?this.options['url']:url;
		pars = (pars == undefined)?this.options['var']:pars;
		if (target == undefined)	{
			target = (this.options['target'])?this.options['target']:this.tipTarget;
		}
		if (effect == undefined)	{
			effect = (this.options['effect'])?this.options['effect']:this.updateEffect;
		}
		if (tips == undefined) {
			tips = (this.options['tip'])?this.options['tip']: this.updateTip;
		}
		if (this.showTip)
		{
			this.loading(target,tips,effect);
		}
		if (this.intval)
		{
			window.clearTimeout(this.intval);
		}
		this.activeRequestCount++;
		this.bComplete = false;
		try {
			if (this.method == "GET")
			{
				xmlhttp.open(this.method, url+"?"+pars, true);
				pars = "";
			}
			else
			{
				xmlhttp.open(this.method, url, true);
				xmlhttp.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
				xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			}
			var _self = this;
			xmlhttp.onreadystatechange = function (){
				if (xmlhttp.readyState == 4 ){
					if( xmlhttp.status == 200 && !_self.bComplete)
					{
						_self.bComplete = true;
						_self.activeRequestCount--;
						_self.ajaxResponse(xmlhttp,target,response);
					}
				}
			}
			xmlhttp.send(pars);
		}
		catch(z) { return false; }
	},

	// 获取ajax的结果
	//direct为替换的元素

	get:function(url,pars,direct,response,target,tips,effect)
	{
		var xmlhttp = this.getTransport();
		url = (url == undefined)?this.options['url']:url;
		pars = (pars == undefined)?this.options['var']:pars;
		if (target == undefined)	{
			target = (this.options['target'])?this.options['target']:this.tipTarget;
		}
		if (effect == undefined)	{
			effect = (this.options['effect'])?this.options['effect']:this.updateEffect;
		}
		if (tips == undefined) {
			tips = (this.options['tip'])?this.options['tip']: this.updateTip;
		}
		if (this.showTip)
		{
			this.loading(target,tips,effect);
		}
		if (this.intval)
		{
			window.clearTimeout(this.intval);
		}
		this.activeRequestCount++;
		this.bComplete = false;
		this.method = "GET";
		try {
			if (this.method == "GET")
			{
				//xmlhttp.open(this.method, url+'?'+pars, true);
				xmlhttp.open(this.method, url+pars, true);
				pars = "";
			}
			else
			{
				xmlhttp.open(this.method, url, true);
				xmlhttp.setRequestHeader("Method", "POST "+url+" HTTP/1.1");
				xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			}
			var _self = this;
			xmlhttp.onreadystatechange = function (){
				if (xmlhttp.readyState == 4 ){
					if( xmlhttp.status == 200 && !_self.bComplete)
					{
						_self.bComplete = true;
						_self.activeRequestCount--;
						$byid(direct).innerHTML=xmlhttp.responseText;//lee99
						window.setTimeout(function (){
						$byid(target).style.display='none';
						},1000);
					}
				}
			}
			xmlhttp.send(pars);
		}
		catch(z) { return false; }
	},
	// 发送表单Ajax操作，暂时不支持附件上传
	sendForm:function(formId,url,response,target,tips,effect)
	{
		vars = this.serialize(formId);//lee99
		this.send(url,vars,response,target,tips,effect);
	},
	// 绑定Ajax到HTML元素和事件
	// event 支持根据浏览器的不同
	// 包括 focus blur mouseover mouseout mousedown mouseup submit click dblclick load change keypress keydown keyup
	bind:function(source,event,url,vars,response,target,tips,effect)
	{
		var _self = this;
	   $byid(source).addEvent(event,function (){_self.send(url,vars,response,target,tips,effect)});
	},
	// 页面加载完成后执行Ajax操作
	load:function(url,vars,response,target,tips,effect)
	{
		var _self = this;
	   window.addEvent('load',function (){_self.send(url,vars,response,target,tips,effect)});
	},
	// 延时执行Ajax操作
	time:function(url,vars,time,response,target,tips,effect)
	{
		var _self = this;
		myTimer =  window.setTimeout(function (){_self.send(url,vars,response,target,tips,effect)},time);
	},
	// 定制执行Ajax操作
	repeat:function(url,vars,intervals,response,target,tips,effect)
	{
		var _self = this;
		_self.send(url,vars,response,target,effect);
		myTimer = window.setInterval(function (){_self.send(url,vars,response,target,tips,effect)},intervals);
	},
	// 定制执行serialize操作//lee99
	serialize:function(obj)
	{
		var info = '';
		var elem = '';
		var checkboxname="";
		var checkboxvalue="";
		var radioname="";
		var radiovalue="";
		for(var i=0; i<$byid(obj).elements.length; i++){
			elem = $byid(obj).elements[i];
			if (elem.type=="checkbox") {
				if (elem.checked) {
					var checkboxname=elem.name;
					if (checkboxvalue != '') {
						checkboxvalue += ','+elem.value;
					} else {checkboxvalue = elem.value;}
					//alert(checkboxvalue);
				}
			} else if (checkboxname !='') {
				info += "&"+checkboxname+"="+checkboxvalue;
				checkboxname='';checkboxvalue='';
			}
			if (elem.type=="radio") {
				if (elem.checked) {
					var radioname=elem.name;
					if (radiovalue != '') {
						radiovalue += ','+elem.value;
					} else {radiovalue = elem.value;}
					//alert(radiovalue);
				}
			} else if (radioname !='') {
				info += "&"+radioname+"="+radiovalue;
				radioname='';radiovalue='';
			}
			if (elem.type!="checkbox" && elem.type!="radio") {
				if (info != ''){
					info += '&';
				}
				info += elem.name+"="+elem.value;
			}
		}
		return info;
	}
}
