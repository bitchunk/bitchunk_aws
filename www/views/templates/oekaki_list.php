<?php
	$pictDirs = $args[0];
	$title = $args[1];
?>
			<h2><?php echo $title; ?></h2>
<?php foreach($pictDirs as $year=>$pictYear){ ?>
			<time><?php echo $year; ?></time>
			<hr class="clear"/>
			<ul>
<?php	 foreach($pictYear as $pict){ ?>
				<li><a href="<?php echo $pict['pict']; ?>"><img src="<?php echo $pict['thumb']; ?>" alt="<?php echo preg_replace('/\.\w+$/', '', mb_convert_encoding(basename(urldecode($pict['thumb'])), 'utf-8', 'sjis-win')); ?>" /></a></li>
<?php	 } ?>
			<hr class="clear" />
			</ul>
<?php } ?>