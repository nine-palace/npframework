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
				<input type="text" id="dir_input" name="" class="field_input"/>
				<input type="button" id="import_button" value="Import" class="button_input"/>
			</div>
			
			<div class="content">
				<table id="content_list" class="table_list">
					<tr>
						<th>序号</th>
						<?php if(is_array($fields)){?>
							<?php foreach($fields as $key => $value){?>
								<th><?=$value;?></th>
							<?php }?>
						<?php }?>
					</tr>
				</table>
			</div>
			
			<div class="status" id="connect_status">
			
			</div>
		</div>
		<script type="text/javascript" src="<?=__PUBLIC__;?>js/jquery.js"></script>
		<script type="text/javascript">
			var socket;
			var count = 1;
			var length = <?=is_array($fields) ? count($fields) : 0;?>;
			length++;
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
				try{
					socket.send(dir);
					log(1, 'Send: ' + dir);
				}catch(ex){
					log(1, ex);
				}
			});
			function init(){
				  var host = "ws://<?=$address;?>:<?=$port;?>/";
				  try{
				    socket = new WebSocket(host);
				    log(1, 'WebSocket - status '+socket.readyState);
				    socket.onopen    = function(msg){ log(1, "Welcome - status "+this.readyState); };
				    socket.onmessage = function(msg){ log(2, msg.data); };
				    socket.onclose   = function(msg){ log(1, "Disconnected - status "+this.readyState); };
				  }
				  catch(ex){ }
			};
			function quit(){
			  log(1, "Goodbye!");
			  socket.close();
			  socket=null;
			}
			function log(flag, data){
				if(flag == 1){
					$('#connect_status').append("<br>" + data);
					$('#connect_status').scrollTop = $('#connect_status').scrollHeight;
				}else{
					data = eval("(" + data + ")")
					if(data.status == false || data.status == 'false'){
						$('#content_list').append("<tr><td colspan=" + length + " style='text-align:center;'>" + data.data + "</td></tr>");
					}else{
						var dom = "<tr>" +
								"<td>" + count + "</td>";
						<?php if(isset($fields) && is_array($fields)){?>
							<?php foreach($fields as $key => $value){?>
								dom += "<td>" + data['data'].<?=$key;?> + "</td>";
							<?php }?>
						<?php }?>
						dom += "</tr>";
						$('#content_list').append(dom);
					}
					$('#content_list').parent().scrollTop($('#content_list').height());
					count++;
				}
			}
		</script>
	</body>
</html>