<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?=$current['title'];?></title>
		<link rel="stylesheet" href="<?=__APP__;?>css/admin.css" type="text/css" />
		<?=$content_for_css;?>
	</head>
	<body>
		<div class="header">
			<div class="shell">
				<div class="top">
					<h1><a href="#">Nine Palace</a></h1>
					<div class="top_navigation">
						Welcome <a href="#"><strong>Administrator</strong></a>
						<span>|</span>
						<a href="#">Help</a>
						<span>|</span>
						<a href="#">Profile Settings</a>
						<span>|</span>
						<a href="#">Log Out</a>
					</div>
				</div>
				
				<div class="navigation">
					<ul>
						<? foreach($menus as $key => $menu){?>
							<? if($current['module'] == $key){ ?>
								<li><a href="<?=__DOMAIN__.$key;?>" class="active"><span><?=$menu;?></span></a></li>
							<? }else{ ?>
								<li><a href="<?=__DOMAIN__.$key;?>" class="active"><span><?=$menu;?></span></a></li>
							<? }?>
						<? }?>
						<li><a href="#" class="active"><span>Dashboard</span></a></li>
						<li><a href="#"><span>New Articles</span></a></li>
						<li><a href="#"><span>User Management</span></a></li>
						<li><a href="#"><span>Photo Gallery</span></a></li>
						<li><a href="#"><span>Products</span></a></li>
						<li><a href="#"><span>Services 	Control</span></a></li>
					</ul>
				</div>
			</div>
		</div>
		
		<?=$content_for_layout;?>
		
		<div class="footer">
			<div class="shell">
				<span class="left">&copy; 2010 - CompanyName</span>
				<span class="right">
					Design by <a href="#" target="_blank">Nine Palace</a>
				</span>
			</div>
		</div>
		
		<?=$content_for_js;?>
	</body>
</html>