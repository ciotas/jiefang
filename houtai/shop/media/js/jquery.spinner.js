function addtocart(op,foodid,foodname,foodprice,foodcooktype,isweight,ispack){
	num=0;
	if(isweight=="0"){
		if(exist("text_box_"+foodid)){
			num=document.getElementById("text_box_"+foodid).value;
		}
	}
	
	 foodid=document.getElementById(foodid).value;
	 if(foodcooktype!="" && foodcooktype!=null && ispack=="0"){
		 cooktypestr=getSelectedCook(foodid,foodcooktype);
	 }else{
		 cooktypestr="";
	 }
	 onefood={};
	 onefood.foodid=foodid;
	 onefood.cooktype=cooktypestr;
	 onefood.isweight=isweight;
	 onefood.ispack=ispack;
	 onefood.foodname=foodname;
	 onefood.foodcooktype=foodcooktype;
	 onefood.foodprice=foodprice;
	 food={}
	if(op=="add"){
		foodamount=parseInt(num)+1;
		if(isweight=="0"){
			if(exist("text_box_"+foodid)){
				document.getElementById("text_box_"+foodid).value=foodamount;
			}
		}
		
		if(foodcooktype!=""&&foodcooktype!=null && isweight=="0" || ispack=="1"){
			if(exist("text_box_vice_"+foodid)){
				document.getElementById("text_box_vice_"+foodid).value=foodamount;
			}
		}
		//加入购物车
		 onefood.foodnum=foodamount;
		 onefood.foodamount=foodamount;
		 if(sessionStorage.food){
			 food=sessionStorage.getItem("food");			
			 food=JSON.parse(food)
			 if(food[foodid]!=null &&food[foodid]!="" ){
				 food[foodid]['foodnum']+=1;
				 food[foodid]['foodamount']+=1;
			 }else{
				 food[foodid]=onefood;
			 }
			 console.log(food);
			 sessionStorage.setItem("food",JSON.stringify(food))
		 }else{			
			 food[foodid]=onefood;
			console.log(JSON.stringify(food));
			 sessionStorage.setItem("food",JSON.stringify(food))
		 }
		 isShopEmpty();
	}else if(op=="minus"){
		console.log(num)
		foodamount=parseInt(num)-1;
		if(num>0){
			if(isweight=="0"){
				if(exist("text_box_"+foodid)){
					document.getElementById("text_box_"+foodid).value=foodamount;
				}
			}
			
			if(foodcooktype!="" && foodcooktype!=null && isweight=="0"  || ispack=="1"){
				if("text_box_vice_"+foodid){
					document.getElementById("text_box_vice_"+foodid).value=foodamount;
				}
			}
			 onefood.foodnum=foodamount;
			 onefood.foodamount=foodamount;
			 //购物车减少
			 if(sessionStorage.food){
				 food=sessionStorage.getItem("food");			
				 food=JSON.parse(food)
				 if(food[foodid]!=null &&food[foodid]!="" ){
					 food[foodid]['foodnum']-=1;
					 food[foodid]['foodamount']-=1;
				 }else{
					 food[foodid]=onefood;
				 }
				 console.log(food);
				 sessionStorage.setItem("food",JSON.stringify(food))
				 
			 }else{			
				 food[foodid]=onefood;
				console.log(JSON.stringify(food));
				 sessionStorage.setItem("food",JSON.stringify(food))
				 
			 }
		}else{//清除购物车
			 if(sessionStorage.food){
				 food=sessionStorage.getItem("food");			
				 food=JSON.parse(food)
				 delete food[foodid];
				 
			 }
		}
		isShopEmpty();
	}else if(op=="cook"){
		if(sessionStorage.food){
			 food=sessionStorage.getItem("food");			
			 food=JSON.parse(food)
			 food[foodid]['cooktype']=cooktypestr;
			
			 console.log(food);
			 sessionStorage.setItem("food",JSON.stringify(food))
		 }else{			
			 food[foodid]=onefood;
			console.log(JSON.stringify(food));
			 sessionStorage.setItem("food",JSON.stringify(food))
		 }
	}else if(op=="weight"){
		foodamount=document.getElementById("weight_"+foodid).value;
		if(foodamount==null || foodamount==""){foodamount=0;}
		 onefood.foodnum=1;
		 onefood.foodamount=foodamount;
		if(sessionStorage.food){
			 food=sessionStorage.getItem("food");			
			 food=JSON.parse(food)
			 if(food[foodid]!=null &&food[foodid]!="" ){
				 food[foodid]['cooktype']=cooktypestr;
				 food[foodid]['foodamount']=foodamount;
				 food[foodid]['foodnum']=1;
			 }else{
				 food[foodid]=onefood;
			 }
			 console.log(food)
			 sessionStorage.setItem("food",JSON.stringify(food))
		 }else{			
			 food[foodid]=onefood;
			console.log(JSON.stringify(food));
			 sessionStorage.setItem("food",JSON.stringify(food))
		 }
		if(exist("text_box_"+foodid)){
			document.getElementById("text_box_"+foodid).value=foodamount;
		}
		isShopEmpty();
		
	}
	getMyMenu();
}
//页面初始化赋值
function initData(){
	if(sessionStorage.food){
		 food=sessionStorage.getItem("food");			
		 food=JSON.parse(food);
		 for(var foodid in food){
			 console.log(food[foodid])
			 if(exist("text_box_"+foodid)){
				 document.getElementById("text_box_"+foodid).value=food[foodid]["foodamount"];
			 }
			
			 if(food[foodid]["cooktype"]!="" && food[foodid]["ispack"]=="0" && food[foodid]["isweight"]=="0"  || food[foodid]["ispack"]=="1"){
				 if(exist("text_box_vice_"+foodid)){
					 document.getElementById("text_box_vice_"+foodid).value=food[foodid]["foodamount"];
				 }
			 }
			 cooktypearr=food[foodid]["cooktype"].split('、');
			
			 for (var i in cooktypearr){
				 if(food[foodid]["cooktype"]!=""){
					 if(exist(foodid+"_"+cooktypearr[i])){
						 document.getElementById(foodid+"_"+cooktypearr[i]).className="btn blue";
						 document.getElementById(foodid+"_"+cooktypearr[i]).style.color="red";
					 }
				 }
			 }
			 if(food[foodid]["isweight"]=="1"&&food[foodid]["ispack"]=="0" ){
				 if(exist("weight_"+foodid)){
					 document.getElementById("weight_"+foodid).value=food[foodid]["foodamount"];
				 }
			 }
		 }

	}
	isShopEmpty();
	getMyMenu();
}
function getSelectedCook(foodid,foodcooktype){
	var arr=new Array();
	var str="";
	if(foodcooktype.length>0){
		cooktypearr=foodcooktype.split('、');
		for (var i in cooktypearr){
			if(!exist(""+foodid+"_"+cooktypearr[i]+"")){continue;}
			btnclass=document.getElementById(""+foodid+"_"+cooktypearr[i]+"").className
			if(btnclass!="btn"){
				arr.push(cooktypearr[i])
			}
		}
	}
	
	if(arr.length !=0){
		str=arr.join("、");
	}
	return  str;
}
function isShopEmpty(){
	if(sessionStorage.food){
		 food=sessionStorage.getItem("food");			
		 food=JSON.parse(food);
		 shopfoodnum=0;
		 for(var foodid in food){
			 if(food[foodid]["foodamount"]>0){
				 shopfoodnum++;
			 }
		 }
		 if(shopfoodnum>0){
			 document.getElementById("shopedcart").style.display="block";
		 }else{
			 document.getElementById("shopedcart").style.display="none";
		 }
	}else{
		 document.getElementById("shopedcart").style.display="none";
	}
}

