<?php
include("phpgraphlib.php");

$title = unserialize(urldecode(stripslashes($_GET['title'])));
$target = unserialize(urldecode(stripslashes($_GET['target'])));
$data = unserialize(urldecode(stripslashes($_GET['data'])));

$graph=new PHPGraphLib(1024,330);

$graph->addData($data);

$graph->setBarColor('#14568A');
$graph->setTitle($title);

if($target > 0) {
	$graph->setGoalLine($target);
	$graph->setGoalLineColor('red');
}

$graph->setupYAxis(false);
$graph->setupXAxis(30, '#EEE');

$graph->setDataValues(true);

$graph->setTitleLocation('left');
$graph->setTitleColor('#262626');

$graph->setGrid(false);
$graph->setLegend(false);
$graph->setYValues(false);

$graph->createGraph();
?>