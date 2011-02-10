<?php

# 2008-11-21
# 聆星
# 

require("../global.php");
set_time_limit(0);
ob_start();
echo "<?xml version=\"1.0\" encoding=\"GB2312\"?>";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
<?php
$ft = $db->query("SELECT * FROM `{$pre}article` where yz = 1 ORDER BY `posttime` DESC  LIMIT 0 , 30");  
 while ($rs = $db->fetch_array($ft)){
    $dirid=floor($rs[aid]/1000);
  #2006-08-17T03:19:00Z
  
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
<url>
	<loc><?php echo $webdb['www_url'];?>/<?php echo $filename;?></loc>
	<news:news>
	<news:publication_date><?php echo date("Y-m-d",$rs[posttime]);?>T<?php echo date("H:i:s",$rs[posttime]);?>Z</news:publication_date>
	<news:keywords><?php echo $rs[title];?></news:keywords>
	</news:news>
</url>
<?php
}
?>
</urlset>
<?php
$this_my_f= ob_get_contents(); 
ob_end_clean();
if(write_file("../googlesitemap.xml",$this_my_f)){
 echo " [生成成功<a href=\"../googlesitemap.xml\">googlesitemap.xml</a>] ";
 }else{
 echo "生成错误";
 }
?>




