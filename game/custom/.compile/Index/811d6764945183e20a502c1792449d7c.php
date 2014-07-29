<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Insert title here</title>
		<link rel="stylesheet" type="text/css" href="<?=__APP__?>css/poker.css" />
	</head>
	<body>
		<?php foreach($hands as $hand){?>
			<div>
			<?php foreach($hand as $card){?>
				<div class="poker <?=$card['numberClass']?> <?=$card['flowerClass']?>" style="float:left;">
					<div class="number"></div>
					<div class="flower"></div>
				</div>
			<?php }?>
			</div>
		<?php }?>
		<div>
			<?php foreach($rest as $re){?>
				<div class="poker <?=$re['numberClass']?> <?=$re['flowerClass']?>" style="float:left;">
					<div class="number"></div>
					<div class="flower"></div>
				</div>
			<?php }?>
		</div>
	</body>
</html>