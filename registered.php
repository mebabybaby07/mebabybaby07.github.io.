<?php //使用者註冊判斷?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>使用者註冊</title>
<style>
</style>
</head>
<body bgcolor="#ffffff"> 
<?php
	$username=$_GET["username"]; 
    $phone=$_GET["phone"];
    $gender=$_GET["gender"];
    $anophone=$_GET["anophone"];
    $password=$_GET["password"];
   
    if($username !=""  && $phone !="" && $gender !="" && $anophone !="" && $password !=""){
		$link =@mysqli_connect('localhost','root','1234','meeting');
	    mysqli_query($link,'SET NAMES utf8'); 
        
		$sql = "SELECT * FROM user WHERE phone='$phone'";
		$result = mysqli_query($link,$sql);
		$num=mysqli_num_rows($result);
		
		if($num>0){
		    ?>
			<br/><br/><br/><br/>
			<center>
			<font face="DFKai-sb" color="#ff69b4" size="4">此電話已註冊過，請至登入畫面</font>
            <form name="login" method="get" action="userlogin.php">  
                   <input type="submit" name="hasregistered" value="前往登入" style="width:80px;height:25px; border:2px #ff0000 double; background-color:#ff7f50;">   
			</form>
			<img src="777.png" align="bottom" width="250" height="200"/>	</center>.
			<img src="6.jpg" align="right" width="400" height="250"/><?php    //用戶已註冊跳至登入畫面
		}else{
			$sql1="INSERT INTO user (name,phone,gender,anophone,password) values('$username','$phone','$gender','$anophone','$password')";
			$link =@mysqli_connect('localhost','root','1234','meeting');
		    mysqli_query($link,'SET NAMES utf8');
		    mysqli_query($link,$sql1);					
			?>
			<br/><br/><br/><br/><br/><br/>
			<center> 
			<font face="DFKai-sb" color="#ff69b4" size="6">註冊成功，請重新登入~</font>
 			<form name="login" method="get" action="userlogin.php"> 
                  <input type="submit" name="registered" value="前往登入" style="width:80px;height:25px; border:2px #dda0dd double; background-color:#ffe4e1;">   
			</form>
			<br /><br /><br /><br />
			<img src="2.jpg" align="bottom" width="400" height="250"/>	</center><?php    //用戶註冊成功跳至選項畫面	
		}	
	}else{
		 echo "請確認所有空格都有填入資料喔!!";
		 ?>
		 </br>
		 <input type="submit" name="registration" value="重返註冊" style="width:80px;height:25px; border:2px #ff0000 double; background-color:#ff7f50;" onclick="location.href= ('http://localhost:8080/meeting/usregistration.php')"/>
	     <?php
	}	 	  
?>
</body>
</html>