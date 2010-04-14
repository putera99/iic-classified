<?php
import('TagLib');
Class TagLibMyhtml extends TagLib
{
	/**
     +----------------------------------------------------------
     * simlist标签解析
     * 格式： <myhtml:simlist datasource="" show="" />
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
	public function _simlist($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'simlist');
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

			$parseStr .= '<A HREF="javascript:sortBy(\''.$trimtalbename.'\',\'{:getsorttype(\''.$trimtalbename.'\')}\',\''.ACTION_NAME.'\')" title="按照'.$showname[2].'排序">'.$showname[0].'{:getsortimg(\''.$trimtalbename.'\')}</A>

			</Th>
			';

		}
		if(!empty($action)) {//如果指定显示操作功能列
			$parseStr .= '<th >操作区域</th>';
		}

		$parseStr .= '</TR>
		';

		$parseStr .= '<volist name="'.$datasource.'" id="'.$name.'" >
					<TR  >';	//支持鼠标移动单元行颜色变化 具体方法在js中定义

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
							$parseStr .= '<A HREF="javascript:'.$c[1].'(\'{$'.$name.'.'.$pk.'}\')"><?php if(0== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[1].'<?php } ?></A><A HREF="javascript:'.$c[0].'({$'.$name.'.'.$pk.'})"><?php if(1== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[0].'<?php } ?></A> ';
						}else {
							$parseStr .= '<A HREF="javascript:'.$a[0].'(\'{$'.$name.'.'.$pk.'}\')"><?php if(0== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[1].'<?php } ?><?php if(1== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[0].'<?php } ?></A> ';
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

	public function _editlist($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'editlist');
		$id         = $tag['id'];                       //表格ID
		$datasource = $tag['datasource'];               //列表显示的数据源VoList名称
		$pk         = empty($tag['pk'])?'id':$tag['pk'];//主键名，默认为id
		$style      = $tag['style'];                    //样式名
		$name       = !empty($tag['name'])?$tag['name']:'vo';                 //Vo对象名
		$action     = $tag['action'];                   //是否显示功能操作
		$quickadd     = $tag['quickadd'];                   //是否显示快速增加
		$checkbox   = $tag['checkbox'];                 //是否显示Checkbox
		$createform = $tag['createform'];                 //是否显示createform
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
		if(!empty($createform)) {///是否显示form
			$parseStr_header	= '<!-- Myhtml 系统列表组件开始 --><form name="form_update" id="form_update" method="POST" autocomplete="off">';
		}
		$parseStr  .= '

		<TABLE id="'.$id.'" class="'.$style.'"  cellpadding="0" cellspacing="1">
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

			$property = explode('|',$field[0]);
			//生成表单

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
			if(trim($property[1])=="hidden"){
				$parseStr .='';
			}else{
				$titlename=explode('[',$showname[0]);//目的,把[]前面的留下来做标题,以不影响美观
				$parseStr .= '<A HREF="javascript:sortBy(\''.$trimtalbename.'\',\'{:getsorttype(\''.$trimtalbename.'\')}\',\''.ACTION_NAME.'\')" title="按照'.$showname[2].'排序">'.$titlename[0].'{:getsortimg(\''.$trimtalbename.'\')}</A>';
			}
			$parseStr .= '</Th>';

		}
		if(!empty($action)||!empty($actionlist[0])) {//如果指定显示操作功能列
			$parseStr .= '<th >操作</th>';
		}

		$parseStr .= '</TR>
		';

		$headvar=$parseStr;
		$parseStr=$parseStr_header.$parseStr;
		$parseStr .= '<volist name="'.$datasource.'" id="'.$name.'" >
					<TR  >';	//支持鼠标移动单元行颜色变化 具体方法在js中定义

		if(!empty($checkbox)) {//如果需要显示checkbox 则在每行开头显示checkbox
			$parseStr .= '<td><input type="checkbox" name="key"	value="{$'.$name.'.'.$pk.'}"> </td>';
		}
		foreach($fields as $field) {
			//显示定义的列表字段
			$parseStr   .=  '<TD>
			';

			$property = explode('|',$field[0]);
			//生成表单
			if(trim($property[1])=="select"){
				//$parseStr .='<select  name="'.trim($property[0]).'[]" />';
				$thispro=str_replace('#',',',trim($property[2]));
				$parseStr .='{:'.$thispro.'}';
			}elseif(trim($property[1])=="text"){
				$parseStr .='{$'.trim($name).'.'.trim($property[0]).'}';
			}elseif(trim($property[1])=="checkbox"){
				$parseStr .='
			<php>
			if($'.trim($name).'[\''.trim($property[0]).'\']==1){
				$checked="checked=checked";
			}else{
				$checked="";
			}
			</php>
			<input  type="checkbox" onclick="checkboxvalue(\'cb_'.trim($property[0]).'{$'.$name.'.'.$pk.'}\',\'ch_'.trim($property[0]).'{$'.$name.'.'.$pk.'}\');" id="cb_'.trim($property[0]).'{$'.$name.'.'.$pk.'}" {$checked} />
			<input id="ch_'.trim($property[0]).'{$'.$name.'.'.$pk.'}"  name="'.trim($property[0]).'[]" type="hidden" value="{$'.trim($name).'.'.trim($property[0]).'}">';
			}elseif(trim($property[1])=="hidden"){
				$parseStr .='<input name="'.trim($property[0]).'[]" type="hidden" value="{$'.trim($name).'.'.trim($property[0]).'}">';
			}else{
				$parseStr .='<input name="'.trim($property[0]).'[]" value="{$'.trim($name).'.'.trim($property[0]).'}" size="'.$property[1].'">';
			}

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
							$parseStr .= '<A HREF="javascript:'.$c[1].'(\'{$'.$name.'.'.$pk.'}\')"><?php if(0== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[1].'<?php } ?></A><A HREF="javascript:'.$c[0].'({$'.$name.'.'.$pk.'})"><?php if(1== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[0].'<?php } ?></A> ';
						}else {
							$parseStr .= '<A HREF="javascript:'.$a[0].'(\'{$'.$name.'.'.$pk.'}\')"><?php if(0== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[1].'<?php } ?><?php if(1== (is_array($'.$name.')?$'.$name.'["status"]:$'.$name.'->status)){ ?>'.$b[0].'<?php } ?></A> ';
						}

					}else {
						$parseStr .= '<A HREF="javascript:'.$a[0].'(\'{$'.$name.'.'.$pk.'}\')">'.$a[1].'</A> ';
					}

				}
				$parseStr .= '
				</TD>';
			}


		}
		$parseStr	.= '
		</TR>
		</volist>
		</table>';
		if(!empty($createform)) {///是否显示form
			$parseStr	.='</form>';
		}
		$parseStr	.='
		<!--form_update完成 -->
		';
		if($quickadd) {///是否显示快速增加
			if(!empty($createform)) {///是否显示form
				$parseStr	.= '
		        <!--form_add开始 -->
		        <form name="form_add" id="form_add" method="POST">';
			}
			$parseStr=$parseStr.$headvar;//写入头
			$parseStr	.= '

        <TR><td><img src="../Public/images/right_up.gif" border=0></td>';

			foreach($fields as $field) {
				//显示定义的列表字段
				$parseStr   .=  '<TD>
			';

				$property = explode('|',$field[0]);
				//生成表单
				if(trim($property[1])=="select"){
					//$parseStr .='<select  name="'.trim($property[0]).'[]" />';
					$thispro=str_replace('#',',',trim($property[2]));
					$thispro=str_replace('[]','',$thispro);
					$parseStr .='{:'.$thispro.'}';
				}elseif(trim($property[1])=="hidden"){
					$parseStr .='';
				}elseif(trim($property[1])=="checkbox"){
					$parseStr .='<input name="'.trim($property[0]).'" value="1" type="checkbox" checked="checked">';
				}else{
					$parseStr .='<input name="'.trim($property[0]).'" value="" size="'.$property[1].'">';
				}

				$parseStr .= '
			</TD>';
			}
			if($quickadd) {//是否显示按钮
				$parseStr	.= '<td><input type="button"  class="button1" onClick="addbtn();" value="增加"></td>';
			}
			$parseStr.='</TR><!--form_add完成 -->';
		}//是否显示快速增加END
		$parseStr.='</TABLE>';
		if(!empty($createform)) {///是否显示form
			$parseStr.='</form>';
		}
		$parseStr	.= "<!--Myhtml系统列表组件结束 -->";
		return $parseStr;
	}


	public function _labelinput($attr)
	{
		$tag        = $this->parseXmlAttr($attr,'labelinput');
		$name       = $tag['name'];                //名称
		$value      = $tag['value'];                //文字
		$id         = $tag['id'];                //ID
		$size      = $tag['size'];                //样式名
		$action     = $tag['action'];                //点击
		$label      = $tag['label'];                //点击
		$type       = $tag['type'];                //按钮类型

		if($type=='text') {
			$parseStr   = '<th>'.$label.'</th><td><INPUT TYPE="'.$type.'" size="'.$size.'" id="'.$id.'" name="'.$name.'" value="'.$value.'" ></td>';
		}elseif ($type=='checkbox'){
			$parseStr   = '<th>'.$label.'</th>';
			$parseStr .='<td>
			<php>
			if('.$value.'==1){
				$checked="checked=checked";
			}else{
				$checked="";
			}
			</php>
			';
			$parseStr .='
			<input  type="checkbox" onclick="checkboxvalue(\'cb_'.$name.'\',\'ch_'.$name.'\');" id="cb_'.$name.'" {$checked} />
			<input id="ch_'.$name.'"  name="'.$name.'" type="hidden" value="'.$value.'">';
			$parseStr .='</td>';
		}
		else {
			$thispro=str_replace('#',',',$action);
			$parseStr   = '<th>'.$label.'</th><td>{:'.$thispro.'}</td>';
		}
		return $parseStr;
	}
}