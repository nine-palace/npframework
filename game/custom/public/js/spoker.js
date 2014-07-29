var spoker = {
    /**
     * Created with JetBrains WebStorm.
     * User: temple
     * Date: 13-5-15
     * Time: 上午10:07
     * To change this template use File | Settings | File Templates.
     */
    start:function(){

    },
    showCards:function(cards, obj){
        var dom = '';
         for(var i in cards){
             dom = '<div class="spoker ' + cards[i].numberClass + ' ' + cards[i].flowerClass + '">';
             dom += '<div class="number"></div><div class="flower"></div></div>';
             obj.appendChild(dom);
         }
    }
}
