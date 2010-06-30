<?php
require_once 'exl/reader.php';
$file="20100625.xls";
$arr=array();
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('utf-8');

$data->read($file);

error_reporting(E_ALL ^ E_NOTICE);

//echo $data->sheets[0]['numRows'];
//echo $data->sheets[0]['numCols'];
echo $data->sheets[0]['cells'][3][8];


/*for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
	}
	echo "\n";
}*/