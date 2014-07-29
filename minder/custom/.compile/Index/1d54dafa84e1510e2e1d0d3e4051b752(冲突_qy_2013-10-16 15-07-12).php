<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link type="text/css" rel="stylesheet" href="<?=__PUBLIC__;?>css/main.css" />
		<title>盟达投资</title>
		<?=$content_for_css;?>
	</head>
	<body>
		<?=$content_for_layout;?>
			<?php if(isset($current) && isset($current['module']) && $current['module'] == 'index'){?>
				<div class="bottom">
			<?php }else{?>
			<div class="bottom_list">
			<?php }?>
			<div class="link">
				<ul>
					<li>友情链接 |</li>
					<li><a href="http://tj.28.com/" target="_blank">创业项目加盟</a></li>
					<li><a href="http://chongqing.rong360.com" target="_blank">贷款融资</a></li>
					<li><a href="http://www.wincn.com/" target="_blank">赢在中国网</a></li>
					<li><a href="http://www.yingfu001.com/" target="_blank">赢富财经</a></li>
					<li><a href="http://cq.edai.com/" target="_blank">重庆贷款</a></li>
					<li><a href="http://www.xianhuo518.com/" target="_blank">渤海现货</a></li>
					<li><a href="http://www.liansuo.com" target="_blank">珠宝加盟网</a></li>
					<li><a href="http://www.sopai.com.cn/" target="_blank">搜拍网</a></li>
					<li><a href="http://www.chinaacc.com/" target="_blank">银行从业考试</a></li>
					<li><a href="http://money.laoqianzhuang.com/" target="_blank">老钱庄理财</a></li>
					<li><a href="http://www.hx9999.com/cn">白银网</a></li>
				</ul>
			</div>
			<p>Copyright © 2013—2018 www.mdtz.com Inc. All Rights Reserved.</p>
		</div><!--end bottom-->
		<?=$content_for_js;?>
	</body>
</html>