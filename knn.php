<?php
require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Classification\KNearestNeighbors;

//使用mysqli面向对象形式读取数据


///根据年龄 性别  分析出喜好的作品类型

$samples = Array (
	0 => Array (657043200, 1, 540, 901 ),
	1=> Array (654192000, 1, 332, 0 ),
	2=>Array (820425600, 1, 349, 934 ),
	3=>Array (689097600, 1, 636, 0 ),
	4 => Array (707155200,1, 692, 41703 ),
	5 => Array (922723200, 0, 286, 901 ),
	6 => Array (699292800, 0,262, 0 ),
	7 => Array (718473600, 1, 278, 0 ),
	8 => Array (699292800, 0, 4, 0 ),
	9 => Array (839433600, 1, 267, 0 ) 
	);
$labels = Array ('不充值', '充值', '充值', '不充值', '充值', '充值',  '不充值',  '充值',  '不充值',  '充值' ); 

$classifier = new KNearestNeighbors();
$classifier->train($samples, $labels);

echo $classifier->predict([372441600, 1, 258,7000]);