<?php
    //"修改"紐按下後
    session_start(); 
    $userphone=$_SESSION["userphone"];
    if(isset($_GET["resetano"])){
        (string)$phone=$_GET["phone"];
        if($phone !=""){
	        $link =@mysqli_connect('localhost','root','1234','meeting');
            mysqli_query($link,'SET NAMES utf8');
	  
	        $sql = "SELECT * FROM user WHERE phone='$phone'";      //查詢新另一伴是否有註冊
	        $result = mysqli_query($link,$sql);  
	        $num=mysqli_num_rows($result);
			
			//新另一伴有註冊
	        if($num>0){
	                while($row = mysqli_fetch_assoc($result)){	  
                        if($row["anophone"]==$userphone||$row["anophone"]==" "){   //另一伴的另一伴號碼為自己或空值
				            mysqli_query($link,'SET NAMES utf8');
				            $sqll="UPDATE user SET anophone='$phone' WHERE phone='$userphone'";
				            echo $sqll;
				            mysqli_query($link,$sqll);
						
				            //給予組數  
		   	                $sqlll="SELECT * FROM user WHERE pair='(SELECT MAX(pair) FROM user)'";
			                $resultt = mysqli_query($link,$sqlll);
			                while($roww = mysqli_fetch_assoc($resultt)){
				                $maxpair=$roww["pair"];
			                }
			                $setpair=$maxpair+1;
			                $sqllll="UPDATE user SET pair='$setpair' WHERE phone='$userphone'";
	                        mysqli_query($link,$sqllll);
                            $sqlllll="UPDATE user SET pair='$setpair' WHERE phone='$phone'";
	                        mysqli_query($link,$sqlllll);				
                        }else{	            
	                        echo "<center><font color='red'>";
	                        echo "此手機號碼並非與您配對";
	                        echo "</font>";?>
				            </br>
				            <img src="777.png" align="bottom" width="300" height="300"/></br>
				            <input type="submit" name="connect" value="返回" style="width:80px;height:25px; border:2px #ffb6c1 double; background-color:#ff4500;" onclick="location.href= ('http://localhost:8080/meeting/anoconnect.php')"/><?php
			            }
                    }  
	        }else{
				//新另一伴沒註冊
		        echo "<center><font color='red'>";
	            echo "此手機號碼並無註冊";
	            echo "</font>"; ?></br>
		        <img src="777.png" align="bottom" width="300" height="300"/></br>
		        <input type="submit" name="connect" value="返回" style="width:80px;height:25px; border:2px #ffb6c1 double; background-color:#ff4500;" onclick="location.href= ('http://localhost:8080/meeting/anoconnect.php')"/><?php
	        } 
	    }
    }
?>