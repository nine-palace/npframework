<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?=L('site_name');?></title>
	<link rel="stylesheet" type="text/css" href="<?=__STATIC__;?>css/default.css" />
	<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.js"></script>
</head>
<body>
	<div class="topFixed"></div>
	<div class="all">
		<div class="head clearfix mb10">
			<h1 class="logo"><a href="<?=__DOMAIN__;?>"><img src="<?=__STATIC__;?>images/logo.png" alt="" /></a></h1>
			<p class="headRight">中国教育学会十一·五子课题实验基地</p>
		</div>
		<div class="nav clearfix">
			<ul id="topNav">
				<li><a href="<?=__DOMAIN__;?>"><?=L('index');?></a></li>
				<?php foreach($main_menus as $menu){?>
					<li>
						<?php if(empty($menu['link_url'])){?>
							<a href="<?=__DOMAIN__;?>show/index.html?id=<?=$menu['id'];?>" target="_blank"><?=$menu['name'];?></a>
						<?php }else{?>
							<a href="<?=$menu['link_url'];?>" target="_blank"><?=$menu['name'];?></a>
						<?php }?>
						<?php if(isset($menu['direct_cates']) && !empty($menu['direct_cates'])){?>
							<div class="secondNav">
								<?php foreach($menu['direct_cates'] as $cate){?>
										<?php if(!empty($cate['link_url'])){?>
											<a href="<?=$cate['link_url'];?>" target="_blank"><?=$cate['name'];?></a>
										<?php }else{?>
											<a href="<?=__DOMAIN__;?>show/index.html?id=<?=$cate['id'];?>" target="_blank"><?=$cate['name'];?></a>
										<?php }?>
								<?php }?>
							</div>
						<?php }?>
					</li>
				<?php }?>
			</ul>
		</div>
	  	<div class="con clearfix">
			<?=$content_for_layout;?>
		</div>
		<div class="address">
			<p>双楠教学中心:武侯区双楠路326号富港中心7楼716-718(人人乐旁) 电话:028-85014016</p>
			<p>紫荆教学中心:高新区神仙树西路3好写字楼12楼32-33(风兰庭酒店楼上)</p>
		</div>
		<div class="foot">
			<p>Copyright © 2013   语之花 版权所有&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=__DOMAIN__;?>show/index.html?id=" target="_blank">加入我们</a></p>
		</div>
	</div>
	<?=$content_for_js;?>
	<script type="text/javascript">
			function addFavorite(){
				var url = location.href;
				var title = document.title;
				try{
					window.external.addFavorite(url, title);
				}catch(e){
					try{
						window.sidebar.addPanel(title, url, '');
					}catch(e){
						alert("加入收藏失败，请使用Ctrl+D进行添加!");
					}
				}
			}
			function setHomePage(obj){
				var url = location.href;
				try{
					obj.style.behavior = 'url(#default#homepage)';
					obj.setHomePage(url);
				}catch(e){
					alert("您的浏览器不支持该操作!");
				}
			}
			// -->
		</script>
</body>
</html>