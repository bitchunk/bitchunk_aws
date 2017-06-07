<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="Thu, 01 Dec 1994 16:00:00 GMT">
<meta name="viewport" content="width=320, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

<?php
	require_once(VIEW_PATH . '/headerbase/'. DispatchController::$headerBase. '.php');
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="<?php echo SITE_AUTHER; ?>">

<?php
 if(!empty(DispatchController::$cardsmeta)){
	require_once(VIEW_PATH . '/cardsmeta/'. DispatchController::$cardsmeta. '.php');
}else{
	require_once(VIEW_PATH . '/cardsmeta/'. DispatchController::$DEFAULT_CARDSMETA. '.php');
}
?>

<!-- <meta name="viewport" content="width=device-width; initial-scale=1.0"> -->

<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
<link rel="stylesheet" href="/css/common.css" />

<script src="/js/lib/jquery-2.0.0.min.js"></script>
<script src="/js/lib/jquery.easing.1.3.js"></script>
<script src="/js/common.js"></script>

<script src="https://apis.google.com/js/platform.js" async defer>
	{lang: 'ja'}
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46774451-6', 'auto');
  ga('send', 'pageview');

</script>
<script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));</script>
<?php
foreach(DispatchController::$additionalScripts as $index=>$filename){
	if(!isURL($filename)){
		echo '<script src="/js/'. $filename. '"></script>'. "\n";
	}else{
		echo '<script src="'. $filename. '"></script>'. "\n";
	}
}
foreach(DispatchController::$additionalHeaders as $index=>$tags){
	echo $tags. "\n";
}
?>
<?php if(self::is_mobile()): ?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-5168032248020758",
    enable_page_level_ads: true
  });
</script>
<?php endif; ?>
</head>
<body>
<div id="container">
	<h1><span class="hide"><?php echo SITE_NAME; ?></span></h1>
	<header>
		<nav>
			<div class="head-layer">
				<h2><span class="hide">メニュー</span><a href="<?php echo PROTOCOL_HOST; ?>"><img id="banner" src="/img/common/logo_header.png"></a></h2>
				<div class="menu">
					<a href="<?php echo PROTOCOL_HOST; ?>"><button class="home">ホーム</button></a>
					<a href="/applist"><button class="apps">アプリ</button></a>
					<a href="/lab"><button class="lab">実験室</button></a>
					<a href="/about"><button class="about">サイト概要</button></a>
					<a href="<?php echo BLOG_HOST; ?>"><button class="blog">ブログ</button></a>
				</div>
				<hr class="clear" />
			</div>
			<hr class="clear" />
			<?php echo DispatchController::outputBreadCrumb(); ?>
			<hr class="clear" />
			<!-- <div class="donate"><?php if(!empty(self::$donateButton)){echo self::$donateButton;}?></div> -->
			
		</nav>
	</header>
	<div class="contents">

