<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Resume</title>
		<link rel="stylesheet" type="text/css" href="<?=__PUBLIC__?>css/main.css" />
		<?=$content_for_css;?>
	</head>
	<body>
		<div class="header">
			<div class="header_logo"></div>
			<div class="header_menu">
				<ul>
					<?php foreach ($menu as $k => $m){?>
					<li><a href="<?=$k;?>"><?=$m;?></a></li>
					<?php }?>
				</ul>
			</div>
			<div class="header_focus"></div>
		</div>
		<?=$content_for_layout;?>
		<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.js"></script>
		<script type="text/javascript" src="<?=__PUBLIC__;?>js/control.js"></script>
		<script type="text/javascript">
		</script>
		<?=$content_for_js;?>
	</body>
</html>