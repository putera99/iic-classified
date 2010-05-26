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
		$xid=$_REQUEST['xid'];
		$types=$_REQUEST['types'];
		$pagetarge=($_REQUEST['comments']=='')?"#comments":$_REQUEST['comments'];



        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
			$upPage="<span><a href='javascript:void(0);' onclick=\"page('$pagemodel','$pageaction','$xid','$types','$pagetarge','$upRow');\"><</a></span>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
			$downPage="<span><a href='javascript:void(1);' onclick=\"page('$pagemodel','$pageaction','$xid','$types','$pagetarge','$downRow');\">></a></span>";
        }else{
            $downPage="";
        }
        // << < > >>
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;

            $prePage = "<span><a href='javascript:void(2);' onclick=\"page('$pagemodel','$pageaction','$xid','$types','$pagetarge','$preRow');\">".$this->rollPage."</a></span>";
            $theFirst = "<span><a href='javascript:void(3);' onclick=\"page('$pagemodel','$pageaction','$xid','$types','$pagetarge','1');\">".$this->config['first']."</a></span>";

        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;

            $nextPage = "<span><a href='javascript:void(4);' onclick=\"page('$pagemodel','$pageaction','$xid','$types','$pagetarge','$nextRow');\"><<</a></span>";
            $theEnd = "<span><a href='javascript:void(5);' onclick=\"page('$pagemodel','$pageaction','$xid','$types','$pagetarge','$theEndRow');\">>></a></span>";

        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "<span><a href='javascript:void(6);'  onclick=\"page('$pagemodel','$pageaction','$xid','$types','$pagetarge','$page');\">".$page."</a></span>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "<span class='on'>".$page."</span>";
                }
            }
        }
        //$pageStr = '共'.$this->totalRows.' '.$this->config['header'].'/'.$this->totalPages.'页 '.$upPage.' '.$downPage.' '.$theFirst.' '.$prePage.' '.$linkPage.' '.$nextPage.' '.$theEnd;
        $pageStr = $upPage.' '.$downPage.' '.$theFirst.' '.$prePage.' '.$linkPage.' '.$nextPage.' '.$theEnd;

        return $pageStr;
    }

}
?>