<?php
if (!defined('THINK_PATH')){ 
	exit();
}
return array(
    'link'=>array('Link','jump','url'),
    'index'=>array('Index','index','cid'),
	'so'=>array('Common','so'),    
	'advso'=>array('Common','advso'),    
	'sitemap'=>array("Maps","sitemap"),	
	'weather_forecast'=>array("Public","weather_forecast"),	
	'select_city'=>array("Public","index","cid,to,title"),	
	'login'=>array("Public","login","to,title"),	
	'register'=>array("Public","register","to,title"),	

    //index
    'cityguide'=>array('CityGuide','index','cid'),
    'classifieds'=>array('Classifieds','index','cid'),
	'featured_classifieds'=>array('Classifieds','ls','id,cid','flag=f'),
    'biz'=>array('Biz','index','cid'),
    'event'=>array('Event','index','cid'),
    'group'=>array('Group','index','cid'),

    //show
    'clss'=>array('Classifieds','show','aid,title'),
    'ctgs'=>array('CityGuide','show','aid,title'),
    'grps'=>array('Group','show','aid,title'),
    'grpts'=>array('Group','thread','id,title'),
    'evts'=>array('Event','show','aid,title'),
    'fair'=>array('Biz','show','aid,title'),
    'article'=>array('Arc','show','aid,title'),

    //ls
    'clist'=>array('Classifieds','ls','id,cid,title'),
    'cglist'=>array('CityGuide','ls','id,cid,title'),
    'glist'=>array('Group','ls','id'),
    'alist'=>array('Arc','ls','id,cid,title'),
    'event-time'=>array('Event','ls','tk,cid'),
    'event-category'=>array('Event','ls','id,title,cid'),
);
?>
