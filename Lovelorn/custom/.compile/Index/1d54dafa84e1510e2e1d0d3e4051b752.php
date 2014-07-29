<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?=$system_site_name;?></title>
	<link rel="stylesheet" type="text/css" href="<?=__PUBLIC__;?>css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?=__PUBLIC__;?>css/nivo-slider.css" />
	<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.js"></script>
	<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.nivo.slider.pack.js"></script>
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
			<h1 class="logo"><a href=""><img src="<?=__PUBLIC__;?>images/logo.gif" alt="" /></a></h1>
			<div class="nav clearfix">
				<ul>
					<li><a href="<?=__DOMAIN__;?>">网站首页</a></li>
					<?php if(isset($main_menus)){?>
						<?php foreach($main_menus as  $menu){?>
							<li>
								<?php if(isset($menu['direct_cates']) && !empty($menu['direct_cates'])){?>
									<?php if(empty($menu['link_url'])){?>
										<a href="<?=__DOMAIN__;?>show/index.html?id=<?=$menu['id'];?>" target="_blank" onmouseover="mopen('m<?=$menu['id'];?>')" onmouseout="mclosetime()"><?=$menu['name'];?></a>
									<?php }else{?>
										<a href="<?=$menu['link_url'];?>" target="_blank"><?=$menu['name'];?></a>
									<?php }?>
									<div id="m<?=$menu['id'];?>" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
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
		</div>
	  <div class="banner">
	    <div id="slider1">
	      <a href="javascript:"><img src="<?=__PUBLIC__;?>images/banner1.jpg" title="我是第一张图片的介绍说明" /></a>
	      <a href="javascript:"><img src="<?=__PUBLIC__;?>images/banner2.jpg" title="我是第一张图片的介绍说明" /></a>
	      <a href="javascript:"><img src="<?=__PUBLIC__;?>images/banner3.jpg" title="我是第一张图片的介绍说明" /></a>
	      <a href="javascript:"><img src="<?=__PUBLIC__;?>images/banner4.jpg" title="我是第一张图片的介绍说明" /></a>
	    </div>
	  </div>
	  	<div class="con clearfix">
			<div class="con_l">
				<div class="conl_cp mb10">
					<div class="conl_cp_title">最近新闻</div>
					<ul>
						<?php if(isset($latest) && is_array($latest)){?>
							<?php foreach($latest as $key => $value){?>
								<li><a href="<?=__DOMAIN__;?>show/detail.html?id=<?=$value['id'];?>" target="_blank" title="<?=$value['title'];?>"><?=SUtilComponent::substr($value['title'], 8);?></a></li>
							<?php }?>
						<?php }?>
					</ul>
				</div>
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
			<div class="nav2">
				<a href="">底部导航</a>|<a href="">底部导航</a>|<a href="">底部导航</a>|<a href="">底部导航</a>|<a href="">底部导航</a>
			</div>
			<p>Copyright © 2004-2011 DEDECMS. 织梦科技 版权所有</p>
		</div>
	</div>
</body>
</html>