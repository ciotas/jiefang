
//验证手机号码
function validatemobile()
{
	var mobile=document.getElementById("telphone").value;
    if(mobile.length==0)
    {
       alert('请输入手机号码！');
       document.form1.mobile.focus();
       return false;
    }    
    if(mobile.length!=11)
    {
        alert('请输入有效的手机号码！');
        document.form1.mobile.focus();
        return false;
    }
    
    var myreg = /^(13[0-9]|14[0-9]|15[0-9]|17[0-9]|18[0-9])\d{8}$/;
    if(!myreg.test(mobile))
    {
        alert('请输入有效的手机号码！');
        document.form1.mobile.focus();
        return false;
    }
    xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	  {
	  alert ("Browser does not support HTTP Request")
	  return
	  } 
	var url="./interface/phoneuse.php"
	url=url+"?phone="+mobile
	url=url+"&sid="+Math.random()
	xmlHttp.onreadystatechange=PhoneUse 
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
    return true;
}
function checkEmail(str){
	shopemail=document.getElementById("shopemail").value;
   var re = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
   if(re.test(shopemail)){
      return true;
   }else{
	   alert("支付宝邮箱格式错误！");
	   return false;
   }
}
function checkphonecode(){
	code=document.getElementById("phonecode").value;
	if(code=="" || code ==null){
		alert("请输入验证码！");
		return false;
	}else{
		return true;
	}
}
function checkpwd1(){
	pwd1=document.getElementById("pwd1").value;
	if(pwd1=="" || pwd1 ==null){
		alert("请输入密码！");
		return false;
	}else{
		return true;
	}
}
function checkpwd2(){
	pwd1=document.getElementById("pwd1").value;
	pwd2=document.getElementById("pwd2").value;
	if(pwd2=="" || pwd2 ==null){
		alert("请输入确认密码！");
		return false;
	}else if(pwd1!=pwd2){
		alert("密码输入不一致！");
		return false;
	}else{
		return true;
	}
}

function checkbossname(){
	bossname=document.getElementById("bossname").value;
	if(bossname=="" || bossname ==null){
		alert("请输入总店名！");
		return false;
	}else{
		return true;
	}
}
function checkremark(){
	remark=document.getElementById("remark").value;
	if(remark=="" || remark ==null){
		alert("请输入备注名！");
		return false;
	}else{
		return true;
	}
}


function checkfrom(){
	if(validatemobile() && checkpwd1() && checkpwd2() && checkphonecode()&&checkbossname()){
		return true;
	}else{
		return false;
	}
	
}

function checkaddsubform(){
	if(validatemobile()&&checkremark()&&checkphonecode()){
		return true;
	}else{
		return false;
	}
}

var xmlHttp
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
 //Internet Explorer
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

function showHint()
{
	var val= validatemobile();
	if(val){
		var mobile=document.getElementById("telphone").value;
		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		  {
		  alert ("Browser does not support HTTP Request")
		  return
		  } 
		var url="./getdesphone.php"
		url=url+"?phone="+mobile
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=stateChanged 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
	}
} 
function emailHint()
{
	var val= checkEmail();
	if(val){
		var shopemail=document.getElementById("shopemail").value;
		xmlHttp=GetXmlHttpObject()
		if (xmlHttp==null)
		  {
		  alert ("Browser does not support HTTP Request")
		  return
		  } 
		var url="./getemailcode.php"
		url=url+"?shopemail="+shopemail
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
		var status=xmlHttp.responseText;
		alert("验证码发送成功，请注意接收！");
	}
}
function PhoneUse() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	 { 
		var status=xmlHttp.responseText;
		if(status){
			
		}else{
			alert("此号码已注册，请直接登录！");
			window.location.href='./login.php';
		}
		
	}
}
