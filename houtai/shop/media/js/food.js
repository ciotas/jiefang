function clearbox(){
	document.getElementById("foodid").value="";
 	document.getElementById("foodname").value="";
 	document.getElementById("foodcode").value="";
 	document.getElementById("foodprice").value="";
 	document.getElementById("orderunit").value="";
 	document.getElementById("foodunit").value="";
 	document.getElementById("foodcooktype").value="";
 	document.getElementById("sortno").value="";
 	putSelectval("0","zoneid");
 	putSelectval("0","ftid");
 	putRadio("fooddisaccount","0");
 	
}
var xmlHttp
function getOneFood(foodid){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/getonefood.php"
	url=url+"?foodid="+foodid
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=stateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
 	onefood=xmlHttp.responseText
 	onefood1=eval("("+onefood+")");
 	document.getElementById("foodid").value=onefood1.foodid;
 	document.getElementById("foodname").value=onefood1.foodname;
 	document.getElementById("foodcode").value=onefood1.foodcode;
 	document.getElementById("foodprice").value=onefood1.foodprice;
 	document.getElementById("orderunit").value=onefood1.orderunit;
 	document.getElementById("foodunit").value=onefood1.foodunit;
 	document.getElementById("foodcooktype").value=onefood1.foodcooktype;
 	document.getElementById("sortno").value=onefood1.sortno;
 	
 	putSelectval(onefood1.zoneid,"zoneid");
 	putSelectval(onefood1.ftid,"ftid");
 	putRadio("fooddisaccount",onefood1.fooddisaccount);
 	putRadio("ispack",onefood1.ispack);
 }
}
function putSelectval(val,id){
	  var sel=document.getElementById(id);
	  for(var i=0;i<sel.options.length;i++)
	  {
	  	if(sel.options[i].value==val)
	  	{
	  	sel.options[i].selected=true;
	  	break;
	  	}
	  }
	}
	
function putRadio(radioid,val){
	var type = document.getElementsByName(radioid);
	for(var i = 0; i < type.length;  i++){
		  if(type[i].value == val){
			  type[i].checked =  'checked';
			  break ;
		  }
	  } 
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
catch (e)
 {
 // Internet Explorer
 try
  {
  xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  }
 catch (e)
  {
  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
 }
return xmlHttp;
}