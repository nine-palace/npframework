/**
 * Created with JetBrains WebStorm.
 * User: temple
 * Date: 13-6-24
 * Time: 下午3:03
 * To change this template use File | Settings | File Templates.
 */
var np = {
    page:function(count, current, num){
        var url = window.href.split('?');
        var newUrl = url[0] + '?';
        if(url[1] != undefined && url[1] != '' && url[1] != null){
            var params = url[1].split('&');
            for(var i=0;i<params.length;i++){
                var tmp =  params[i].split('=');
                if(tmp[0] != undefined && tmp[0] != '' && tmp[0] != null && tmp[0] != 'page'){
                    var t = tmp[1] == undefined || tmp[1] == null ? '' : tmp[1];
                    newUrl += tmp[0] + '=' + tmp[1] + '&';
                }
            }
        }
        newUrl += 'page=';
        var prev = current - 1;
        var next = current + 1;
        var pages = count / num;
        if((count % num) != 0){
            pages++;
        }

        var div = '<div class="pagging"><div class="left">Showing 1-12 of 44</div>' +
                    '<div class="right">';
        if(prev >= 1){
            div += '<a href="' + newUrl + prev + '">Previous</a>';
        }
        if(pages <= 5){
            for(var i = 1;i <= pages; i++){
                if(i == current){
                    div += '<span>' + i + '</span>';
                }else{
                    div += '<a href="' + newUrl + i + '">' + i + '</a>';
                }
            }
        }else{
            if(current < 4){
                for(var i = 1;i <= 4; i++){
                    if(i == current){
                        div += '<span>' + i + '</span>';
                    }else{
                        div += '<a href="' + newUrl + i +'">' + i + '</a>';
                    }
                }
                div += '<span>...</span>';
                div += '<a href="' + newUrl + pages + '">' + pages + '</a>';
            }else if(current > pages - 3){
                div += '<a href="' + newUrl + '1">1</a>';
                div += '<span>...</span>';
                for(var i = pages - 3; i <= pages; i++){
                    if(i == current){
                        div += '<span>' + i +'</span>'
                    }
                }
            }
        }
                    '</div>' +
                    '</div>';
    }
};