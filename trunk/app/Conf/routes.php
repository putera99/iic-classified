<?php
if (!defined('THINK_PATH')){ 
	exit();
}
return array(
    'link'=>array('Link','jump','url'),
    'index'=>array('Index','index','cid'),
	'so'=>array('Common','so','key'),    
	'advso'=>array('Common','advso'),    

    //index
    'cityguide'=>array('CityGuide','index','cid'),
    'classifieds'=>array('Classifieds','index','cid'),
    'biz'=>array('Biz','index'),
    'event'=>array('Event','index','cid'),
    'group'=>array('Group','index'),

    //show
    'clss'=>array('Classifieds','show','aid'),
    'ctgs'=>array('CityGuide','show','aid'),
    'grps'=>array('Group','show','aid'),
    'grpts'=>array('Group','thread','id'),
    'evts'=>array('Event','show','aid'),

    //ls
    'clist'=>array('Classifieds','ls','id,cid'),
    'cglist'=>array('CityGuide','ls','id,cid'),
    'glist'=>array('Group','ls','id'),
);
?>
