<?php

# 2008-11-21
# 聆星
# 


require("../global.php");
set_time_limit(0);
ob_start();
echo "<?xml version=\"1.0\" encoding=\"GB2312\"?>";
?>
<document>
<webSite><?php echo $webdb['www_url'];?></webSite>
<webMaster><?php echo $webdb['webmail'];?></webMaster>
<updatePeri>1440</updatePeri>
<?php
$ft = $db->query("SELECT A.*,R.* FROM {$pre}article A LEFT JOIN {$pre}reply R ON A.aid=R.aid WHERE yz = 1 ORDER BY A.posttime DESC LIMIT 0 , 30");  
while ($rs = $db->fetch_array($ft)){
$dirid=floor($rs[aid]/1000);
#2006-08-17T03:19:00Z
$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$rs[fid]'");
$titleDB[keywords]	= filtrate($rs[keywords]);
$titleDB[description] = filtrate($rs[description]);
$rs[posttime] = date("Y-m-d H:i:s",$rs[posttime]);
$rs[content]=get_word(strip_tags($rs[content]),3000);
$rs[content] = str_replace("&nbsp;", "", "$rs[content]");
$rs[content] = str_replace("&quot;", "", "$rs[content]");
$rs[description] || $rs[description]=get_word(strip_tags($rs[content]),200);
$rs[description] = str_replace("&nbsp;", "", "$rs[description]");
$rs[description] = str_replace("&quot;", "", "$rs[description]");

$rs[content]=preg_replace('/<([^>]*)>/is',"",$rs[content]);	
$rs[description]=preg_replace('/<([^>]*)>/is',"",$rs[description]);	
if($rs[picurl]){
$rs[picurl] = tempdir($rs[picurl]);
}else{
$rs[picurl] = "";
}

if($Html_Type['bencandy'][$fid]){
			$filename=$Html_Type['bencandy'][$fid];
		}else{
			$filename=$webdb[bencandy_filename];
		}
	
	$fid = $rs[fid];
	$id = $rs[aid];
	$page = "1";
		
eval("\$filename=\"$filename\";");

?>
<item>
<title><?php echo $rs[title];?></title>
<link><?php echo $webdb['www_url'];?>/<?php echo $filename;?></link>
<description><![CDATA[<?php echo $rs[description];?>]]></description>
<text><![CDATA[<?php echo $rs[content];?>]]></text>
<image><?php echo $rs[picurl];?></image>
<headlineImg />
<keywords><?php echo $rs[keywords];?></keywords>
<category><?php echo $rs[fname];?></category>
<author><?php echo $rs[author];?></author>
<source><?php echo $webdb['webname'];?></source>
<pubDate><?php echo $rs[posttime];?></pubDate>
</item>
<?php
}
?>
</document>
<?php
$this_my_f= ob_get_contents(); 
ob_end_clean();
if(write_file("../baidusitemap.xml",$this_my_f)){
 echo " [生成成功<a href=\"../baidusitemap.xml\">baidusitemap.xml</a>]";
 }else{
 echo "生成错误";
 }
?>