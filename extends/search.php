<?php
/*
 * 依據GET內容進行戰鬥運算
 */
session_start();
header('Content-type: application/json; charset=utf-8');
//載入身分檢查程序
//require_once("check.php"); //有問題
require_once("search/search-merge.php");
require_once("search/search-log.php");

$data = $_GET['data'];
$id = $_GET['id'];

$merge = new DataMerge();
$JSON = $merge->GetData($data, $id);
$log = new BattleLog($JSON);
$JSON['map'] = $JSON['maps']['id'];
unset($JSON['maps']);
foreach((array)$JSON['teamA'] as $key => $value) {
	unset($JSON['teamA'][$key]['item']);
	unset($JSON['teamA'][$key]['skill']);
	unset($JSON['teamA'][$key]['atk']);
	unset($JSON['teamA'][$key]['dis']);
}
foreach((array)$JSON['teamB'] as $key => $value) {
	unset($JSON['teamB'][$key]['item']);
	unset($JSON['teamB'][$key]['skill']);
	unset($JSON['teamB'][$key]['atk']);
	unset($JSON['teamB'][$key]['dis']);
}
$JSON['log'] = $log->ShowLog();

$DB = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$DB->connect();
require("data/data-mission.php");
if($data == 'battle') {
	$rowA = $DB->query_array("SELECT status FROM user WHERE uid='".$_SESSION['info']['uid']."'");
	$rowA = json_decode($rowA['status'], true);
	$rowB = $DB->query_array("SELECT status FROM user WHERE uid='".$id."'");
	$rowB = json_decode($rowB['status'], true);
	if($JSON['log'][count($JSON['log'])-1]['who'] == 0) {
		$rowA['win']++;
		$rowB['lose']++;
	}
	else if($JSON['log'][count($JSON['log'])-1]['who'] == 1) {
		$rowA['lose']++;
		$rowB['win']++;
	}
	$DB->query("UPDATE user SET status='".json_encode($rowA)."' WHERE uid='".$_SESSION['info']['uid']."'");
	$DB->query("UPDATE user SET status='".json_encode($rowB)."' WHERE uid='".$id."'");
}
else if($data == 'mission'){
	$row = $DB->query_array("SELECT status FROM user WHERE uid='".$_SESSION['info']['uid']."'");
	$row = json_decode($row['status'], true);
	if($JSON['log'][count($JSON['log'])-1]['who'] == 0) {
		$row['win']++;
		$row['exp'] += $mission[$id]['exp'];
		$row['coin'] += $mission[$id]['coin'];
	}
	else if($JSON['log'][count($JSON['log'])-1]['who'] == 1) {
		$row['lose']++;
	}
	$DB->query("UPDATE user SET status='".json_encode($row)."' WHERE uid='".$_SESSION['info']['uid']."'");
}
$DB->close();
echo json_encode($JSON);
?>