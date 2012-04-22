<?php
require_once("extends/check.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CHEXX Wars</title>
<link rel="stylesheet" type="text/css" href="css/main.css"/>
<link rel="stylesheet" type="text/css" href="css/interface.css"/>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.ui.js"></script>
<script type="text/javascript" src="js/jquery.swfobject.js"></script>
<script type="text/javascript" src="js/function.js"></script>
<script type="text/javascript" src="js/interface.js"></script>
</head>
<body>
<div id="fb-root">
	<fb:like href="www.facebook.com/apps/application.php?id=348422954086" layout="button_count" show_faces="false" width="100"></fb:like>
	<div id="main">
		<div id="banner">
			<img src="images/ChexxLogo.png"><br />
			<div id="main_option">
				<ul class="main_option">
					<li><a href="javascript:void(0);" _data="status">狀態</a></li>
					<li><a href="javascript:void(0);" _data="store">商店</a></li>
					<li><a href="javascript:void(0);" _data="mission">任務</a></li>
					<li><a href="javascript:void(0);" _data="battle">對戰</a></li>
					<li><a href="javascript:void(0);" _data="friends">朋友</a></li>
				</ul>
			</div>
			<div id="bar"></div>
		</div>
		<div id="content">
        	<div id="msg"></div>
			<div id="replayer"></div>
			<div id="container">
				<div class="top">
					<div id="sub_option">
						<ul id="status" class="sub_option">
							<li><a href="javascript:void(0);" _data="status" _id="hero">英雄狀態</a></li>
							<li><a href="javascript:void(0);" _data="status" _id="arrange">戰鬥配置</a></li>
						</ul>
						<ul id="store" class="sub_option">
							<li><a href="javascript:void(0);" _data="store" _id="hero">英雄</a></li>
							<li><a href="javascript:void(0);" _data="store" _id="skill">技能</a></li>
							<li><a href="javascript:void(0);" _data="store" _id="item">物品</a></li>
						</ul>
						<ul id="mission" class="sub_option">
							<li><a href="javascript:void(0);" _data="mission" _id="000">Lv1 荒地</a></li>
							<li><a href="javascript:void(0);" _data="mission" _id="001">Lv2 熔岩</a></li>
							<li><a href="javascript:void(0);" _data="mission" _id="002">Lv3 叢林</a></li>
							<li><a href="javascript:void(0);" _data="mission" _id="003">Lv4 酒吧</a></li>
						</ul>
					</div>
				</div>
				<div class="body">
					<div class="middle"></div>
					<div class="bottom"></div>
				</div>
			</div>
			<div id="footer">
				© copyright 2010-2011 CHEXX Wars<br /><a href="javascript:void(0);" class="copyright">版權宣告</a>
			</div>
		</div>
	</div>
</div>
<script>
window.fbAsyncInit = function() {
	FB.init({appId: '<?php echo APP_ID; ?>', status: true, cookie: true, xfbml: true});
	FB.Canvas.setAutoResize(100);
};
(function() {
	var e = document.createElement('script');
	e.async = true;
	e.src = document.location.protocol + '//connect.facebook.net/zh_TW/all.js';
	document.getElementById('fb-root').appendChild(e);
}());
</script>
</body>
</html>