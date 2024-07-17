	<div id="display" class="fade">
		<img src="/img/common/logo_top.png"/>
	</div>
	<section class="news">
		<article>
			<h2>おしらせ</h2>
			<h3 style="text-align:center; color:crimson">
				<a href="/applist#pelogen2" style="">
					【PICO-8】コンパクト3Dモデリングソフト<br />
					PELOGEN2をリリース！！
				</a>
			</h3>
			<a href="https://blog.bitchunk.net/tag/pelogen/" style="text-align:center; color:crimson; vertical-align: top; overflow:hidden;">
				<p style="text-align: center;"><img src="/img/applist/pelogen2_02.gif" style="height:10rem; width:auto;" /></p>
				<p style="text-align: center;">ローレゾローポリモデリング「PELOGEN」がパワーアップ！</p>
				<p style="text-align: center;">操作をblenderに寄せつつ、座標解像度を31x31x31に拡大など</p>
			</a>
			<hr>
			<h3 style="text-align:center; color:crimson">
				<a href="/applist#spranimkit" style="">
					【PICO-8】仮想ピアノ・スケール参照ソフト<br />
					μ-ScaleLabをリリース！！
				</a>
			</h3>
			<p style="text-align: center;"><img src="/img/applist/mu-scalelab_01.gif" style="height:10rem; width:auto;" /></p>
			<hr>
			<h3 style="text-align:center; color:crimson">
				<a href="/applist#spranimkit" style="">
					PICO-8用アニメーションツール<br />
					SPRANIM-KITをリリース！！
				</a>
			</h3>
			<p style="text-align: center;"><img src="/img/applist/spranimkit_01.gif" style="height:10rem; width:auto;" /></p>
			<hr>
			<h3 style="text-align:center; color:crimson"><a href="/applist#konsairi"><strong>根菜！きつね！<br />KONSAIRI公開中！</strong></a></h3>
			<p style="text-align: center;"><img src="/img/applist/konsairi_02.jpg" style="height:10rem; width:auto;" /></p>
			<hr>
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
			<hr>
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
