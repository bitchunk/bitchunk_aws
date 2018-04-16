	<div id="display" class="fade">
		<img src="/img/common/logo_top.png"/>
	</div>
	<section class="news">
		<article>
			<h2>おしらせ</h2>
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
