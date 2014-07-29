

$(document).ready(function(){
	$('.delete_single').click(function(){
		if(!confirm('确认要删除吗?')){
			return false;
		}else{
			var id = $(this).attr('id');
			$.ajax({
				type:'GET',
				url: $domain + '/delete.html?id=' + id,
				success:function(data){
					window.location.reload();
				}
			})
		}
		return false;
	});
	
	$('.delete_select').click(function(){
		if(!confirm('确认删除所选吗?')){
			return false;
		}else{
			var id = '';
			$('.single_select').each(function(){
				if($(this).attr('checked') == true){
					id += $(this).val() + ',';
				}
			});
			$.ajax({
				type:'GET',
				url: $domain + '/delete.html?id=' +　id,
				success:function(data){
					window.location.reload();
				}
			});
		}
		return false;
	});
	
	$('.all_select').click(function(){
		if($(this).attr('checked') == 'checked'){
			$('.single_select').attr('checked', true);
			$('.all_select').attr('checked', true);
		}else{
			$('.single_select').attr('checked', false);
			$('.all_select').attr('checked', false);
		}
	});
	
	$('.order_by option').click(function(){
		var order = $(this).val();
		var url = window.location.href;
		var tmp = url.split('?');
		var newUrl = tmp[0] + '?';
		if(tmp[1] != undefined && tmp[1] !== null && tmp[1] != ''){
			var tmps = tmp[1].split('&');
			for(var i = 0; i < tmps.length;i++){
				var t = tmps[i].split('=');
				if(t[0] != 'order'){
					newUrl += tmps[i] + '&';
				}
			}
		}
		newUrl += 'order=' + order;
		window.location.href = newUrl;
	});
	
	$('.select_by_part option').click(function(){
		var id = $(this).val();
		var url = $domain + '/index.html';
		if(id > -1){
			url += '?id=' + id;
		}
		window.location.href= url;
	});
	$('.thumbnail_delete').click(function(){
		$('.thumbnail_delete_input').val(1);
		$(this).prev().remove();
		$(this).remove();
	});
});