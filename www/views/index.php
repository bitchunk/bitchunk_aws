	<div id="display" class="fade">
		<img src="/img/common/logo_top.png"/>
	</div>
	<section class="news">
		<article>
			<h2>おしらせ</h2>
			<h3 style="text-align:center; color:crimson"><strong>2Dアクションゲーム(Platformer)<br />KONSAIRIを無料公開中（β版）</strong></h3>
			<a href="https://bitchunk.itch.io/konsairi" style="text-align:center; color:crimson; vertical-align: top; overflow:hidden;">
				<p style="text-align: center;"><img src="/img/applist/konsairi_01.gif" style="height:10rem; width:auto;" /></p>
				<p style="text-align: center;"><strong>きつね</strong>の仔が主人公！<strong>根菜</strong>を採りながら冒険！！</p>
			</a>
			<hr>
			<h3 style="text-align:center; color:crimson">多人数非同期対戦ボードゲーム<br />PITMAPを公開中</h3>
			<a href="https://pitmap.bitchunk.net" style="text-align:center; color:crimson; vertical-align: top; overflow:hidden;">
				<p style="text-align: center;"><img src="/img/applist/pitmap_02.jpg" style="height:10rem; width:auto;" /></p>
				<p style="text-align: center;">フィールドに隠されたカプセルを見つけよう！</p>
			</a>
			<hr/>
			<?php $log = self::siteUpdatesLog(); ?>
			<?php foreach($log as $row): ?>
			<p><span class="date"><?php echo $row['date']; ?></span><?php echo $row['text']; ?></p>
			<?php endforeach; ?>
		</article>
	</section>
	<section>
		<article>
			<nav>
				<h2>サイトメニュー</h2>
				<div class="menu">
					<p><a href="<?php echo PROTOCOL_HOST; ?>"><button class="home">ホーム</button><span>bitchunk ホーム画面</span></a></p>
					<p><a href="/applist"><button class="apps">アプリ</button><span>アプリ／制作物一覧</span></a></p>
					<p><a href="/lab"><button class="lab">研究室</button><span>ラボ／実験的なもの</span></a></p>
					<p><a href="/about"><button class="about">サイト概要</button><span>サイト概要</span></a></p>
					<p><a href="<?php echo BLOG_HOST; ?>"><button class="blog">ブログ</button><span>ブログ／bitchunk.log</span></a></p>
					<p><a href="/press"><button class="press">プレスキット</button><span>プレスキット／参考資料</span></a></p>
				</div>
			</nav>
		</article>
	</section>
