<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?=L('site_name');?></title>
	<link rel="stylesheet" type="text/css" href="<?=__STATIC__;?>css/default.css" />
	<link rel="stylesheet" type="text/css" href="<?=__STATIC__;?>css/nivo-slider.css" />
	<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.js"></script>
	<script type="text/javascript" src="<?=__STATIC__;?>js/jquery.nivo.slider.pack.js"></script>
	<script type="text/javascript">
	    $(window).load(function() {
		    $('#slider1').nivoSlider({
		      pauseTime:4000
		    });
	    });
	</script>
</head>
<body>
	<div class="all">
		<div class="head clearfix mb10">
			<h1 class="logo"><a href="<?=__DOMAIN__;?>"><img src="<?=__STATIC__;?>images/logo.jpg" alt="" /></a></h1>
			<p class="headRight">中国教育学会十一·五子课题实验基地</p>
		</div>
		<div class="nav clearfix">
			<ul id="sddm">
				<li><a href="<?=__DOMAIN__;?>"><?=L('index');?></a></li>
				<?php if(isset($main_menus)){?>
					<?php foreach($main_menus as  $menu){?>
						<li>
							<?php if(isset($menu['direct_cates']) && !empty($menu['direct_cates'])){?>
								<?php if(empty($menu['link_url'])){?>
									<a href="<?=__DOMAIN__;?>show/index.html?id=<?=$menu['id'];?>" target="_blank" class="direct_cates" onmouseover="mopen('m<?=$menu['id'];?>')" onmouseout="mclosetime()"><?=$menu['name'];?></a>
								<?php }else{?>
									<a href="<?=$menu['link_url'];?>" target="_blank" onmouseover="mopen('m<?=$menu['id'];?>')" onmouseout="mclosetime()"><?=$menu['name'];?></a>
								<?php }?>
								<div id="m<?=$menu['id'];?>" class="direct_cates" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
									<?php foreach($menu['direct_cates'] as $cate){?>
										<?php if(empty($menu['link_url'])){?>
										<a href="<?=__DOMAIN__;?>show/index.html?id=<?=$cate['id'];?>" target="_blank"><?=$cate['name'];?></a>
										<?php }else{?>
											<a href="<?=$cate['link_url'];?>" target="_blank"><?=$cate['name'];?></a>
										<?php }?>
									<?php }?>
								</div>
							<?php }else{?>
								<?php if(empty($menu['link_url'])){?>
								<a href="<?=__DOMAIN__;?>show/index.html?id=<?=$menu['id'];?>" target="_blank"><?=$menu['name'];?></a>
								<?php }else{?>
									<a href="<?=$menu['link_url'];?>" target="_blank"><?=$menu['name'];?></a>
								<?php }?>
							<?php }?>
						</li>
					<?php }?>
				<?php }?>
			</ul>
			<div class="nav_r"></div>
		</div>
		<!-- 
	  <div class="banner">
	    <div id="slider1">
	      <a href="javascript:"><img src="<?=__STATIC__;?>images/banner1.jpg" title="我是第一张图片的介绍说明" /></a>
	      <a href="javascript:"><img src="<?=__STATIC__;?>images/banner2.jpg" title="我是第一张图片的介绍说明" /></a>
	      <a href="javascript:"><img src="<?=__STATIC__;?>images/banner3.jpg" title="我是第一张图片的介绍说明" /></a>
	      <a href="javascript:"><img src="<?=__STATIC__;?>images/banner4.jpg" title="我是第一张图片的介绍说明" /></a>
	    </div>
	  </div>-->
	  	<div class="con clearfix">
			<div class="con_l"><!-- 
				<div class="conl_cp mb10">
					<div class="conl_cp_title">最近新闻</div>
					<ul>
						<?php if(isset($latest) && is_array($latest)){?>
							<?php foreach($latest as $key => $value){?>
								<li><a href="<?=__DOMAIN__;?>show/detail.html?id=<?=$value['id'];?>" target="_blank" title="<?=$value['title'];?>"><?=SUtilComponent::substr($value['title'], 8);?></a></li>
							<?php }?>
						<?php }?>
					</ul>
				</div>-->
				<div class="conl_contact">
					<h4 class="conl_tilte2">联系我们</h4>
					<div>
						<?=$address;?>
					</div>
				</div>
			</div>
			<?=$content_for_layout;?>
		</div>
		<div class="foot">
			<!-- 
			<div class="nav2">
				<a href="">底部导航</a>|<a href="">底部导航</a>|<a href="">底部导航</a>|<a href="">底部导航</a>|<a href="">底部导航</a>
			</div>
			-->
			<p>Copyright © 2013   语之花 版权所有</p>
		</div>
	</div>
	<?=$content_for_js;?>
	<script type="text/javascript">
			<!--
			var timeout         = 500;
			var closetimer		= 0;
			var ddmenuitem      = 0;
			// open hidden layer
			function mopen(id){	
				// cancel close timer
				mcancelclosetime();
			
				// close old layer
				if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
			
				// get new layer and show it
				ddmenuitem = document.getElementById(id);
				ddmenuitem.style.visibility = 'visible';
			}
			// close showed layer
			function mclose(){
				if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
			}
			// go close timer
			function mclosetime(){
				closetimer = window.setTimeout(mclose, timeout);
			}
			// cancel close timer
			function mcancelclosetime(){
				if(closetimer){
					window.clearTimeout(closetimer);
					closetimer = null;
				}
			}
			// close layer when click-out
			document.onclick = mclose; 
			
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