<?php
#########################################################################
#                Under GNU General Public License (GPL)
#########################################################################
#    One line to give the program's name and a brief idea of what it does.
#    Copyright (C) 2009 Stanislav Chervenkov
#
#    This program is free software; you can redistribute it and/or modify
#it under the terms of the GNU General Public License as published by the
#Free Software Foundation; either version 2 of the License, or (at your
#option) any later version.
#
#    This program is distributed in the hope that it will be useful, but
#WITHOUT ANY WARRANTY; without even the implied warranty of
#MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General
#Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#along with this program; if not, write to the Free Software Foundation,
#Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
#########################################################################

class cache {
	function cache() {
		$this->cache_dir = './cache';
		$this->index = $this->cache_dir.'/cache_info.index';
	}
	
	function set($var, $key, $time) {
		$h=fopen($this->index,'r+');
		$c=fread($h, filesize($this->index));
		fclose($h);
		if (strlen($c)>0)
			$c = unserialize($c);
		else
			$c = array();
		$scalar=is_scalar($var);
		$c[md5($key)] = array(
			'scalar'=>$scalar,
			'time'=>$time,
			'update_time'=>time()
		);

		$cache_content = $scalar?$var:serialize($var);
		$h=fopen($this->cache_dir.'/'.md5($key).'.cache','w+');
		fwrite($h,$cache_content);
		fclose($h);

		$c=serialize($c);
		$h=fopen($this->index, 'w+');
		fwrite($h,$c);
		fclose($h);
		return $this->cache_dir.'/'.md5($key).'.cache';
	}
	
	function read(&$variable, $key) {
		$h=fopen($this->index,'r');
		$c=fread($h, filesize($this->index));	
		fclose($h);
		if (strlen($c)>0)
			$c=unserialize($c);
		else
			$c=array();
		if (!@isset($c[md5($key)]) || !file_exists($this->cache_dir.'/'.md5($key).'.cache') || filesize($this->cache_dir.'/'.md5($key).'.cache')==0) return false;
		$currentFileTime=$c[md5($key)]['update_time'];
		if ((intval($c[md5($key)]['time'])+$currentFileTime)<time()) {
			return false;		
		} else {
			$h = fopen($this->cache_dir.'/'.md5($key).'.cache','r');
			$content = fread($h, filesize($this->cache_dir.'/'.md5($key).'.cache'));
			fclose($h);
			if ($c[md5($key)]['scalar']) $var = $content;
			else $var = @unserialize($content);
			$variable = $var;
			return true;
		}
	}
}
?>