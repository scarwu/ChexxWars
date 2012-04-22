<?php
/*
 * 依據GET內容進行資料寫入寫出動作
 */
session_start();
header('Content-type: application/json; charset=utf-8');
//載入身分檢查程序
//require_once("check.php"); //有問題
require_once("shell/shell-io.php");

if(isset($_GET['action'])) {
	$IO = new InputOutput();
	$action = $_GET['action'];
	if($action == 'read') {
		echo $IO->Read($_GET['data'], $_GET['id']);
	}
	elseif($action == 'write') {
		$IO->Write($_GET['data'], $_GET['idA'], $_GET['idB']);
		echo $IO->Read($_GET['data'], $_GET['idA']);
	}
}
?>
