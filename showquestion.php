<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>感受問卷作答</title>
<style>
 body{
	   background-image:url(16.jpg);	  
	   background-repeat:no-repeat;
	   background-position:center;
	   background-size:cover;
 }
</style>
</head>
<body  text="#0000ff">
<font  color='#1e90ff' size='6'>問卷作答</font> <?php 
    session_start();
    $link =@mysqli_connect('localhost','root','1234','meeting');
    mysqli_query($link,'SET NAMES utf8');
    $userphone=$_SESSION["userphone"];
    

//個人問卷填寫
if(isset($_GET["personitemselect"])){  ?>
    <form name="write" method="get" action="analysis.php" onsubmit="return chk();"><?php  
	echo "<font color='red'>請注意，這是個人分析，並不會計入雙方的分析結果喔~</br></font>";
    $sql = "SELECT * FROM itemnum ";
    $result = mysqli_query($link,$sql); 
    $total_records=mysqli_num_rows($result);
    $item = $_SESSION["item"] ;
    $select_item=0;
  
    for($i=1; $i<$total_records+1; $i++){      //判斷共選了幾個項目
        if(isset($item[$i-1])){$select_item++;}
    }
    $_SESSION["select_item"]=$select_item;
    $qnumber=0;
 
    for($i=0; $i<$select_item; $i++){	         //依照選了幾個項目，去跑幾次
		echo "<table border=1 cellspacing=5 cellpadding=2>";
	    while($row = mysqli_fetch_assoc($result)){	
	        if($row["itemnum"]==$item[$i]){
                echo "<br><tr><td>".$row["itemname"]."</td><td align=center>問題敘述"."</td><td>您的感受</td></tr>";
                break;		  
	        }	  
	    }
 
        $sql1 = "SELECT * FROM questionset ";
	    $result1 = mysqli_query($link,$sql1);
	    while($row1 = mysqli_fetch_array($result1,MYSQLI_ASSOC)){	                                  //跑一項目裡的題目
	        if($row1["itemnum"]==$item[$i]){
                $qid[]=$row1["qid"];
		        $qnum=$qnumber+1;
	            echo "<tr><td align=right>".$qnum."</td><td>".$row1["question"]."</td>"."<td>"?> 
		                  <input type="radio" name="<?php echo "Q".$qnumber;?>" value="1" />喜歡
                          <input type="radio" name="<?php echo "Q".$qnumber;?>" value="2" />理所當然
                          <input type="radio" name="<?php echo "Q".$qnumber;?>" value="3" />無感
                          <input type="radio" name="<?php echo "Q".$qnumber;?>" value="4" />能忍受
                          <input type="radio" name="<?php echo "Q".$qnumber;?>" value="5" />不喜歡	<?php 
		        echo "</td></tr>"; 
		        $qnumber++;
	        }
	    }
		echo "</table>";
    }
    $_SESSION["qid"] = $qid;?></br>   
    都填寫完畢就送出八~~<br/> 
    <input type="submit" name="answer" value="個人問卷送出" style="width:120px;height:25px; border:2px #87ceeb double; background-color:#add8e6;"> 
    </form><?php
	
}else{            
//雙人問卷填寫

    $anophone=$_SESSION["anophone"];
    $sql4="SELECT * FROM twoanswer WHERE phone=$userphone";        //判斷是否已有使用者以前的紀錄
    $result4=mysqli_query($link,$sql4);
    $userexist=0;
    $userexist=mysqli_num_rows($result4);
    if($userexist!=0){
	   $sql5="DELETE FROM twoanswer WHERE phone=$userphone";
	   mysqli_query($link,$sql5);
    }
    ?>
    <form action="analysis.php" name="twoanswer" method="get"   onsubmit="return chk();" ><?php 
	$sql = "SELECT A,B,C,D,E,F,G,H,I,J FROM user WHERE phone=$userphone";  //使用者所選項目
    $result = mysqli_query($link,$sql);
    $row=mysqli_fetch_row($result);
	$sql1 = "SELECT A,B,C,D,E,F,G,H,I,J FROM user WHERE phone=$anophone";  //使用者另一伴所選項目
	$result1 = mysqli_query($link,$sql1);
    $row1=mysqli_fetch_row($result1);
	
	$qnumber=0;
	$select_item=0; //總選取的項目個數
	
	for($i=1; $i<=10; $i++){     
	  if($row[$i-1]==1 || $row1[$i-1]==1){        //使用者有選且另一伴也有選
		$select_item++;                           //總選取的項目個數+1
		$sql2 = "SELECT * FROM itemnum ";
        $result2 = mysqli_query($link,$sql2); 
		
        echo "<table border=1 cellspacing=5 cellpadding=2>";		
	    while($row2 = mysqli_fetch_assoc($result2)){	                       //顯示要填寫的項目名稱
	         if($row2["itemnum"]==$i){
                echo "<br><tr><td>".$row2["itemname"]."</td><td align=center>問題敘述"."</td><td>您的感受</td></tr>";	
                //break;		  
	         }	  
	    }
        $sql3 = "SELECT * FROM questionset ";
	    $result3 = mysqli_query($link,$sql3);
	    while($row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC)){	                                  //跑一項目裡的題目
	         if($row3["itemnum"]==$i){
                $qid[]=$row3["qid"];
				$qnum=$qnumber+1;
	            echo "<tr><td align=right>".$qnum."</td><td>".$row3["question"]."</td>"."<td>"?> 
		                <input type="radio" name="<?php echo "Q".$qnumber;?>" value="1" id="1"/>喜歡
                        <input type="radio" name="<?php echo "Q".$qnumber;?>" value="2" id="2"/>理所當然
                        <input type="radio" name="<?php echo "Q".$qnumber;?>" value="3" id="3"/>無感
                        <input type="radio" name="<?php echo "Q".$qnumber;?>" value="4" id="4"/>能忍受
                        <input type="radio" name="<?php echo "Q".$qnumber;?>" value="5" id="5"/>不喜歡			 <?php 
		        echo "</td></tr>"; 
		        $qnumber++;
	         }
	    }		
	  }  
	}
	$sql2 = "SELECT pair FROM user WHERE phone=$userphone ";
    $result2 = mysqli_query($link,$sql2);
	$row2=mysqli_fetch_row($result2);
	$_SESSION["pair"]=$row2[0];
	$_SESSION["qid"] = $qid;
	$_SESSION["select_item"]=$select_item;?>
    </table>
    </br>
    都填寫完畢就存檔八~~<br/>
    <input type="submit" name="twoanswer" value="送出" style="width:120px;height:25px; border:2px #87ceeb double; background-color:#add8e6;"> 
    </form><?php	
  }?>
</body>
  <script type="text/javascript">  //顯示未填寫之題目
  $qnumber=<?php echo $qnumber;?>; 
  function chk(){
	for (var i = 0; i < $qnumber; i++) {
		var h="Q"+i;  //目前問題號碼
		var num=0;    //沒有選擇的RADIO數量
		for(var j=0; j<5; j++){
			var k=document.getElementsByName(h)[j].checked;
			if(k==false){
				num++;
				if(num==5){
				   var h="Q"+(i+1);
				   alert(h+"沒有填到喔~");
				   return false;
				}
			}
        }   
    }
	return true;
  }	
  </script>
</html>