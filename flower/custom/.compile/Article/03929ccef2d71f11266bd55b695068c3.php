<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?=$current['title'];?></title>
		<link rel="stylesheet" href="<?=__STATIC__;?>css/admin.css" type="text/css" />
		<?=$content_for_css;?>
	</head>
	<body>
		<div class="header">
			<div class="shell">
				<div class="top">
					<h1><a href="<?=__DOMAIN__;?>"><?=L('site_name');?></a></h1>
					<div class="top_navigation">
						Welcome <strong><?=$current['admin_user'];?></strong>
						<span>|</span>
						<a href="<?=$domain;?>login/logout.html"><?=L('logout');?></a>
					</div>
				</div>
				
				<div class="navigation">
					<ul>
						<?php foreach($menus as $menu){?>
								<li><a href="<?=$domain.$menu;?>.html" <?php if($current['module'] == $menu){?>class="active" <?php }?>><span><?=$menu == 'article' ? L('site_content') : L($menu);?></span></a></li>
						<?php }?>
					</ul>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="shell">
				<div class="small_nav">
					<a href="<?=$domain;?>"><?=L('index');?></a>
					<span>&gt;</span>
					<a href="<?=$domain.$current['module'];?>"><?=L($current['module']);?></a>
					<span>&gt;</span>
					<?php if (isset($current['title'])){?>
					<span><?=$current['title'];?></span>
					<?php }?>
				</div>
				<?php if(isset($return_message)){?>
					<div class="msg <?php if($return_message['status'] == true){?>msg-ok<?php }else{?>msg-error<?php }?>">
						<p><strong><?php if(!empty($return_message['msg'])){ echo $return_message['msg'];}else{ if($return_message['status'] == true){ echo '操作成功!';}else{ echo '操作失败';}}?></strong></p>
						<a href="#" class="close">close</a>
					</div>
				<?php }?>
				<br />
				<div class="main">
					<div class="cl">&nbsp;</div>
					<?=$content_for_layout;?>
					<div class="sidebar">
						<div class="box">
							<div class="box_head">
								<h2><?=L('control_panel');?></h2>
							</div>
							<div class="box_content">
								<?php if($current['action'] == 'modify'){?>
									<a href="<?=$domain.$current['module'];?>/index.html" class="add_button"><span><?=L(array($current['module'], 'ds', 'list'));?></span></a>
								<?php }else{?>
									<a href="<?=$domain.$current['module'];?>/modify.html" class="add_button"><span><?=L(array('add', 'ds', $current['module']));?></span></a>
								<?php }?>
								<div class="cl">&nbsp;</div>
								<?php if($current['action'] == 'list'){?>
									<p class="select_all"><input type="checkbox" class="checkbox all_select" /><label><?=L('select_all');?></label></p>
									<p><a href="###" class="delete_select"><?=L(array('delete', 'ds', 'select'));?></a></p>
									
									<div class="sort">
										<label><?=L('order');?></label>
										<select class="field order_by">
											<option value="order_asc"><?=L('order_by_order_field_asc');?></option>
											<option value="order_desc"><?=L('order_by_order_field_desc');?></option>
											<option value="time_asc"><?=L('order_by_time_asc');?></option>
											<option value="time_desc"><?=L('order_by_time_desc');?></option>
										</select>
									</div>
									
									<div class="sort">
										<label><?=L('category_filter');?></label>
										<select class="field select_by_part">
											<option value="-1"><?=L(array('all', 'ds', $current['module']));?></option>
											<?php foreach($parts as $key => $part){?>
												<option value="<?=$key;?>" <?php if($key == $current['id']){?>selected="selected"<?php }?>><?=$part;?></option>
											<?php }?>
										</select>
									</div>
								<?php }?>
							</div>
						</div>
					</div><!-- end of sidebar -->
					<div class="cl">&nbsp;</div>
				</div>
			</div>
		</div>
		
		<div class="footer">
			<div class="shell">
				<span class="left">&copy; 2010 - <?=L('common_company');?></span>
				<span class="right">
					<?=L('designer');?> <a href="#" target="_blank"><?=L('common_designer');?></a>
				</span>
			</div>
		</div>
		<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.js"></script>
		<script type="text/javascript">
			var $domain = '<?=$domain.$current['module'];?>';
		</script>
		<script type="text/javascript" src="<?=__STATIC__;?>js/admin.js"></script>
		<?=$content_for_js;?>
	</body>
</html>