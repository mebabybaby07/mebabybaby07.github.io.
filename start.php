 <?php //起始頁面?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>"歡迎來到新人婚禮準備溝通"</title>
<style>
   body{
	   background-image:url(1.jpg);
	   background-repeat:no-repeat;
	   background-position:right top;
	   background-size:cover;
   }
</style>
</head>
<body  text="#ff69b4"> 
    <h2>歡迎您的到來，是否有來過呢？</h2>
    <input type="button" value="第一次使用" style="width:120px;height:40px; border:2px #7fffd4 double;background-color:#e0ffff;" onclick="location.href= ('http://localhost:8080/meeting/usregistration.php')"/>
    </br>
    </br>
	<input type="button" value="已使用過"   style="width:120px;height:40px; border:2px #ffc0cb double; background-color:#ffe4e1;" onclick="location.href= ('http://localhost:8080/meeting/userlogin.php')">
</body>