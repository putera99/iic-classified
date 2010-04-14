<?php
import('TagLib');
Class TagLibMkrtags extends TagLib{
	public function _input($attr){
		$tag        = $this->parseXmlAttr($attr,'input');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = $tag['class']; 			//表单class//没值则用NAME为值
		$style      = $tag['style']; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则

		$parseStr   = '<INPUT type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'"  class="'.$class.'"  style="'.$style.'" '.$othervar.' '. $disabled.' '. $readonly. '>';

		return $parseStr;
	}


	public function _files($attr){
		$tag        = $this->parseXmlAttr($attr,'files');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则

		$parseStr   = '<INPUT type="file" id="'.$id.'" name="'.$name.'" >';

		return $parseStr;
	}

	public function _select($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'select');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$value      =(strpos($tag['value'],'$')===false )?'"'.$tag['value'].'"':$tag['value'];		//值和变量
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		$outtable   = $tag['outtable'];               					//表单[outtable]//没值则
		$outkey     = $tag['outkey'];               					//表单[outkey]//没值则
		$outfield   = $tag['outfield'];               					//表单[outfield]//没值则
		$outcondition   = $tag['outcondition'];               			//表单[outcondition]//没值则
		$outorder   = $tag['outorder'];               					//表单[outorder]//没值则
		$outadd     = $tag['outadd'];               					//表单[outadd]//没值则
		if(!empty($outtable)){
			if($outtable=='Array'){
				$optionval=explode(',',$outfield);
				if(!empty($outkey)){
					$optionkey=explode(',',$outkey);
					$options=array_combine($optionkey,$optionval);
				}else{
					$options=$optionval;
				}
				$parseStr   = '<php>  $options=array();$options=array(';

				foreach($options as $key=>$val){
					$selectoption[]  = '"'.$key.'" => "'.$val.'"';
				}
				$parseStr .=join(',',$selectoption);
				$parseStr .= '); </php>';
			}else{
				$options=makeoption($outtable,$outkey,$outcondition,$outfield,$outorder,$outadd);
				$parseStr   .= '
				<php>  $options=makeoption("'.$outtable.'","'.$outkey.'","'.$outcondition.'","'.$outfield.'","'.$outorder.'","'.$outadd.'"); </php>
				';
			}
		}


		$parseStr .= '<select id="'.$id.'" name="'.$name.'" class="'.$class.'"  style="'.$style.'" >';

		if(!empty($options)) {
			$parseStr   .= '<php>  foreach($options as $key=>$val) { </php>';
			if(!empty($value)) {
				$parseStr   .='<php> if('.$value.'== $key) { </php>';
				$parseStr   .= '<option selected="selected" value="<php> echo $key </php>"><php> echo $val </php></option>';
				$parseStr   .= '<php> }else { </php>';
				$parseStr   .= '<option value="<php> echo $key </php>"><php> echo $val </php></option>';
				$parseStr   .= '<php> } </php>';
			}else {
				$parseStr   .= '<option value="<php> echo $key </php>"><php> echo $val </php></option>';
			}
			$parseStr   .= '<php> } </php>';
			$parseStr   .= '<php> unset($options,$key,$val); </php>';
		}
		$parseStr   .= '</select>';
		return $parseStr;
	}

	public function _outtable($attr){
		$tag        = $this->parseXmlAttr($attr,'outtable');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?'"'.$tagvalue.'"':$tagvalue;		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		$outtable   = $tag['outtable'];               					//表单[outtable]//没值则
		$outkey     = $tag['outkey'];               					//表单[outkey]//没值则
		$outfield   = $tag['outfield'];               					//表单[outfield]//没值则
		$outcondition   = $tag['outcondition'];               			//表单[outcondition]//没值则
		$outorder   = $tag['outorder'];               					//表单[outorder]//没值则
		$outadd     = $tag['outadd'];               					//表单[outadd]//没值则
		if(!empty($outtable)){
			if($outtable=='Array'){
				$optionval=explode(',',$outfield);
				if(!empty($outkey)){
					$optionkey=explode(',',$outkey);
					$options=array_combine($optionkey,$optionval);
				}else{
					$options=$optionval;
				}
				$parseStr   = '<php>  $options=array();$options=array(';

				foreach($options as $key=>$val){
					$selectoption[]  = '"'.$key.'" => "'.$val.'"';
				}
				$parseStr .=join(',',$selectoption);
				$parseStr .= '); </php>';
			}else{
				$options=makeoption($outtable,$outkey,$outcondition,$outfield,$outorder,$outadd);
				$parseStr   .= '<php>  $options=makeoption("'.$outtable.'","'.$outkey.'","'.$outcondition.'","'.$outfield.'","'.$outorder.'","'.$outadd.'"); </php>';
			}
		}


		if(!empty($options)) {
			$parseStr   .= '<php>  foreach($options as $key=>$val) { </php>';
				if(substr($value,0,1)=="$"){//如果是变量
					$tempvalue=explode('.',$value);
					if(count($tempvalue)>1){//如果是数组
						$value=$tempvalue[0].'["'.$tempvalue[1].'"]';
					}
				}else{
					$value=$value;
				}
				$parseStr   .='<php> if('.$value.'== $key) { </php>';
				$parseStr   .= '<php> echo $val </php>';
				$parseStr   .= '<php> } </php>';
			$parseStr   .= '<php> } </php>';
		}
		return $parseStr;
	}


	/**
	 * select多选
	 * @param $attr
	 */
	public function _selectmultiple($attr){
		$tag        = $this->parseXmlAttr($attr,'selectmultiple');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		$outtable   = $tag['outtable'];               					//表单[outtable]//外联的数据表
		$outkey     = $tag['outkey'];               					//表单[outkey]//外键的取值键名
		$outfield   = $tag['outfield'];               					//表单[outfield]//外键显示的键名
		$outcondition   = $tag['outcondition'];               			//表单[outcondition]//外联条件
		$outorder   = $tag['outorder'];               					//表单[outorder]//没值则
		$outadd     = $tag['outadd'];               					//表单[outadd]//没值则
		$parseStr='';
		if(!empty($outtable)){
			//$optionval=$options=array();
			if($outtable=='Array'){
				$optionval=explode(',',$outfield);
				if(!empty($outkey)){
					$optionkey=explode(',',$outkey);
					$options=array();
					$options=array_combine($optionkey,$optionval);
				}else{
					$options=$optionval;
				}
				$parseStr   .= '<php>  $options=array(';

				foreach($options as $key=>$val){
					$parseStr   .= '"'.$key.'" => "'.$val.'", ';
				}
				$parseStr .= '); </php>';
			}else{
				$options=makeoption($outtable,$outkey,$outcondition,$outfield,$outorder,$outadd);
				$parseStr   .= '<php>  $options=makeoption("'.$outtable.'","'.$outkey.'","'.$outcondition.'","'.$outfield.'","'.$outorder.'","'.$outadd.'"); </php>';
			}
		}
		$parseStr   .= '<script language="JavaScript" src="../Public/mkrtagsjs/selectmultiple.js"></script>';
		$parseStr   .= '<php>
		 $value="'.$value.'";//写入相应的值
		 $tmpvale=explode(",",$value);//看看是不是多选的值,以","号为分隔
		 //dump($tmpvale);
		if(count($tmpvale)>1){ $value=$tmpvale; }else{ $value=$value; } </php>';

		$parseStr   .= '
  <table border="0" width="400">
    <tr>
      <th><center>可选择的项目</center></th>
      <th></th>
      <th><center>已选择的项目</center></th>
    </tr>
    <tr>
      <td width="40%"><select style="width:100%;" multiple name="'.$name.'left" id="'.$name.'left" size="8"  ondblclick="moveSelected(document.getElementById("'.$name.'left"),document.getElementById("'.$name.'right"));" >';
		if(!empty($options)) {
			$parseStr   .= '<php>  foreach($options as $key=>$val) { </php>';
			if(!empty($value)) {
				$parseStr   .='<php> if($value== $key  || in_array($key,$value) ) { </php>';
				$parseStr   .= ' ';
				$parseStr   .= '<php> }else { </php><option value="<php> echo $key </php>"><php> echo $val </php></option>';
				$parseStr   .= '<php> } </php>';
			}else {
				$parseStr   .= '<option value="<php> echo $key </php>"><php> echo $val </php></option>';
			}
			$parseStr   .= '<php> } </php>';
		}
		$parseStr   .= '
        </select>
      </td>
      <td  align="center">
		<input type="button" value=">>>>>>>" onClick="moveAll(document.all.'.$name.'left,document.all.'.$name.'right);"><br>
        <input type="button" value="<<<<<<<" onClick="moveAll(document.all.'.$name.'right,document.all.'.$name.'left);"><br>
		<input type="button" value="上移一格" onClick="moveUp(document.all.'.$name.'right);moveUp(document.all.'.$name.'left);"><br>
        <input type="button" value="下移一格" onClick="moveDown(document.all.'.$name.'right);moveDown(document.all.'.$name.'left);"><br>
        <input type="button" value="上移到顶" onClick="moveUp(document.all.'.$name.'right,true);moveUp(document.all.'.$name.'left,true);"><br>
        <input type="button" value="下移到顶" onClick="moveDown(document.all.'.$name.'right,true);moveDown(document.all.left,true);"><br>

      </td>
      <td width="40%"><select style="width:100%;" multiple name="'.$name.'right"  id="'.$name.'right" size="8"  ondblclick="moveSelected(document.getElementById("'.$name.'right"),document.getElementById("'.$name.'left"));">';
		if(!empty($options)) {
			$parseStr   .= '<php>  foreach($options as $key=>$val) { </php>';
			if(!empty($value)) {
				$parseStr   .='<php> if($value== $key  || in_array($key,$value) ) { </php>';
				$parseStr   .= '<option selected="selected" value="<php> echo $key </php>"><php> echo $val </php></option>';
				$parseStr   .= '<php> }</php>';
			}else {
				//$parseStr   .= '<option value="<php> echo $key </php>"><php> echo $val </php></option>';
				$parseStr   .= '';
			}
			$parseStr   .= '<php> } </php>';
		}
		$parseStr   .= '

        </select>
      </td>
    </tr>
  </table>';
		return $parseStr;
	}


	public function _radio($attr){
		$tag        = $this->parseXmlAttr($attr,'radio');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$value      =(strpos($tag['value'],'$')===false )?'"'.$tag['value'].'"':$tag['value'];		//值和变量
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		$outtable   = $tag['outtable'];               					//表单[outtable]//没值则
		$outkey     = $tag['outkey'];               					//表单[outkey]//没值则
		$outfield   = $tag['outfield'];               					//表单[outfield]//没值则
		$outcondition   = $tag['outcondition'];               			//表单[outcondition]//没值则
		$outorder   = $tag['outorder'];               					//表单[outorder]//没值则
		$outadd     = $tag['outadd'];               					//表单[outadd]//没值则
		if(!empty($outtable)){
			if($outtable=='Array'){
				$optionval=explode(',',$outfield);
				if(!empty($outkey)){
					$optionkey=explode(',',$outkey);
					$options=array_combine($optionkey,$optionval);
				}else{
					$options=$optionval;
				}
				$parseStr   = '<php>  $options=array(';

				foreach($options as $key=>$val){
					$radiooption[]  = '"'.$key.'" => "'.$val.'"';
				}
				$parseStr .=join(',',$radiooption);
				$parseStr .= '); </php>';
			}else{
				$options=makeoption($outtable,$outkey,$outcondition,$outfield,$outorder,$outadd);
				$parseStr   .= '<php>
				 $options=makeoption("'.$outtable.'","'.$outkey.'","'.$outcondition.'","'.$outfield.'","'.$outorder.'","'.$outadd.'");
				 </php>';
			}
		}



		if(!empty($options)) {
				if(substr($value,0,1)=="$"){//如果是变量
					$tempvalue=explode('.',$value);
					if(count($tempvalue)>1){//如果是数组
						$value=$tempvalue[0].'["'.$tempvalue[1].'"]';
					}
				}
			$parseStr   .= '<php>  foreach($options as $key=>$val) { </php>';
			if(!empty($value)) {
				$parseStr   .='<php> if('.$value.'== $key) { </php>';
				$parseStr   .= '<label><input id="'.$id.'" name="'.$name.'" type="radio"  value="<php> echo $key </php>" checked="checked" /><php> echo $val </php></label> ';
				$parseStr   .= '<php> }else { </php><label><input id="'.$id.'" name="'.$name.'" type="radio"  value="<php> echo $key </php>" /><php> echo $val </php></label> ';
				$parseStr   .= '<php> } </php>';
			}else {
				$parseStr   .= '<label><input id="'.$id.'" name="'.$name.'" type="radio"  value="<php> echo $key </php>" /><php> echo $val </php></label>';
			}
			$parseStr   .= '<php> } </php>';
		}

		return $parseStr;
	}


	public function _checkboxGroup($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'checkboxGroup');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		$outtable   = $tag['outtable'];               					//表单[outtable]//没值则
		$outkey     = $tag['outkey'];               					//表单[outkey]//没值则
		$outfield   = $tag['outfield'];               					//表单[outfield]//没值则
		$outcondition   = $tag['outcondition'];               			//表单[outcondition]//没值则
		$outorder   = $tag['outorder'];               					//表单[outorder]//没值则
		$outadd     = $tag['outadd'];               					//表单[outadd]//没值则
		if(!empty($outtable)){
			if($outtable=='Array'){
				$optionval=explode(',',$outfield);
				if(!empty($outkey)){
					$optionkey=explode(',',$outkey);
					$options=array_combine($optionkey,$optionval);
				}else{
					$options=$optionval;
				}
				$parseStr   = '<php>  $options=array(';

				foreach($options as $key=>$val){
					$parseStr   .= '"'.$key.'" => "'.$val.'", ';
				}
				$parseStr .= '); </php>';
			}else{
				$options=makeoption($outtable,$outkey,$outcondition,$outfield,$outorder,$outadd);
				$parseStr   .= '<php>  $options=makeoption("'.$outtable.'","'.$outkey.'","'.$outcondition.'","'.$outfield.'","'.$outorder.'","'.$outadd.'"); </php>';
			}
		}

		$parseStr   .= '<php>
		 $value="'.$value.'";//写入相应的值
		 $tmpvale=explode(",",$value);//看看是不是多选的值,以","号为分隔
		if(count($tmpvale)>1){ $value=$tmpvale; }else{ $value=$value; } </php>';
		if(!empty($options)) {
			$parseStr   .= '<php>  foreach($options as $key=>$val) { </php>';
			if(!empty($value)) {
				$parseStr   .='<php> if($value== $key  || in_array($key,$value) ) { </php>';
				$parseStr   .= '<label><input id="'.$id.'_tmpval" name="'.$name.'_tmpval" type="checkbox"  value="<php> echo $key </php>" checked="checked" onchange="'.$name.'updatevalue();" /><php> echo $val </php></label> ';
				$parseStr   .= '<php> }else { </php><label><input id="'.$id.'_tmpval" name="'.$name.'_tmpval" type="checkbox"  value="<php> echo $key </php>"  onchange="'.$name.'updatevalue();" /><php> echo $val </php></label> ';
				$parseStr   .= '<php> } </php>';
			}else {
				$parseStr   .= '<label><input id="'.$id.'_tmpval" name="'.$name.'_tmpval" type="checkbox"  value="<php> echo $key </php>"  onchange="'.$name.'updatevalue();" /><php> echo $val </php></label>';
			}
			$parseStr   .= '<php> } </php>';
		}
		$parseStr   .= '
	 <input type="hidden" value="'.$value.'"  id="'.$id.'" name="'.$name.'">
	 <script  type="text/javascript">
