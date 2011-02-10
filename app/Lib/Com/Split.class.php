<?php
/**
 +------------------------------------------------------------------------------
 * 英文分词过滤类
 +------------------------------------------------------------------------------
 * @category   SubExt
 * @package  ext
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2011-01-19
 * @time  上午09:41:43
 +------------------------------------------------------------------------------
 */
class Split {
	
	//过滤掉的词
	protected $filter=array(
		'in','at','on','before','after','till','since','for','fromto','until','by','of','at','past','five','night',
		'morning','sunrise','spring','summer','autumn','winter','afternoon','winter','evening','long','age','
		into','to','beside','before','behind','above','under','outside','inside','up','from','far','from','near',
		'across',' off','down','among','past','between','out','of','around','front','back','foot','home','this',
		'that','these','those','and','but','before','since','when','however','therefore','is','the','it','or','its',
		'th','a','b','c','d','e','f','g','h','i','j','k','l','n','m','o','p','q','r','s','t','u','v','w','x','y','z',
		'two','three','four','five','six','ride','right','nine','ten','one',
		'with',
	);
	protected $tags='';
	public function __construct($kw){
		$kw=str_word_count($kw,1);
		foreach ($kw as $v){
			if(!in_array(strtolower($v), $this->filter)){
				$this->tags.=$v.',';
			}
		}
	}
	
	public function get_tags($num=10) {
		$tags='';
		$arr=explode(',', trim($this->tags,','));
		$arr=array_count_values($arr);
		$k='';
		for ($i=0;$i<count($arr);$i++){
			if($i+1<$num){
				$k=array_search(max($arr), $arr);
				unset($arr[$k]);
				$tags.=$k.',';
			}
		}
		
		return trim($tags,',');
	}//end get_tags
}