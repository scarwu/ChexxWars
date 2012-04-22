<?php 
//基本狀態
$default_status = json_encode(array(
	'win' => 0, 
	'lose' => 0, 
	'exp' => 0, 
	'coin' => 500000
));

//擁有物品
$default_item = json_encode(array(
	'000' => array(
		'id' => '000', 
		'own' => 1
	)
));

//擁有技能
$default_skill = json_encode(array(
	'000' => array(
		'id' => '000', 
		'own' => 1
	)
));

//擁有英雄
$default_hero = json_encode(array(
	'000' => array(
		array(
			'id' => '000', 
			'order' => 0,
			'item' => array('000'), 
			'skill' => array('000')
		)
	)
));

//預設組合
$default_arrange = json_encode(array(
	array(
		'id' => '000',
		'order' => 0,
		'x' => 6,
		'y' => 1
	)
));
?>