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


function stateChanged() 
{ 
if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
 { 
   isreg=xmlHttp.responseText 
   if(isreg){
	   document.getElementById("phonetip").innerHTML="";
   }else{
	   document.getElementById("phonetip").innerHTML="此号码未注册开饭啦app！";
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