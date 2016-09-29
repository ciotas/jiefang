 onload=function(){
	document.getElementById("btnsave").disabled=true;
}
var xmlHttp

function checkphone(phone)
{
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var val= validatemobile(phone);
	if(val){
		var url="./interface/judgephone.php"
		url=url+"?phone="+phone
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=stateChanged 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
} 


function checkreversephone(phone,shopid)
{
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var val= validatemobile(phone);
	if(val){
		var url="./interface/judgevip.php"
		url=url+"?phone="+phone
		url=url+"&shopid="+shopid
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=stateChanged1 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
} 

function checkUserphone(val){
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
		  return
	  } 
	var url="./interface/judgecard.php"
	url=url+"?phone="+val
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=cardnostateChanged
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function cardnostateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
   data=xmlHttp.responseText;
   data1=eval("("+data+")");
   if(data1['status']=="ok"){
	   document.getElementById("cardnotip").innerHTML="卡内余额为 ￥"+data1.accountbalance;
	   document.getElementById("btnsave").disabled=false;
   }else{
	   document.getElementById("cardnotip").innerHTML="此卡号未注册，请前往<a href='./vipreg.php'>注册</a>！";
	   document.getElementById("btnsave").disabled=true;
   }
 } 
}

function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
   isreg=xmlHttp.responseText 
   if(isreg){
	   document.getElementById("phonetip").innerHTML="";
	   document.getElementById("btnsave").disabled=false;
   }else{
	   document.getElementById("phonetip").innerHTML="此号码未注册，请前往<a href='./vipreg.php'>注册</a>！";
	   document.getElementById("btnsave").disabled=true;
   }
 } 
}

function stateChanged1() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
   isreg=xmlHttp.responseText 
   if(isreg){
	   document.getElementById("phonetip").innerHTML="此号码已注册，无需重复注册！";
	   document.getElementById("btnsave").disabled=true;
   }else{
	   document.getElementById("phonetip").innerHTML="";
	   document.getElementById("btnsave").disabled=false;
   }
 } 
}

function checkVip()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
   isreg=xmlHttp.responseText 
   if(isreg){
	   document.getElementById("phonetip").innerHTML="已赠送，无需重复赠送！";
   }else{
	   document.getElementById("cardidtip").innerHTML="";
   }
 } 
}
function checkVipPay()
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
	  document.getElementById("cardidtip").innerHTML="卡内余额：￥"+xmlHttp.responseText ;
 } 
}

function checkRealname(val){
	if(val==null || val==""){
		document.getElementById("nametip").innerHTML="姓名不能为空！";
//		document.getElementById("btnsave").disabled=true;
		return false;
	}else{
		document.getElementById("nametip").innerHTML="";
		return true;
//		document.getElementById("btnsave").disabled=false;
	}
}
function checkIDSix(val){
	if(val==null || val==""){
		document.getElementById("idsixtip").innerHTML="不能为空！";
//		document.getElementById("btnsave").disabled=true;
		return false;
	}else{
		strlength=getStrLength(val);
		if(strlength!=6){
			document.getElementById("idsixtip").innerHTML="不是六位数字";
//			document.getElementById("btnsave").disabled=true;
			return false;
		}else{
			document.getElementById("idsixtip").innerHTML="";
//			document.getElementById("btnsave").disabled=false;
			return true;
		}
	}
}

function getStrLength(str) {  
    var cArr = str.match(/[^\x00-\xff]/ig);  
    return str.length + (cArr == null ? 0 : cArr.length);  
}  

function checkBtnsave(val){
	realname=document.getElementById("realname").value;
	cardno=document.getElementById("cardno").value;
	if(checkRealname(realname) && checkIDSix(cardno) ){
		document.getElementById("btnsave").disabled=false;
	}else{
		document.getElementById("btnsave").disabled=true;
	}
}

function checktype(val){
	if(val=="0"|| val==null){
		document.getElementById("cardidtip").innerHTML="请选择类型！";
	}else{
		document.getElementById("cardidtip").innerHTML="";
	}
}
function checksendtype(val){
	if(val=="0"|| val==null){
		 document.getElementById("cardidtip").innerHTML="请选择类型！";
	}else{
		document.getElementById("cardidtip").innerHTML="";
		phone=document.getElementById("userphone").value;
		var url="./interface/issendvip.php"
		url=url+"?phone="+phone
		url=url+"&cardid="+val
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=checkVip 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
}
function checkvipaytype(val){
	if(val=="0"|| val==null){
		 document.getElementById("cardidtip").innerHTML="请选择类型！";
	}else{
		document.getElementById("cardidtip").innerHTML="";
		phone=document.getElementById("userphone").value;
		var url="./interface/getvipmoney.php"
		url=url+"?phone="+phone
		url=url+"&cardid="+val
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=checkVipPay 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
}
function checkcharge(val){
	if(val.length==0){
		document.getElementById("chargemoneytip").innerHTML="请输入充值数字金额！";
	}else{
		if(validate(val)){
			document.getElementById("chargemoneytip").innerHTML="";
		}else{
			document.getElementById("chargemoneytip").innerHTML="请输入数字金额！";
			
		}
		
	}
}

function checkvippaymoney(val){
	if(val.length==0){
		document.getElementById("vippaymoneytip").innerHTML="请输入充值数字金额！";
	}else{
		if(validate(val)){
			document.getElementById("vippaymoneytip").innerHTML="";
		}else{
			document.getElementById("vippaymoneytip").innerHTML="请输入数字金额！";
			
		}
		
	}
}

function validate(val){
  var reg = new RegExp("^[0-9]*$");
	if(!reg.test(val)){
		return false;
	}else{
		return true;
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

//验证手机号码
function validatemobile(mobile)
{
if(mobile.length==0)
{
   document.getElementById("phonetip").innerHTML="请输入手机号码！";
   document.form1.mobile.focus();
   return false;
}    
if(mobile.length!=11)
{
    //alert('请输入有效的手机号码！');
    document.getElementById("phonetip").innerHTML="请输入有效的手机号码！";
    document.form1.userphone.focus();
    return false;
}

var myreg = /^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/;
if(!myreg.test(mobile))
{
	 document.getElementById("phonetip").innerHTML="请输入有效的手机号码！";
    document.form1.userphone.focus();
    return false;
}
return true;
}

function showHint()
{
	var mobile=document.getElementById("userphone").value;
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/sendcusmsg.php"
	url=url+"?phone="+mobile
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=msgstateChanged 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
} 

function msgstateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	 { 
		var status=xmlHttp.responseText;
		alert("验证码发送成功，请注意接收！");
	}
}