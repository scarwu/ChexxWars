<?php
//地圖設定
$maps = array(
	'000' => array(
		'id' => '000',
		'name' => '荒地',
		'map' => array(
			array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
			array(0,1,0,0,0,1,0,1,1,1,1,0,1,0,0,0,1,0),
			array(0,0,0,0,1,0,0,1,1,1,1,0,0,1,0,0,0,0),
			array(0,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0),
			array(0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,0),
			array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
			array(0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,0),
			array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
			array(0,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0),
			array(0,0,0,0,1,0,0,1,1,1,1,0,0,1,0,0,0,0),
			array(1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,1),
			array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)
		)
	),
	'001' => array(
		'id' => '001',
		'name' => '火焰島',
		'map' => array(
			array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
			array(1,1,0,0,0,0,1,1,1,1,0,0,0,0,0,0,1,1),
			array(1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,1),
			array(1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,1),
			array(1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1),
			array(1,0,0,0,0,0,0,1,0,0,1,0,0,0,0,0,0,1),
			array(1,0,0,0,0,0,0,1,0,0,1,0,0,0,0,0,0,1),
			array(1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1),
			array(1,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1),
			array(1,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1),
			array(1,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,1,1),
			array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1)
		)
	),
	'002' => array(
		'id' => '002',
		'name' => '叢林',
		'map' => array(
			array(1,1,1,0,0,0,0,0,1,1,0,0,0,0,0,1,1,1),
			array(1,1,1,0,0,1,1,0,1,1,0,1,1,0,0,1,1,1),
			array(1,0,0,0,1,1,0,0,1,1,0,0,1,1,0,0,0,1),
			array(1,0,0,0,1,1,0,0,1,1,0,0,1,1,0,0,0,1),
			array(1,0,0,0,1,1,0,0,0,0,0,0,1,1,0,0,0,1),
			array(1,0,0,0,1,1,0,0,0,0,0,0,1,1,0,0,0,1),
			array(1,0,0,0,1,1,1,1,0,0,1,1,1,1,0,0,0,1),
			array(1,0,0,0,1,1,1,1,0,0,1,1,1,1,0,0,0,1),
			array(1,0,0,0,1,1,1,1,0,0,1,1,1,1,0,0,0,1),
			array(1,0,0,0,1,1,1,1,0,0,1,1,1,1,0,0,0,1),
			array(1,1,1,0,0,1,1,1,0,0,1,1,1,0,0,1,1,1),
			array(1,1,1,1,0,0,0,0,0,0,0,0,0,0,1,1,1,1)
		)
	),
	'003' => array(
		'id' => '003',
		'name' => '酒吧',
		'map' => array(
			array(1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1),
			array(1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,1),
			array(1,0,0,0,1,1,0,1,1,1,1,0,1,1,0,0,0,1),
			array(1,0,0,0,1,1,0,0,0,0,0,0,1,1,0,0,0,1),
			array(1,0,0,0,1,1,1,1,1,1,1,1,1,1,0,0,0,1),
			array(1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1),
			array(1,0,0,0,1,1,1,1,0,0,1,1,1,1,0,0,0,1),
			array(1,0,0,0,1,1,1,1,0,0,1,1,1,1,0,0,0,1),
			array(1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1),
			array(1,1,0,0,1,1,1,1,0,0,1,1,1,1,0,0,1,1),
			array(1,1,1,0,1,1,1,1,0,0,1,1,1,1,0,1,1,1),
			array(1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1)
		)
	)
);
?>
