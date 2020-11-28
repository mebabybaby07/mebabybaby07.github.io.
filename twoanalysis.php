<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>分析結果</title>
<style>
   body{
	   background-image:url(17.jpg);	  
	   background-repeat:no-repeat;
	   background-position:center top;
	   background-size:cover;
   }
</style>
</head>
<body  text="#4169e1">
<?php
    //echo "兩人分析結果";
    $link =@mysqli_connect('localhost','root','1234','meeting');
    mysqli_query($link,'SET NAMES utf8');
    session_start();
    $userphone = $_SESSION["userphone"]; 
    $anophone = $_SESSION["anophone"];

    $sql="SELECT A,B,C,D,E,F,G,H,I,J FROM twoanswer WHERE phone=$userphone";   //兩人項目優先序
    $result=mysqli_query($link,$sql);
    $row=mysqli_fetch_row($result);

    $seq=array();
    $num=0;      //項目數目
    for($i=1; $i<=10; $i++){
	    if($row[$i-1]!=0){  
		    $sql2="SELECT itemname FROM itemnum WHERE itemnum='$i'";
		    $result2=mysqli_query($link,$sql2);
		    $row2=mysqli_fetch_row($result2);
		    $seq["$row2[0]"]=$row[$i-1];
		    $num++;
	    }
    }
    arsort($seq);
    //print_r($seq);

    $sql2="SELECT * FROM user WHERE phone=$userphone";      //找出使用者性別,姓名
    $result2=mysqli_query($link,$sql2);
    $row2=mysqli_fetch_assoc($result2);
    $sql3="SELECT * FROM user WHERE phone=$anophone";      //找出另一伴性別,姓名，
    $result3=mysqli_query($link,$sql3);
    $row3=mysqli_fetch_assoc($result3);
	
    ?><table><td><?php
    if($row2["gender"]==1){?>
	    <div style=" background-color:#FFC9C9; height:25px; line-height:25px; width:400px; ">
		     <?php echo $row2["name"];?>小姐姐您好，這是您上次填寫問卷所得之分析結果!
		</div><?php
	    if($row3["gender"]==1){
		    echo "<div style=' background-color:#FFECC9; height:25px; line-height:25px; width:425px; '>我們已將您與".$row3["name"]."小姐的結果分析完畢了!</div>";
			
	    }else{
		    echo "<div style=' background-color:#FFECC9; height:25px; line-height:25px; width:425px; '>我們已將您與".$row3["name"]."先生的結果分析完畢了!</div>";	
	    }
    }else{?>
		<div style=" background-color:#FFC9C9; height:25px; line-height:25px; width:425px; ">
		     <?php echo $row2["name"];?>小哥哥您好，這是您上次填寫問卷所得之分析結果!
		</div><?php
	    if($row3["gender"]==1){
		    echo "<div style=' background-color:#FFECC9; height:25px; line-height:25px; width:425px; '>我們已將您與".$row3["name"]."小姐的結果分析完畢了!";
	    }else{
		    echo "<div style=' background-color:#FFECC9; height:25px; line-height:25px; width:425px; '>我們已將您與".$row3["name"]."先生的結果分析完畢了!";
	    }
    }
    $it=array_keys($seq);
    $j=$num;
    for($i=1; $i<=$num; $i++){
	    echo "<div style=' background-color:#FFFFC9; height:25px; line-height:25px; width:425px; '>第".$i."順位為"."<strong>".$it[$j-1]."</strong></br>";
	    $j--;
    }?>
</br>
<input type="submit" name="back"  value="返回功能表" style="width:100px;height:25px; border:2px #B5FFB5 double; background-color:#C9FFC9;" onclick="location.href= ('http://localhost:8080/meeting/functionselect.php')"/>
	
<input type="submit"  name="twodetailcho"   value="查看雙方細節" style="width:120px;height:25px; border:2px #ADADFF double; background-color:#C2C2FF;" onclick="location.href= ('http://localhost:8080/meeting/twodetail.php')"/>

</td>
<td><img src="TE.php">
</td>
<td><div style=" background-color:#E8C9FF; height:125px; line-height:25px; width:350px; ">
		     如右圖所示</br>SI值表示滿意的影響力，DSI值則不滿意的影響力</br>他們兩者會影響您對此服務的敏感度</br>
			 圖上項目距離左上角，也就是原點越遠</br>表示這個項目對你們而言越重要</br>
	</div>
</td>


</body>
</head>
</html> 