<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="<?=__PUBLIC__;?>admin/login/freelanceSuite.css" />
		<title>网站管理平台</title>
	</head>
	<body>
		<div class="entry">
			<div class="title"><font>freelance suite</font></div>
			<div class="login">
				<div class="name">Login</div>
				<form action="" method="post">
					<table class="loginTable" width="100%">
						<tr>
							<td class="labelTd"><label>account:</label></td>
							<td class="textTd"><input type="text" name="username"/></td>
						</tr>
						<tr>
							<td class="labelTd"><label>password:</label></td>
							<td class="textTd"><input type="password" name="userpass" /></td>
						</tr>
						
						<tr class="msgTr">
							<td colspan="2"><?=isset($result) && $result['status'] == false ? $result['msg'] : '';?></td>
						</tr>
						<tr>
							<td class="loginTd"><input type="submit" value="Login" /></td>
							<td class="resetTd"><input type="reset" value="reset" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<!-- 
		<div class="entry">
			<div class="entry_logo"></div>
			<div class="login">
				<div class="content">
					<form action="" method="post">
						<ul>
							<li><label>账号:&nbsp;</Label><input name="username" type="text" /></li>
							<li><label>密码:&nbsp;</label><input name="userpass" id="submit_pass" type="password" /></li>
							<?php if(isset($result) && $result['status'] == false){?>
								<li style="line-height:20px;"><p style="text-align:center;"><?=$result['msg'];?></p></li>
							<?php }?>
							<li><a href="###" id="submit_a">登录</a></li>
						</ul>
					</form>
				</div>
			</div>
			<div class="bot">Copyright © 2008—2012 <?=$system_site_name;?>网站管理后台</div>
		</div>
		-->
		<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('#submit_a').click(function(){
					$('form').submit();
				});
			});
			$('#submit_pass').keyup(function(k){
				if(k.keyCode == 13){
					$('#submit_a').click();
				}
			});
		</script>
	</body>
</html>