function '.$name.'updatevalue(){
	var s = document.getElementsByName("'.$name.'_tmpval");
	var checkboxvar = document.getElementById("'.$name.'");
	var s2 = "";
	for( var i = 0; i < s.length; i++ )
	{
		if ( s[i].checked ){
		s2 += s[i].value+",";
		}
	}
	s2 = s2.substr(0,s2.length-1);
	checkboxvar.value=s2;
	//alert(treuval.value);
}
</script>';
		return $parseStr;
	}


	public function _calendar($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'calendar');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$value      = !empty($tag['value'])?$tag['value']:date("Y-m-d");//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则

		$parseStr   = '
<script type="text/javascript" src="'.WEB_PUBLIC_URL.'/mkrtagsjs/calendar.js"></script>
 		';
		$parseStr  .= '<INPUT type="text" id="'.$id.'_input" name="'.$name.'" value="'.$value.'"  class="'.$class.'"  style="'.$style.'" '.$othervar.' '. $disabled.' '. $readonly. ' onclick="SelectDate(this)" >';

		return $parseStr;
	}


	public function _textarea($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'textarea');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则

		$parseStr   = '<textarea id="'.$id.'" name="'.$name.'" '.$othervar.' class="'.$class.'"  style="'.$style.'" '.$othervar.' '. $disabled.' '. $readonly. '>'.$value.'</textarea>';

		return $parseStr;
	}


	public function _word($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'word');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:'_editor'; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		$width		=	'100%';
		$height     =	'160px';
		$ajax     =	false;


		$parseStr   =	'<!-- 编辑器调用开始 --><script type="text/javascript" src="__ROOT__/Public/Js/FCKeditor/fckeditor.js"></script><textarea  id="'.$id.'" name="'.$name.'"  '.$othervar.' class="'.$class.'"  style="'.$style.'" '.$othervar.' '. $disabled.' '. $readonly. '>'.$value.'</textarea><script type="text/javascript"> var oFCKeditor = new FCKeditor( "'.$id.'","'.$width.'","'.$height.'" ) ; oFCKeditor.BasePath = "__ROOT__/Public/Js/FCKeditor/" ; oFCKeditor.ReplaceTextarea() ;</script><!-- 编辑器调用结束 -->';
		if($ajax){
			$parseStr   .=	'<input type="button" value="保存编辑器" onclick="document.getElementById(\''.$id.'\').value = getContents(\''.$id.'\');"> <input type="button" value="重设编辑器" onclick="setContents(\''.$id.'\',document.getElementById(\''.$id.'\').value);">';
		}

		return $parseStr;
	}


	public function _hidden($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'hidden');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则

		$parseStr   = '<INPUT type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'" '.$othervar.' >';

		return $parseStr;
	}

	public function _password($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'password');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则

		$parseStr   = '<script language="JavaScript" src="'.WEB_PUBLIC_URL.'/mkrtagsjs/checkpassword.js"></script>';
		$parseStr   .= '
