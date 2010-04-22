<?php
class ajaxpage extends Page{
    /**
     +----------------------------------------------------------
     * 分页显示
     * 用于在页面显示的分页栏的输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function ajaxshow($pagetarge){

        if(0 == $this->totalRows) return;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);

		$pageaction=ACTION_NAME;
		$pagemodel=MODULE_NAME;
		$pagefield=$_REQUEST['order'];
		$pagesort=$_REQUEST['sort'];
		$pagetarge=($pagetarge=='')?MODULE_NAME."_Grid":$pagetarge;



        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
			$upPage="[<a href='javascript:void(0);'  onclick=\"AjaxPageBy('$pagemodel','$pageaction','$pagefield','$pagesort','$pagetarge','$upRow');\">".$this->config['prev']."</a>]";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
			$downPage="[<a href='javascript:void(0);'  onclick=\"AjaxPageBy('$pagemodel','$pageaction','$pagefield','$pagesort','$pagetarge','$downRow');\">".$this->config['next']."</a>]";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;

            $prePage = "[<a href='javascript:void(0);'  onclick=\"AjaxPageBy('$pagemodel','$pageaction','$pagefield','$pagesort','$pagetarge','$preRow');\">上".$this->rollPage."页</a>]";
            $theFirst = "[<a href='javascript:void(0);'  onclick=\"AjaxPageBy('$pagemodel','$pageaction','$pagefield','$pagesort','$pagetarge','1');\">".$this->config['first']."</a>]";

        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;

            $nextPage = "[<a href='javascript:void(0);'  onclick=\"AjaxPageBy('$pagemodel','$pageaction','$pagefield','$pagesort','$pagetarge','$nextRow');\">下".$this->rollPage."页</a>]";
            $theEnd = "[<a href='javascript:void(0);'  onclick=\"AjaxPageBy('$pagemodel','$pageaction','$pagefield','$pagesort','$pagetarge','$theEndRow');\">".$this->config['last']."</a>]";

        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<a href='javascript:void(0);'  onclick=\"AjaxPageBy('$pagemodel','$pageaction','$pagefield','$pagesort','$pagetarge','$page');\">&nbsp;".$page."&nbsp;</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= " [".$page."]";
                }
            }
        }
        $pageStr = '共'.$this->totalRows.' '.$this->config['header'].'/'.$this->totalPages.'页 '.$upPage.' '.$downPage.' '.$theFirst.' '.$prePage.' '.$linkPage.' '.$nextPage.' '.$theEnd;

        return $pageStr;
    }

}
?>