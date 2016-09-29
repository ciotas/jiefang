
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
			html+='<input type="hidden" name="foodintro" id="foodintro_'+lists[i][key][j].id+'" value="'+lists[i][key][j].foodintro+'">';
			if(lists[i][key][j].isdis){
				html+='<div class="dis"><a href="javascript:;" class="hunanese " index="'+lists[i][key][j].id+'">口味</a></div>';
			}else{
//				html+='<div class="dis"><a href="javascript:;" class="hunanese isfalse" index="'+lists[i][key][j].id+'">口味</a></div>';
			}
			if(lists[i][key][j].price == 0){
				html += '</li>';
			}else{
				html+='<div class="totle"></div>'+
				'<div class="add foodAdd">'+
				'<span class="minus food-minus"></span>'+
				'<span class="number">0</span>'+
				'<span class="minus plus"></span>'+
				'</div>'+
				'</li>';				
			}

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




//sessionStorage.setItem("dis","");
/*口味*/
	var disArrayList;
	var disArray;//用来储存选中的口味值
	if(sessionStorage.getItem("dis")){
        disArrayList=$.parseJSON(sessionStorage.getItem("dis"));
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
            '<input type="hidden" value="'+obj.id+'" name="dishes"/>'+
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
//                          disArrayList[i][id]["totprice"]=totprice; //增肌钱的
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
            var id=$('input[name="dishes"]').val();  //获取id
            $('#disUl li').each(function(i){
                if($(this).find('input').prop('checked')){
                    $(this).find('input').prop("checked",false)
                }
            });
            for(var i=0;i<disArrayList.length;i++){
            	if(disArrayList[i].hasOwnProperty(id)){
            		disArrayList[i][id]["checked"]=[];
            		disArrayList.splice(i,1); //删除该对象
            		var str=JSON.stringify(disArrayList);
            		sessionStorage.setItem('dis',str);
					break;
				}
            }
            $('.lists li[data-type="'+id+'"]').find(".hunanese").removeClass("istrue");
			$('#disAlert').hide();
			

            var  foodList=$.parseJSON(sessionStorage.getItem("key"))  //菜的缓存  改

            var disArrayListNew=$.parseJSON(sessionStorage.getItem("dis"));  //口味的缓存 改

            ok(disArrayListNew,foodList);  //改
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
        $("#selected").on('click',function(){
//            var id=$('input[type="hidden"]').val();  //获取id
            var id=$('input[name="dishes"]').val();  //获取id
            var obj={};
            var disArray=[];
            
            var totprice=0; //改
            
            $('#disUl li').each(function(i){
               if($(this).find('input').prop('checked')){
                    var val=$(this).find('input').val(); 
                    

                    if(val.indexOf('_')==-1){  //改
                        totprice+=0;      //改
                    }else{   //改

                        var str=val.split('_')[1];
                        var mystr=str.substring(0,str.length-1);
                        var index=Number(mystr);
                        totprice+=index;     //改

//                         var index=Number(val.substring(val.indexOf('_')+1));  //改
//                           console.log(index);   //改
//                         totprice+=index;     //改
                    }  //改
                    
                    disArray.push(val);
                }
            });

         

            var flag;
            if(disArrayList.length>0){
	            for(var i=0;i<disArrayList.length;i++){
	            	if(disArrayList[i].hasOwnProperty(id)){
	            		flag=true;
	            		disArrayList[i][id]["checked"]=disArray;
	            		 disArrayList[i][id]["totprice"]=totprice;  //改
						break;
					}
	            }
        	}


            if(disArray.length>0){
            	$('.lists li[data-type="'+id+'"]').find(".hunanese").addClass("istrue");
            	sessionStorage.setItem('dis',str);
            }else{
            	$('.lists li[data-type="'+id+'"]').find(".hunanese").removeClass("istrue");


            	for(var i=0;i<disArrayList.length;i++){
	            	if(disArrayList[i].hasOwnProperty(id)){
	            		disArrayList.splice(i,1); //删除该对象
	            		break;
					}
            	}

            }

            if(!flag){
            	disList["checked"]=disArray;
                obj[id]=disList;
                disList['totprice']=totprice;  //改
                disArrayList.push(obj);	
            }
			var str=JSON.stringify(disArrayList);
            sessionStorage.setItem('dis',str);
            $('#disAlert').hide();
            
            var  foodList=$.parseJSON(sessionStorage.getItem("key"))  //菜的缓存   //改

            var disArrayListNew=$.parseJSON(sessionStorage.getItem("dis"));  //口味的缓存   //改

            ok(disArrayListNew,foodList);    //改
        });

        var  foodList=$.parseJSON(sessionStorage.getItem("key"))  //菜的缓存   //改

        var disArrayListNew=$.parseJSON(sessionStorage.getItem("dis"));  //口味的缓存   //改

        ok(disArrayListNew,foodList);    //改
        /*改*/
        function ok(disArrayListNew,foodList){
           var mondyValue=0;
           var mondA=0;
           var mondB=0;
          
           if(disArrayListNew){
               if(disArrayListNew.length>0){
                   for(var i=0;i<disArrayListNew.length;i++){
                        for(var key in disArrayListNew[i]){
                           var valok=Number(disArrayListNew[i][key].totprice);
                           mondA=mondA+valok;
                        }
                   }
                   console.log(mondyValue);
               }
           }   

           if(foodList){
               if(foodList.length>0){
                   for(var i=0;i<foodList.length;i++){
                       for(var key in foodList[i]){
                           var valok=Number(foodList[i][key].foodNum*foodList[i][key].foodPrice);
                           mondB=mondB+valok;
                       }
                   }
               } 
           }
           mondyValue= mondA+mondB;
           if(mondyValue<=0){
               $(".cartMask").hide();
               $(".main-bottom").hide();
           }else{
               $(".main-bottom").show();
           }
           $(".totalPrice").text("￥"+mondyValue.toFixed(2));
       }       

        /*改 end*/
       
       
        for(var i=0;i<disArrayList.length;i++){
        	for(var key in disArrayList[i]){
        		if(disArrayList[i][key].checked.length>0){
        			$('.lists li[data-type="'+key+'"]').find(".hunanese").addClass("istrue");
        		}
        	}
        }