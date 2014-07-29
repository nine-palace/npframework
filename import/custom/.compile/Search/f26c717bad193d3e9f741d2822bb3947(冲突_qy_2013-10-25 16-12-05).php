<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Insert title here</title>
		<link rel="stylesheet" href="<?=__STATIC__;?>css/main.css" type="text/css" />
	</head>
	<body onload="init();">
		<div class="entry">
			<div class="form">
				<form action="" method="get">
					<label>姓名:</label><input type="text" name="name" class=""/>
					<label>性别:</label>
					<select name="gender">
						<option value="">不限</option>
						<option value="M">男</option>
						<option value="F">女</option>
					</select>
					<input type="submit" id="import_button" value="Search" class="button_input"/>
				</form>
			</div>
			
			<div class="content" style="height:750px;">
				<table id="content_list" class="table_list">
					<tr>
						<th>姓名</th>
						<th>身份证</th>
						<th>性别</th>
						<th>地址</th>
					</tr>
					<?php if(isset($list) && is_array($list)){?>
						<?php foreach($list as $key => $value){?>
							<tr>
								<td><?=$value['name'];?></td>
								<td><?=$value['ctfId'];?></td>
								<td><?=strtolower($value['gender']) == 'f' ? '女' : '男';?></td>
								<td><?=$value['address'];?></td>
							</tr>
						<?php }?>
					<?php }?>
				</table>
				<?php if(isset($pages)){?>
					<div class="pagging">
						<?=$pages;?>
					</div>
				<?php }?>
			</div>
		</div>
		<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.js"></script>
		<script type="text/javascript">
			$('#dir_input').keypress(function(event){
				if(event.keyCode == 13){
					$('#import_button').click();
				}
			});
			$('#import_button').click(function(){
				var dir = encodeURI($('#dir_input').val());
				if(dir == '' || dir == null || dir == undefined){
					alert('文件不能为空!');
					return false;
				}
			});
		</script>
	</body>
</html>