<table >
	<tr><td><INPUT  type="password" id="'.$id.'" name="'.$name.'" value="'.$value.'"  class="'.$class.'"  style="'.$style.'" '.$othervar.' '. $disabled.' '. $readonly. ' onkeyup="ps.update(this.value);"></td>';
		$parseStr   .= '
<td>
	<script language="javascript">
	var ps = new PasswordStrength();
	ps.setSize("300","20");
	ps.setMinLength(2);
	</script>
</td>
	</tr>
	<tr><td><INPUT  type="password" id="'.$id.'_2" name="'.$name.'_2" class="'.$class.'"  style="'.$style.'" '.$othervar.' ></td><td>确认密码</td></tr>

</table>
';

		return $parseStr;
	}



	/**
     +----------------------------------------------------------
     * list标签解析
     * 格式： <html:list datasource="" show="" />
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
	public function _list($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'list');
		$id         = $tag['id'];                       //表格ID
		$datasource = $tag['datasource'];               //列表显示的数据源VoList名称
		$pk         = empty($tag['pk'])?'id':$tag['pk'];//主键名，默认为id
		$style      = $tag['style'];                    //样式名
		$name       = !empty($tag['name'])?$tag['name']:'vo';                 //Vo对象名
		$action     = $tag['action'];                   //是否显示功能操作
		$checkbox   = $tag['checkbox'];                 //是否显示Checkbox
		if(isset($tag['actionlist'])) {
			$actionlist = explode(',',trim($tag['actionlist']));    //指定功能列表
		}

		if(substr($tag['show'],0,1)=='$') {
			$show   = $this->tpl->get(substr($tag['show'],1));
		}else {
			$show   = $tag['show'];
		}
		$show       = explode(',',$show);                //列表显示字段列表

		//计算表格的列数
		$colNum     = count($show);
		if(!empty($checkbox))   $colNum++;
		if(!empty($action))     $colNum++;

		//显示开始
		$parseStr	= "\n<!-- Myhtml 系统列表组件开始 -->\n";
		$parseStr  .= '
<script type="text/javascript">
<!--
var firstrowoffset = 1; // first data row start at
var tablename = "'.$id.'"; // table name
//-->
</script>
		<TABLE id="'.$id.'" class="'.$style.'" >
		';
		$parseStr  .= '<TR class="row" >
		';
		//列表需要显示的字段
		$fields = array();
		foreach($show as $key=>$val) {
			$fields[] = explode(':',$val);
		}
		if(!empty($checkbox) && 'true'==strtolower($checkbox)) {//如果指定需要显示checkbox列
			$parseStr .='
			<th width="8">
				<input type="checkbox" id="check" onclick="CheckAll(\''.$id.'\')">
			</th>';
		}
		foreach($fields as $field) {//显示指定的字段
			$property = explode('|',$field[0]);
			$showname = explode('|',$field[1]);
			if(isset($showname[1])) {
				$parseStr .= '
			<Th width="'.$showname[1].'">';
			}else {
				$parseStr .= '
			<Th>
				';
			}
			$showname[2] = isset($showname[2])?$showname[2]:$showname[0];

			$trimtalbename=trim($property[0]);
			// dump($property);

			$parseStr .= '<A class="titleth" HREF="javascript:sortBy(\''.$trimtalbename.'\',\'{:getsorttype(\''.$trimtalbename.'\')}\',\''.ACTION_NAME.'\')" title="按照'.$showname[2].'排序">'.$showname[0].'|{:getsorttype(\''.$trimtalbename.'\',1)}</A>

			</Th>
			';

		}
		if(!empty($action)) {//如果指定显示操作功能列
			$parseStr .= '<th >操作区域</th>';
		}

		$parseStr .= '</TR>
		';

		$parseStr .= '<volist name="'.$datasource.'" id="'.$name.'" >
        <php> if(($key%2)==0){ $tablesclass="ewTableAltRow";} else { $tablesclass="ewTableRow";}</php>
					<TR class="{$tablesclass}" onmouseover="ew_mouseover(this);" onmouseout="ew_mouseout(this);" onclick="ew_click(this);"  >';	//支持鼠标移动单元行颜色变化 具体方法在js中定义

		if(!empty($checkbox)) {//如果需要显示checkbox 则在每行开头显示checkbox
			$parseStr .= '<td><input type="checkbox" name="key"	value="{$'.$name.'.'.$pk.'}"> </td>';
		}
		foreach($fields as $field) {
			//显示定义的列表字段
			$parseStr   .=  '<TD>
			';

			$property = explode('|',$field[0]);
			//生成表单

			$parseStr .='{$'.trim($name).'.'.trim($property[0]).'}';


			$parseStr .= '
			</TD>';
		}
		if(!empty($action)) {//显示功能操作
			if(!empty($actionlist[0])) {//显示指定的功能项
				$parseStr .= '<TD>';
				foreach($actionlist as $val) {
					// edit:编辑 表示 脚本方法名:显示名称
					$a = explode(':',$val);
					$b = explode('|',$a[1]);
					if(count($b)>1) {
						$c = explode('|',$a[0]);
						if(count($c)>1) {
							$parseStr .= '<A HREF="javascript:'.$c[1].'(\'{$'.$name.'.'.$pk.'}\')"><php> if(0== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ </php>'.$b[1].'<php> } </php></A><A HREF="javascript:'.$c[0].'({$'.$name.'.'.$pk.'})"><php> if(1== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ </php>'.$b[0].'<php> } </php></A> ';
						}else {
							$parseStr .= '<A HREF="javascript:'.$a[0].'(\'{$'.$name.'.'.$pk.'}\')"><php> if(0== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ </php>'.$b[1].'<php> } </php><php> if(1== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ </php>'.$b[0].'<php> } </php></A> ';
						}

					}else {
						$parseStr .= '<A HREF="javascript:'.$a[0].'(\'{$'.$name.'.'.$pk.'}\')">'.$a[1].'</A> ';
					}

				}
				$parseStr .= '
				</TD>';
			}else { //显示默认的功能项，包括编辑、删除
				$parseStr .= '
				<TD>
						<A HREF="javascript:edit({$'.$name.'.'.$pk.'})">编辑</A> <A onfocus="javascript:getTableRowIndex(this)" HREF="javascript:del({$'.$name.'.'.$pk.'})">删除</A>
				</TD>';
			}

		}
		$parseStr	.= '
		</TR>
		</volist>
		';

		$parseStr.='

		</TABLE>';
		$parseStr	.= "\n<!--Myhtml系统列表组件结束 -->\n";
		return $parseStr;
	}



	public function _text($attr){
		$tag        = $this->parseXmlAttr($attr,'text');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		//$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		//$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		
		$value      =(strpos($tag['value'],'$')===false )?'"'.$tag['value'].'"':$tag['value'];
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		
		$outtable   = $tag['outtable'];               					//外联数据表
		$outkey     = $tag['outkey'];               					//外联值字段
		$outfield   = $tag['outfield'];               					//外联显示字段
		$outcondition   = $tag['outcondition'];               			//外联条件
		$outorder   = $tag['outorder'];               					//外联排序
		$outadd     = $tag['outadd'];               					//表单[outadd]//没值则
		if(!empty($outtable)){
			if($outtable=='Array'){
				$optionval=explode(',',$outfield);
				if(!empty($outkey)){
					$optionkey=explode(',',$outkey);
					$options=array_combine($optionkey,$optionval);
				}else{
					$options=$optionval;
				}
				$parseStr   = '<php>  $options=array();$options=array(';

				foreach($options as $key=>$val){
					$selectoption[]  = '"'.$key.'" => "'.$val.'"';
				}
				$parseStr .=join(',',$selectoption);
				$parseStr .= '); </php>';
			}else{
				$options=makeoption($outtable,$outkey,$outcondition,$outfield,$outorder,$outadd);
				$parseStr   .= '
				<php>  $options=makeoption("'.$outtable.'","'.$outkey.'","'.$outcondition.'","'.$outfield.'","'.$outorder.'","'.$outadd.'"); </php>
				';
			}
		}

		if(!empty($options)) {
			$parseStr   .= '<php>  foreach($options as $key=>$val) { </php>';
			//$parseStr   .= '<php> if('. $value.'==$key){ </php>';
			$parseStr   .='<php> if('.$value.'== $key) { </php>';
			$parseStr   .= '<span class="'.$class.'" style="'.$style.'" ><php> echo $val;</php><input type="hidden" id="'.$id.'" name="'.$name.'" value="<php> echo $key;</php>" ></span>';
			$parseStr   .= '<php> } </php>';
			$parseStr   .= '<php> } </php>';
			$parseStr   .= '<php> unset($options,$key,$val); </php>';
		}else{
			if(strpos($tag['value'],'$')===false){
				$parseStr   = '<span class="'.$class.'" style="'.$style.'" >'.$value.'<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'" ></span>';
			}else{
				$value=substr($tag['value'],1);
				$parseStr   = '<span class="'.$class.'" style="'.$style.'" >{$'.$value.'|auto_charset|un_clean_html'.$othervar.' }<input type="hidden" id="'.$id.'" name="'.$name.'" value="{$'.$value.'|auto_charset|clean_html'.$othervar.' }" ></span>';
			}
		}
		return $parseStr;

	}

	public function _htmltext($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'htmltext');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		if(strpos($tag['value'],'$')===false){
			$parseStr   = '<span    class="'.$class.'"  style="'.$style.'" >'.$value.'<input type="hidden" id="'.$id.'" name="'.$name.'" value="'.$value.'" ></span>';
		}else{
			$value=substr($tag['value'],1);
			$parseStr   = '<span    class="'.$class.'"  style="'.$style.'" >{$'.$value.'|auto_charset|un_clean_html'.$othervar.' }<input type="hidden" id="'.$id.'" name="'.$name.'" value="{$'.$value.'|auto_charset|clean_html'.$othervar.' }" ></span>';
		}
		return $parseStr;
	}


	public function _time($attr){
		$tag        = $this->parseXmlAttr($attr,'time');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		if(strpos($tag['value'],'$')===false){
			$parseStr   = '<span    class="'.$class.'"  style="'.$style.'" >'.$value.'</span>';
		}else{
			$value=substr($tag['value'],1);
			$parseStr   = '<span    class="'.$class.'"  style="'.$style.'" >{$'.$value.'|toDate|auto_charset'.$othervar.' }</span>';
		}
		return $parseStr;
	}



	public function _image($attr){
		$tag        = $this->parseXmlAttr($attr,'time');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则

		$parseStr   = '<span    class="'.$class.'"  style="'.$style.'" ><a href="__ROOT__/Public/Uploads/{$'.$value.'}" target="_blank"  ><img src="__ROOT__/Public/Uploads/{$'.$value.'}" border="0" title="查看原图大小"></a></span>';
		return $parseStr;

	}


	public function _filedown($attr){
		$tag        = $this->parseXmlAttr($attr,'time');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则

		$parseStr   = '<span    class="'.$class.'"  style="'.$style.'" ><a href="__ROOT__/Public/Uploads/'.$value.'" target="_blank"  >查看或下载文件</a></span>';
		return $parseStr;

	}

	public function _chart($attr){
		$tag        = $this->parseXmlAttr($attr,'chart');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$type       =$tag['type'];  		//$type图表的类型
		$width       =empty($tag['width'])?'540':$tag['width'];  		//$width//flash的宽和高
		$hight       =empty($tag['hight'])?'400':$tag['hight'];  		//$hight//flash的宽和高
		$padding       =empty($tag['padding'])?'8':$tag['padding'];  		//$hight//flash的宽和高
		$bgcolor     =empty($tag['bgcolor'])?'#FFFFFF':$tag['bgcolor'];  		//$bgcolor//flash的背景色
		$settings_file       =$tag['settings_file'];  		//设定的文件
		$data_file       =$tag['data_file'];  		//$data_file//数据的文件
		$preloader_color       =empty($tag['preloader_color'])?'#cccccc':$tag['preloader_color'];  		//$preloader_color//加载时的色
		$parseStr   = '<script type="text/javascript" src="'.WEB_PUBLIC_URL.'/chart/js/swfobject.js"></script>
    <div id="'.$name.'">
        <strong>你需要更新你的FLASH播放器,或联系管理员!</strong>
    </div>
    <script type="text/javascript">
        // <![CDATA[
        var so_'.$name.' = new SWFObject("'.WEB_PUBLIC_URL.'/chart/chart/'.$type.'.swf", "'.$type.'", "'.$width.'", "'.$hight.'", "'.$padding.'", "'.$bgcolor.'");
        so_'.$name.'.addVariable("settings_file", encodeURIComponent("'.$settings_file.'"));
        so_'.$name.'.addVariable("data_file", encodeURIComponent("'.$data_file.'"));
        so_'.$name.'.addVariable("preloader_color", "'.$preloader_color.'");
        so_'.$name.'.write("'.$name.'");
        // ]]>
    </script>';
		return $parseStr;

	}

	public function _temp($attr){
		$tag        = $this->parseXmlAttr($attr,'input');
		$name       = $tag['name']; 									//表单名称[name]
		$id       	= !empty($tag['id'])?$tag['id']:$tag['name']; 		//表单ID[id]//没值则用NAME为值
		$tagvalue      =!empty($tag['value'])?$tag['value']:''; 		//$tagvalue//没值则
		$value      =(strpos($tagvalue,'$')===false )?$tagvalue:'{'.$tagvalue.'}';		//表单[value]//没值则
		$class      = empty($tag['class'])?$tag['class']:''; 			//表单class//没值则用NAME为值
		$style      = empty($tag['style'])?$tag['style']:''; 			//表单[style]//没值则
		$disabled   = $tag['disabled']==1?'disabled':'';                //表单[disabled]//没值则
		$readonly   = $tag['readonly']==1?'readonly':'';               	//表单[readonly]//没值则
		$othervar   = $tag['othervar'];               					//表单[othervar]//没值则
		$parseStr   = '<INPUT  id="'.$id.'" name="'.$name.'" value="'.$value.'"  class="'.$class.'"  style="'.$style.'" '.$othervar.' '. $disabled.' '. $readonly. '>';
		return $parseStr;

	}

}