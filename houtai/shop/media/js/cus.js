
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
    return true;
}
function sendcode()
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
		var url="./interface/getdescusphone.php"
		url=url+"?phone="+mobile
		url=url+"&sid="+Math.random()
		xmlHttp.onreadystatechange=stateChanged 
		xmlHttp.open("GET",url,true)
		xmlHttp.send(null)
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

function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	 { 
		var status=xmlHttp.responseText;
		alert("验证码发送成功，请注意接收！");
	}
}