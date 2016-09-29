
var html='';

for(var i=0;i<lists.length;i++){
	html+='<div class="lists">';
	for(var key in lists[i]){
		html+='<div class="items-top">'+key+'</div><ul>';	
		for(var j=0;j<lists[i][key].length;j++){
			html+='<li data-type="'+lists[i][key][j].id+'"><div class="shoop-pic"><img src="'+lists[i][key][j].imgUrl+'"></div>'+
			'<div class="shoop-details">'+
			'<div class="shoop-name  name">'+lists[i][key][j].name+'</div>'+
			'<p class="price pricers"><span>￥<em>'+lists[i][key][j].price+'</em></span>/<i>'+lists[i][key][j].unit+'</i></p>'+
			'</div>';
			if(lists[i][key][j].isdis){
				html+='<div class="dis"><a href="javascript:;" class="hunanese isfalse" index="'+lists[i][key][j].id+'">口味</a></div>';
			}else{
//				html+='<div class="dis"><a href="javascript:;" class="hunanese isfalse" index="'+lists[i][key][j].id+'">口味</a></div>';
			}
			html+='<div class="totle"></div>'+
			'<div class="add foodAdd">'+
			'<span class="minus food-minus"></span>'+
			'<span class="number">0</span>'+
			'<span class="minus plus"></span>'+
			'</div>'+
			'</li>';
		}
		html+='</ul>';
	}	
	html+='</div>';
}
$(".wrapper").append(html);


var menuHtml="";
for(var i=0;i<menus.length;i++){
	if(i==0){
		menuHtml+='<li class="menu-left-list active"><span>0</span>'+menus[i]+'</li>';
	}else{
		menuHtml+='<li class="menu-left-list"><span>0</span>'+menus[i]+'</li>'	
	}
}
$(".eat-title ul").append(menuHtml);




//localStorage.setItem("dis","");
/*口味*/
	var disArrayList;
	var disArray;//用来储存选中的口味值
	if(localStorage.getItem("dis")){
        disArrayList=$.parseJSON(localStorage.getItem("dis"));
    }else{
        disArrayList=[];
    }
//	console.log(disArrayList);
//    var disList={
//            id:2,
//            name:"早餐供应三鲜春卷2",
//            classify:["非冰","少冰","非豆","少豆","222"]
//        }
	
    var disList;
	function view(obj){
		$('#disAlertWrapper').html('');
        var html="";
         html+='<div class="dis-alert-top">'+
            '<h2>'+obj.name+'</h2>'+
            '<input type="hidden" value="'+obj.id+'"/>'+
            '<p>已选：<span id="disName">';
            if(obj.checked){
                for(var i=0;i<obj.checked.length;i++){
                   html+='<font>'+obj.checked[i]+'</font>';
                }
            }
            html+='</span></p></div>'+
            '<div class="dis-alert-main">'+
            '<div class="dis-name">口味</div>'+
            '<ul class="dis-ul" id="disUl">';
           w:for(var i=0;i<obj.classify.length;i++){
                if(obj.checked){
                    for(var j=0;j<obj.checked.length;j++){
                        if(obj.classify[i]==obj.checked[j]){
                          html+='<li><span><input type="checkbox" class="chk_1" checked name="dis" value="'+obj.classify[i]+'"  id="dis_'+i+'" /><label for="dis_'+i+'">'+obj.classify[i]+'</label></span></li>';
                           continue w;
                        }
                    }
                }
                html+='<li><span><input type="checkbox" class="chk_1"  name="dis" value="'+obj.classify[i]+'"  id="dis_'+i+'" /><label for="dis_'+i+'">'+obj.classify[i]+'</label></span></li>' 
            }
            html+='</ul></div>';
        $('#disAlertWrapper').append(html);
        $('#disAlert').show();
    }


	$('.hunanese').on('click',function(){
         $('#disAlertWrapper').html('');
         var id=$(this).attr('index');
//         console.log(disArrayList);
         getFoodCooks(id);
	 });
	
        function getFoodCooks(foodid){
        	for ( var p in cooktype ){
        		if(foodid==cooktype[p]['foodid']){
        			 disList={
        		        		id:foodid,
        				     	name:cooktype[p]['foodname'],
        				     	classify:cooktype[p]['foodcooktype']
        		            }
        			        id=foodid;
        			        var flag;
        			         for(var i=0;i<disArrayList.length;i++){
        			         	if(disArrayList[i].hasOwnProperty(id)){
        			         		flag=true;
        			         		disArray=disArrayList[i][id].checked;
        			                 view(disArrayList[i][id]);
        				            	break;
        			         	}
        			         }
        			         if(!flag){
        			         	view(disList);
        			         }
        		}
        	}
        }
      //不要了
        $("#no").on('click',function(){
            var id=$('input[type="hidden"]').val();  //获取id
            $('#disUl li').each(function(i){
                if($(this).find('input').prop('checked')){
                    $(this).find('input').prop("checked",false)
                }
            });
            for(var i=0;i<disArrayList.length;i++){
            	if(disArrayList[i].hasOwnProperty(id)){
            		disArrayList[i][id]["checked"]=[];
					break;
				}
            }
            //按钮变灰 hunanese isfalse
            
			$('#disAlert').hide();
        });
        
        //选择食物
        $(document).on("click",'#disUl span',function(e){
            if(e.target.tagName!="INPUT"){
                return;
            }
            var disArray=[]; 
            $("#disName").html('');
            $('#disUl li').each(function(i){
               if($(this).find('input').prop('checked')){
                    var val=$(this).find('input').val(); 
                    disArray.push(val);
                }
            });
           var html="";
            for(var i=0;i<disArray.length;i++){
                html+='<font>'+disArray[i]+'</font>'
            }  
            $("#disName").append(html); 
        })
        //点击选好了。
  //点击选好了。
        console.log(disArrayList);
        $("#selected").on('click',function(){
            var id=$('input[type="hidden"]').val();  //获取id
            var obj={};
            var disArray=[];

            $('#disUl li').each(function(i){
               if($(this).find('input').prop('checked')){
                    var val=$(this).find('input').val(); 
                    disArray.push(val);
                }
            });

            var flag;
            if(disArrayList.length>0){
	            for(var i=0;i<disArrayList.length;i++){
	            	if(disArrayList[i].hasOwnProperty(id)){
	            		flag=true;
	            		disArrayList[i][id]["checked"]=disArray;
						break;
					}
	            }
        	}
            if(!flag){
            	disList["checked"]=disArray;
                obj[id]=disList;
                disArrayList.push(obj);	
            }
			var str=JSON.stringify(disArrayList);
            localStorage.setItem('dis',str);
            $('#disAlert').hide();
        });

        
        function updateCookStatus(){
        	for(var i=0;i<disArrayList.length;i++){
            	if(disArrayList[i].hasOwnProperty(id)){
            		
				}
            }
        }