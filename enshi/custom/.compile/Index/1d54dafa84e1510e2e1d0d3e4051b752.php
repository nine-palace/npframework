<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="<?=__PUBLIC__;?>css/main.css" />
		<?=$content_for_css;?>
		<title>恩施同乡在线</title>
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
	</head>
	<body>
		<div class="cm_topmenu">
			<div class="cm_topmenu_bd">
				<span>【<a href="#" onclick="setHomePage(this)" title="设置本站为浏览器首页">设为首页</a>】【<a href="#" onclick="addFavorite()" title="收藏本站到你的收藏夹">收藏本站</a>】</span>
				<font class="pl10">欢迎光临渝恩在线，让我们一起为家乡加油！</font>
			</div>
		</div>
		<div class="header"><img src="<?=__PUBLIC__;?>images/top.jpg" width="960" height="249" /></div>
		<div class="nav_menu_bd">
			<ul id="sddm">
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
		</div>
		<div class="hotline">
			<div class="search">
				<form action="<?=__DOMAIN__;?>show/index.html" method="get">
					<input name="keywords" type="text" value="<?=isset($current['keywords']) ? $current['keywords'] : '';?>" />
					<input class="bnt" type="submit"  value=""/>
					<span>热门词汇：
						<a href="<?=__DOMAIN__;?>show/index.html?keywords=恩施">恩施</a>&nbsp;&nbsp;
						<a href="<?=__DOMAIN__;?>show/index.html?keywords=恩施人在重庆">恩施人在重庆</a>&nbsp;&nbsp;
						<a href="<?=__DOMAIN__;?>show/index.html?keywords=恩施项目">恩施项目</a>&nbsp;&nbsp;
						<a href="<?=__DOMAIN__;?>show/index.html?keywords=恩施助">恩施助学</a></span>
				</form>
			</div>
		</div>
		<?=$content_for_layout;?>
		<div class="clear"></div>
		<div class="bottom">
			<p>商会地址：重庆XXXXXXX 电话：023-XXXXXXXX手机：186XXXXXXX 投稿邮箱：XXXXXXXX@163.com</p>
		</div>
		<?=$content_for_js;?>
	</body>
</html>