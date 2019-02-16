<?php
require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Classification\NaiveBayes;

class My_mysqli extends mysqli
{
	
}

$samples = []; //性别 年龄(70 80 90) 预测读书类型
$labels = [];

function getAgeType($birthdayTime)
{
	$age = ceil((time()-$birthdayTime) / (24*3600*365*5));
	return $age;
}

function getUserArray($mysqli, $userId)
{
	$result2 = $mysqli->query("SELECT * FROM User WHERE UserId={$userId}");
	$curUser = $result2->fetch_object();
			
	return [$curUser->Sex, getAgeType($curUser->Birthday)];
}

function getMaxClassId($mysqli, $userId)
{
	$maxClassId = 0;
	$tableName = 'userread_'.($userId % 10);
	
	$arr1 = [];
	
	if ($result = $mysqli->query("SELECT BookId FROM cxssread.{$tableName} WHERE UserId={$userId}")) {
		while ($row = $result->fetch_object()) {
			$result2 = $mysqli->query("SELECT NowBookClassId0 FROM Book WHERE BookId={$row->BookId}");
			if($result2)
			{
				$curBook = $result2->fetch_object();
				if(isset($arr1[$curBook->NowBookClassId0]))
				{
					$arr1[$curBook->NowBookClassId0]++;
				}
				else
				{
					$arr1[$curBook->NowBookClassId0] = 1;
				}
			}
		}

		$max = 0;
		foreach($arr1 as $k=>$v)
		{
			if($v > $max)
			{
				$maxClassId = $k;
			}
		}

		$result->close();
	}
	
	return $maxClassId;
}

$mysqli = new My_mysqli('www.8kana.cc', 'root', '123456', 'cxss', 3306);
if ($result = $mysqli->query("SELECT UserId,Sex,Birthday FROM user LIMIT 5000")) { //logintimes是连续值
	while ($row = $result->fetch_object()) {
		$newSample = [$row->Sex, getAgeType($row->Birthday)];
		$newLabel = getMaxClassId($mysqli, $row->UserId);
		
		if($newLabel > 0)
		{
			$samples[] = $newSample;
			$labels[] = $newLabel;
		}
    }

    $result->close();
}

$classifier = new NaiveBayes();
$classifier->train($samples, $labels);

$userId = 180463;
$result = $classifier->predict(getUserArray($mysqli, $userId));
print_r($result);exit;