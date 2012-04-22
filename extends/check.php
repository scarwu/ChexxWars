<?php
session_start();
//載入核心元件
header('charset=utf-8');
require_once("core.php");
//Facebook連結
$FB = new Facebook(APP_API_KEY, APP_SECRET);
$FB->require_login();
$FB_api = $FB->api_client;

//取得Facebook User_ID (uid)
$FB_uid = $FB_api->users_getLoggedInUser();

//資料庫連結
$DB = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$DB->connect();

//資料庫查詢使用者是否存在
$DB_row = $DB->query_array("SELECT uid FROM user WHERE uid='".$FB_uid."'");
if(!$DB_row) {
//新建使用者資料
	require_once("data/data-default.php");
	$DB->query("INSERT user SET uid='".$FB_uid."', status='".$default_status."', item='".$default_item."', skill='".$default_skill."', arrange='".$default_arrange."', hero='".$default_hero."'");
	$DB_row = $DB->query_array("SELECT uid FROM user WHERE uid='".$FB_uid."'");
}
//關閉資料庫連結
$DB->close();
if($DB_row and isset($FB_uid)) {
	//依據 uid 取得相關資料
	$info = $FB_api->users_getInfo($FB_uid, 'uid, name, pic_square');
	$friends_list = $FB_api->friends_getAppUsers();
	$friends = $FB_api->users_getInfo($friends_list, 'uid, name, pic_square');
	
	//產生SESSION資料
	session_register('login');
	session_register('info');
	session_register('friends');
	session_register('friends_list');

	$_SESSION['login'] = true;
	$_SESSION['info'] = $info[0];
	$_SESSION['friends'] = $friends;
	$_SESSION['friends_list'] = $friends_list;
}
?>