//选择做法
function SelectCook(foodid,onecook){
	btnclass=document.getElementById(""+foodid+"_"+onecook+"").className;
	if(btnclass=="btn"){
		document.getElementById(""+foodid+"_"+onecook+"").className="btn blue";
		document.getElementById(""+foodid+"_"+onecook+"").style.color="red";
	}else{
		document.getElementById(""+foodid+"_"+onecook+"").className="btn";
		document.getElementById(""+foodid+"_"+onecook+"").style.color="black";
	}
}
function getMyMenu(){
	totalmoney=0;
	if(sessionStorage.food){
		 food=sessionStorage.getItem("food");
		 food=JSON.parse(food);
		 menu='';
		 menu+='<table class="table table-hover">';
		 menu+='<tbody>';
		 menu+='<tr><td colspan="3">人数：';
		 menu+=' <button class="btn purple icn-only" onclick="changeCusnum(\'minus\')" id="cusnum" style="height:30px;width:50px;margin:0;padding:0;"><i class=" icon-minus"></i></button>';
		 menu+='<input id="text_box_cusnum"  disabled  type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center"/>';
		 menu+='<button  class="btn purple icn-only"onclick="changeCusnum(\'add\')"  style="height:30px;width:50px;margin:0;padding:0;"/><i class=" icon-plus"></i></button>';
		menu+='</td><td style="color:red;font-weight:600">总额：<span id="totalmoney"></span></td></tr>';	
		 for(var foodid in food){
			 if(food[foodid]["foodamount"]=="0"){continue;}
			 menu+='<tr>';
			 menu+='<td>'+food[foodid]["foodname"]+'</td>';
			 menu+='<td style="color:#FF8C69;">'+food[foodid]["foodprice"]+'</td>';
			 menu+='<td style="color:#FF8C69">'+food[foodid]["cooktype"]+'</td>';
			 if(food[foodid]["isweight"]=="0"){
				 menu+='<td>';
				 menu+='<button class="btn red icn-only"  onclick="changecartnum(\'minus\',\''+food[foodid]["foodid"]+'\',\''+food[foodid]["isweight"]+'\',\''+food[foodid]["ispack"]+'\')"  style="height:25px;width:35px;margin:0;padding:0;"/><i class=" icon-minus"></i></button>';
				 menu+='<input id="text_box_cart_'+food[foodid]["foodid"]+'"  disabled name="" type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center"/>';
				 menu+='<button  class="btn red icn-only"   onclick="changecartnum(\'add\',\''+food[foodid]["foodid"]+'\',\''+food[foodid]["isweight"]+'\',\''+food[foodid]["ispack"]+'\')"  style="height:25px;width:35px;margin:0;padding:0;"/><i class=" icon-plus"></i></button>';
				 menu+= '</td>';
			 }else{
				 menu+= '<td>';
				 menu+='<button style="height:25px;width:35px;margin:0;padding:0;visibility:hidden;"/></button>';
				 menu+= '<input id="text_box_cart_'+food[foodid]["foodid"]+'"  disabled name="" type="text" value="0"  style="width:25px;height:25px;margin:0;padding:0;text-align:center"/></td>';
				 menu+= '</td>' ;
			 }
			 menu+='</tr>';
			 totalmoney+=food[foodid]["foodamount"]*food[foodid]["foodprice"];
		 }
		 menu+='</tbody>';
		 menu+='</table>';
		 document.getElementById("mymenu").innerHTML=menu;
		 document.getElementById("totalmoney").innerHTML="￥"+totalmoney;
		 for(var foodid in food){
			 if(food[foodid]["foodamount"]=="0"){continue;}
			 document.getElementById("text_box_cart_"+food[foodid]["foodid"]).value=food[foodid]["foodamount"];
		 }
		 if(sessionStorage.cusnum>0){
			 document.getElementById("text_box_cusnum").value=sessionStorage.cusnum;
		}else{
			 document.getElementById("text_box_cusnum").value=0;
		}
	}	 
	
}
function changecartnum(op,foodid,isweight,ispack){
	 food=sessionStorage.getItem("food");
	 food=JSON.parse(food);
	 if(op=="add"){
		 if(isweight=="0"){
			 newfoodnum=food[foodid]['foodnum']+1;
			 newfoodamount=food[foodid]['foodamount']+1;
		 }
		 
	 }else if(op=="minus"){
		 newfoodnum=food[foodid]['foodnum']
		 if(isweight=="0"){
			 if(food[foodid]['foodamount']>0){
				 newfoodamount=food[foodid]['foodamount']-1;
			 }
		 }
	 }
	 
	 food[foodid]['foodnum']=newfoodnum;
	 food[foodid]['foodamount']=newfoodamount;
	 sessionStorage.setItem("food",JSON.stringify(food));
	 if(exist("text_box_"+foodid)){
			document.getElementById("text_box_"+foodid).value=newfoodamount;
	 }
	 getMyMenu();
}
function changeCusnum(op){
	cusnum=document.getElementById("text_box_cusnum").value;
	cusnum=parseInt(cusnum);
	newcusnum=0;
	if(op=="add"){
		newcusnum=cusnum+1;
		document.getElementById("text_box_cusnum").value=newcusnum;
		sessionStorage.cusnum=newcusnum;
	}else if(op=="minus"){
		if(cusnum>1){
			newcusnum=cusnum-1;
			document.getElementById("text_box_cusnum").value=newcusnum;
			sessionStorage.cusnum=newcusnum;
		}else{
			alert("人数不能为0");
		}
	}
}
function CalcTotalmoney(){
	if(sessionStorage.food){
		 food=sessionStorage.getItem("food");
		 food=JSON.parse(food);
		 for(var foodid in food){
			 totalmoney+=food[foodid]["foodamount"]*food[foodid]["foodprice"];
		 }
		 document.getElementById("totalmoney").innerHTML="￥"+totalmoney;
	}
}
function downSheet(){	
	 cusnum=sessionStorage.cusnum;
	 food=sessionStorage.getItem("food");
	 food=JSON.parse(food);
	 foodnum=0;
	 for(var i in food){
		 if(food[i]['foodamount']>0){
			 foodnum++;
		 }
	 }
	if(cusnum>0){
		if(foodnum==0){alert("请点餐后再提交！");return false;}
		document.getElementById("form_cusnum").value=cusnum;
		document.getElementById("form_food").value=sessionStorage.food;
		return confirm('确认下单给服务员？');
	}else{
		alert("人数不能为 0");
		return false;
	}
}
function exist(id){
    var s=document.getElementById(id);
      if(s){ return true; }else{return false; }
}
//是否存在指定变量 
function isExitsVariable(variableName) {
    try {
        if (typeof(variableName) == "undefined") {
            //alert("value is undefined"); 
            return false;
        } else {
            //alert("value is true"); 
            return true;
        }
    } catch(e) {}
    return false;
}