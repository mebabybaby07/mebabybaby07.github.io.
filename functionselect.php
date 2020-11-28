<?php //使用者使用功能選擇?>
<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8"/>
<title>"功能選擇"</title>
<style>
   body{
	   background-image:url(12.jpg);	  
	   background-repeat:no-repeat;
	   background-position:center top;
	   background-size:cover;
   }
</style>
</head>
<body bgcolor="#e6e6fa" text="#ffa500">
    <?php
	    session_start();
		$userphone=$_SESSION["userphone"];
		$link =@mysqli_connect('localhost','root','1234','meeting');
        mysqli_query($link,'SET NAMES utf8');
		$sq="SELECT * FROM user WHERE phone=$userphone";
		$resul = mysqli_query($link,$sq);
		$ro=mysqli_fetch_assoc($resul);		
	?>
    <h2><?php echo $ro["name"].","; ?>歡迎再次回來~請選擇您要執行的功能!</h2>    
	<?php  
	    //判斷兩人都有選擇個人項目才出現此按鈕 	    	
		$sql = "SELECT pair FROM user WHERE phone=$userphone";  //找到使用者組數
        $result = mysqli_query($link,$sql);
		$row=mysqli_fetch_row($result);
		//若使用者有組數
        if($row[0]!=null){		
	       $_SESSION["pair"]=$row[0];

           echo "<font face='DFKai-sb'color='#ff69b4' size='5'>雙人模式</font></br>";		   
		   $sql1 = "SELECT anophone FROM user WHERE phone=$userphone";  //找到使用者另一伴電話
           $result1 = mysqli_query($link,$sql1);
	       $row1=mysqli_fetch_row($result1);
           $_SESSION["anophone"]=$row1[0];	
           $anophone=$_SESSION["anophone"];		
		   $sql2 = "SELECT pair FROM user WHERE phone=$row1[0]";  //找到使用者另一伴組數
           $result2 = mysqli_query($link,$sql2);
	       $row2=mysqli_fetch_row($result2);
		
		   if($row[0]!=null && $row2[0]!=null && $row[0]==$row2[0]){		
		      //判斷自己是否有選擇項目
		      $sql3 = "SELECT A,B,C,D,E,F,G,H,I,J FROM user WHERE phone='$userphone'";
              $result3 = mysqli_query($link,$sql3);
		      $row3=mysqli_fetch_row($result3);
		      $itemqua=0;
		      for($i=0;$i<10;$i++){
			     if($row3[$i]!=0){ 
				    $itemqua++;
			     }
		      }
		      echo "<font face='DFKai-sb' color='#ff69b4' size='4'>";
		      echo "您目前選的項目總數:".$itemqua."    ";
		      echo "</font>";
		
		      //判斷另一伴是否有選擇項目
		      $sql4 = "SELECT A,B,C,D,E,F,G,H,I,J FROM user WHERE anophone=$userphone";
		      $result4 = mysqli_query($link,$sql4);
		      $row4=mysqli_fetch_row($result4);
		      $itemquaa=0;
		      for($i=0;$i<10;$i++){
			     if($row4[$i]!=0){
			        $itemquaa++;
			     }
		      }
		      echo "<font face='DFKai-sb' color='#ff69b4' size='4'>";
		      echo "另一伴選的總數:".$itemquaa;
		
		      if($itemqua>0 && $itemquaa>0){
				  //進入雙人作答頁面?>			  
			      <input type="submit" name="twoitemselect" value="作答雙人填寫項目" style="width:120px;height:25px; border:2px #dda0dd double; background-color:#ffe4e1;" onclick="location.href= ('http://localhost:8080/meeting/showquestion.php')"/> <?php
		      }else{
		          ?><br>
		          要兩人都填選才能進行兩人的題目填寫喔~~~</font></br><?php	
		      }		
	       }
		   //判斷雙方是否已經做過問卷
		   $sql5 = "SELECT A,B,C,D,E,F,G,H,I,J FROM twoanswer WHERE phone='$userphone'";
           $result5 = mysqli_query($link,$sql5);
		   $row5=mysqli_fetch_row($result5);
		   $ans=0;
		   for($i=0;$i<10;$i++){
			   if($row5[$i]!=0){
			      $ans++;
			   }
		   }
		   $sql6 = "SELECT A,B,C,D,E,F,G,H,I,J FROM twoanswer WHERE phone='$anophone'";
           $result6 = mysqli_query($link,$sql6);
		   $row6=mysqli_fetch_row($result6);
		   $anss=0;
		   for($i=0;$i<10;$i++){
			   if($row6[$i]!=0){
			      $anss++;
			   }
		   }
		   if($ans>0&&$anss>0){?>
			   <input type="submit" name="twoana" value="查看雙人分析結果" style="width:120px;height:25px; border:2px #dda0dd double; background-color:#ffe4e1;" onclick="location.href= ('http://localhost:8080/meeting/twoanalysis.php')"/>  <?php //進入雙人分析結果頁面?>	
		       </br></br><?php
		   }
		}

		//顯示使用者目前選擇的項目
		//顯示user資料表ABC那些欄位為1
		//顯示的同時，抓itemnum表的數值是多少
		$itemsel=0;
		$sql3 = "SELECT A,B,C,D,E,F,G,H,I,J FROM user WHERE phone='$userphone'";
        $result3 = mysqli_query($link,$sql3);
		$row3=mysqli_fetch_row($result3);
		$i=0;
		echo "</br>";
		echo "<font face='DFKai-sb' color='#1e90ff' size='5'>單人模式:</font></br>";
		while($meta=mysqli_fetch_field($result3)){    //一次顯示一個欄位名

			if($row3[$i]!=0){
			   //echo $meta->name."+";
			   $sq="SELECT itemname FROM itemnum WHERE itemnum=$i+1";
		       $resul = mysqli_query($link,$sq);
               $ro=mysqli_fetch_row($resul);
			   echo "<font face='DFKai-sb' color='#1e90ff' size='4'>";
		       echo $ro[0].",";
			   $i++;
			   $itemsel++;
			}else{$i++;}
		}
		if($itemsel!=0){
		    echo "</br>為您目前所選擇的分析項目";
		    echo "</font>";
		}else{
			echo "</br><font face='DFKai-sb' color='#1e90ff' size='4'>";
			echo "您目前沒有選擇的分析項目喔~~";
			echo "</font>";
		}
		?>	
	    <input type="submit" name="itemselect" value="編輯個人填寫項目" style="width:120px;height:25px; border:2px #add8e6 double; background-color:#e0ffff;" onclick="location.href= ('http://localhost:8080/meeting/itemselect.php')"/> 
        <?php
		//判斷使用者是否已經做過問卷
		   $sql7 = "SELECT A,B,C,D,E,F,G,H,I,J FROM answer WHERE phone='$userphone'";
           $result7 = mysqli_query($link,$sql7);
		   $row7=mysqli_fetch_row($result7);
		   $oneans=0;
		   for($i=0;$i<10;$i++){
			   if($row7[$i]!=0){
			      $oneans++;
			   }
		   }
		   
		   if($oneans>0){?>
			   <input type="submit" name="oneana" value="查看各項目分析結果" style="width:140px;height:25px; border:2px #add8e6 double; background-color:#e0ffff;" onclick="location.href= ('http://localhost:8080/meeting/oneanalysis.php')"/>  <?php //進入各項目分析結果頁面?>	
		       </br><?php
		   }
		?>
		</br></br>	
        <input type="submit" name="connect" value="編輯另一伴資料" style="width:120px;height:25px; border:2px #ffd700 double; background-color:#ffff00;" onclick="location.href= ('http://localhost:8080/meeting/anoconnect.php')"/>     
</body>