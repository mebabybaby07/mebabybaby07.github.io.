 <?php
     
	//第四象限繪製	
    $im2=imagecreatetruecolor(401,401); 
	$bg = imagecolorallocate($im2, 0, 0, 0);      //設定黑色
	$red=imagecolorallocate($im2,255,0,0);        //設定紅色
	$white=imagecolorallocate($im2,255,255,255);  //設定白色
	$gray=imagecolorallocate($im2,220,220,220);   //設定灰色
	//imagefilledrectangle($im2,0,0,400,400,$white);//圖底色為白色
		
	$triangle=array(300,10,300,20,305,15);         
	imagefilledpolygon($im2,$triangle,3,$red);    //Y軸三角形
	$triangle=array(10,300,20,300,15,305);         
	imagefilledpolygon($im2,$triangle,3,$red);    //X軸三角形
	imageline($im2,15,15,15,300,$red);            //直線-Y軸
	imageline($im2,14,15,14,300,$red);            //直線-Y軸加粗
	imageline($im2,15,15,300,15,$red);            //直線-X軸
	imageline($im2,15,14,300,14,$red);            //直線-X軸加粗
	imagefilledrectangle($im2,250,250,400,400,$white); //圖例底色
	imagefilledrectangle($im2,250,250,400,267,$gray); //圖例底色
	imageline($im2,0,0,400,0,$white);             //外框
	imageline($im2,0,0,0,400,$white);             //外框
	imageline($im2,400,0,400,400,$white);         //外框
	imageline($im2,0,400,400,400,$white);         //外框
	
	imagestring($im2,5,310,10,"SI",$red);
	imagestring($im2,5,10,310,"DSI",$red);
	imagestring($im2,5,4,4,"O",$red);
	$font='C:\Windows\Fonts\kaiu.ttf';
	$dsa="圖例:";
	imagettftext($im2,13,0,250,265,$red,$font,$dsa);
	
	$A=imagecolorallocate($im2,0,255,128);        //設定春綠色
	$B=imagecolorallocate($im2,255,128,0);        //設定橘色
	$C=imagecolorallocate($im2,255,215,0);        //設定黃色 
	$D=imagecolorallocate($im2,127,255,0);        //設定綠色
	$E=imagecolorallocate($im2,25,25,112);        //設定靛色
	$F=imagecolorallocate($im2,138,43,226);       //設定紫色
	$G=imagecolorallocate($im2,255,127,80);       //設定粉色
	$H=imagecolorallocate($im2,160,82,45);        //設定棕色
	$I=imagecolorallocate($im2,128,138,135);      //設定灰色
	$J=imagecolorallocate($im2,255,128,191);      //設定粉色
	/*顏色測試
	$font='C:\Windows\Fonts\kaiu.ttf';
	$dsa="圖例";	
    imagettftext($im2,13,0,150,150,$A,$font,$dsa);
	imagettftext($im2,13,0,150,163,$B,$font,$dsa);
	imagettftext($im2,13,0,150,176,$C,$font,$dsa);
	imagettftext($im2,13,0,150,189,$D,$font,$dsa);
	imagettftext($im2,13,0,150,202,$E,$font,$dsa);
	imagettftext($im2,13,0,150,215,$F,$font,$dsa);
	imagettftext($im2,13,0,150,228,$G,$font,$dsa);
	imagettftext($im2,13,0,150,241,$H,$font,$dsa);
	imagettftext($im2,13,0,150,254,$I,$font,$dsa);
	imagettftext($im2,13,0,150,267,$J,$font,$dsa);
	*/
	
	//直接連資料庫去挖距離
	
	$link =@mysqli_connect('localhost','root','1234','meeting');
    mysqli_query($link,'SET NAMES utf8');
    session_start();
    $userphone = $_SESSION["userphone"]; 
    $anophone = $_SESSION["anophone"];
	$qid = $_SESSION["qid"];
	
	$sql="SELECT A,B,C,D,E,F,G,H,I,J FROM twoanswer WHERE phone=$userphone";   //兩人項目優先序
    $result=mysqli_query($link,$sql);
    $row=mysqli_fetch_row($result);
	$seq=array();
    $num=0;                                                                //項目數目
	
    for($i=1; $i<=10; $i++){
	    if($row[$i-1]!=0){
		    $sql2="SELECT itemname FROM itemnum WHERE itemnum='$i'";
		    $result2=mysqli_query($link,$sql2);
		    $row2=mysqli_fetch_row($result2);
		    $seq["$row2[0]"]=$row[$i-1];       //項目的順序為:
		    $num++;
	    }
    }
	$q=0; $r=0;	$a=0; $o=0; $m=0; $i=0;           //使用者各需求個數為0
	$q1=0; $r1=0;	$a1=0; $o1=0; $m1=0; $i1=0;   //另一伴各需求個數為0
	$si=0; $dsi=0; $distance=0;
				
	mb_internal_encoding("UTF-8");    //提取第一個問題代碼的英文
	$itemf=$qid[0];
    $itemfirst = mb_substr($itemf, 0, 1);  //位元移動取第一位
	
    $numm=count($qid);	
	for($h=0; $h<count($qid); $h+=2){
		//提取問題代碼的英文
		mb_internal_encoding("UTF-8");         
	    $items=$qid[$h];
        $itemsec = mb_substr($items, 0, 1);
			
		//讀取使用者正面題目之選項
		$sql1="SELECT $qid[$h] FROM twoanswer WHERE phone=$userphone"; 
		$result1=mysqli_query($link,$sql1);
		$row1=mysqli_fetch_row($result1);
		//echo $row1[0]."+";               //正面問題的答案(使用者)
			
		//讀取使用者反面題目之選項
	    $k=$h+1;
		$sql2="SELECT $qid[$k] FROM twoanswer WHERE phone=$userphone"; 
		$result2=mysqli_query($link,$sql2);
	    $row2=mysqli_fetch_row($result2);
		//echo $row2[0]."  ";             //反面問題的答案(使用者)
						
	    //讀取另一伴正面題目之選項
		$sql3="SELECT $qid[$h] FROM twoanswer WHERE phone=$anophone"; 
		$result3=mysqli_query($link,$sql3);
		$row3=mysqli_fetch_row($result3);
		//echo $row3[0]."-";               //正面問題的答案(另一伴)
			
		//讀取另一伴反面題目之選項
		$sql4="SELECT $qid[$k] FROM twoanswer WHERE phone=$anophone"; 
		$result4=mysqli_query($link,$sql4);
		$row4=mysqli_fetch_row($result4);
		//echo $row4[0]."  ";             //反面問題的答案(另一伴)
		
		if($itemsec==$itemfirst){ 
			        //使用者分析
			        if(($row1[0]==1 &&$row2[0]==1 )|| ($row1[0]==5 &&$row2[0]==5)){
				        $q++;
			        }else if( ($row1[0]==2 && $row2[0]==1) || ($row1[0]==3 && $row2[0]==1) || ($row1[0]==4 && $row2[0]==1) || ($row1[0]==5 && $row2[0]==1)){  
						$r++;
					}else if( ($row1[0]==5 && $row2[0]==2) || ($row1[0]==5 && $row2[0]==3) || ($row1[0]==5 && $row2[0]==4)){ 
						$r++;
			        }else if( ($row1[0]==1 && $row2[0]==2) || ($row1[0]==1 && $row2[0]==3) || ($row1[0]==1 && $row2[0]==4)){ 
						$a++;
			        }else if( $row1[0]==1 && $row2[0]==5 ) {
						$o++;
			        }else if( ($row1[0]==2 && $row2[0]==5) || ($row1[0]==3 && $row2[0]==5) || ($row1[0]==4 && $row2[0]==5)){
						$m++;
			        }else {
						$i++;
			        }
				    //另一伴分析
			        if(($row3[0]==1 &&$row4[0]==1 )|| ($row3[0]==5 &&$row4[0]==5)){
				        $q1++;
			        }else if( ($row3[0]==2 && $row4[0]==1) || ($row3[0]==3 && $row4[0]==1) || ($row3[0]==4 && $row4[0]==1) || ($row3[0]==5 && $row4[0]==1)){  
						$r1++;
					}else if( ($row3[0]==5 && $row4[0]==2) || ($row3[0]==5 && $row4[0]==3) || ($row3[0]==5 && $row4[0]==4)){
						$r1++;
			        }else if( ($row3[0]==1 && $row4[0]==2) || ($row3[0]==1 && $row4[0]==3) || ($row3[0]==1 && $row4[0]==4)){
						$a1++;
			        }else if( $row3[0]==1 && $row4[0]==5 ) {
						$o1++;
			        }else if( ($row3[0]==2 && $row4[0]==5) || ($row3[0]==3 && $row4[0]==5) || ($row3[0]==4 && $row4[0]==5)){ 
						$m1++;
			        }else {
						$i1++;
			        } 	
		        }else{
				    $total["$itemfirst-si"]=($a+$a1+$o+$o1)/($a+$a1+$o+$o1+$m+$m1+$i+$i1);
		            $total["$itemfirst-dsi"]=($o+$o1+$m+$m1)/($a+$a1+$o+$o1+$m+$m1+$i+$i1);
				    $total["$itemfirst-distance"]=sqrt($total["$itemfirst-si"]*$total["$itemfirst-si"]+$total["$itemfirst-dsi"]*$total["$itemfirst-dsi"]);
				    $artotal["$itemfirst"]=sqrt($total["$itemfirst-si"]*$total["$itemfirst-si"]+$total["$itemfirst-dsi"]*$total["$itemfirst-dsi"]);
				    $value=round($total["$itemfirst-distance"],3);
					
					$sql5="SELECT itemname FROM itemnum WHERE itemcode='$itemfirst'"; 
		            $result5=mysqli_query($link,$sql5);
		            $row5=mysqli_fetch_row($result5);
					$name=$row5[0].":".$value;
					$str = mb_convert_encoding($name, "html-entities", "utf-8");
					
					$itemsi=300*$total["$itemfirst-si"]+25;
	                $itemdsi=300*$total["$itemfirst-dsi"]+25;
					$font='C:\Windows\Fonts\kaiu.ttf';
					
					imagettftext($im2,12,0,250,262+$h*2,$$itemfirst,$font,$str); //項目名稱					
                    imageline($im2,15,15,$itemsi,$itemdsi,$$itemfirst);                //項目直線
					imagefilledellipse($im2,$itemsi,$itemdsi,5,5,$$itemfirst);       //項目圓形

			        $q=0; $a=0; $r=0; $o=0; $m=0; $i=0;
					
				    if(($row1[0]==1 &&$row2[0]==1 )|| ($row1[0]==5 &&$row2[0]==5)){
				        $q++;
			        }else if( ($row1[0]==2 && $row2[0]==1) || ($row1[0]==3 && $row2[0]==1) || ($row1[0]==4 && $row2[0]==1) || ($row1[0]==5 && $row2[0]==1)){
						$r++;
					}else if( ($row1[0]==5 && $row2[0]==2) || ($row1[0]==5 && $row2[0]==3) || ($row1[0]==5 && $row2[0]==4)){ 
						$r++;
			        }else if( ($row1[0]==1 && $row2[0]==2) || ($row1[0]==1 && $row2[0]==3) || ($row1[0]==1 && $row2[0]==4)){  
						$a++;
			        }else if( $row1[0]==1 && $row2[0]==5 ) { 
						$o++;
			        }else if( ($row1[0]==2 && $row2[0]==5) || ($row1[0]==3 && $row2[0]==5) || ($row1[0]==4 && $row2[0]==5)){
						$m++;
			        }else { 
						$i++;
			        }
                    $q1=0;
			        $a1=0;				
			        $r1=0;			    
			        $o1=0;			   
			        $m1=0;			   
			        $i1=0;
				    if(($row3[0]==1 &&$row4[0]==1 )|| ($row3[0]==5 &&$row4[0]==5)){ 
				        $q1++;
			        }else if( ($row3[0]==2 && $row4[0]==1) || ($row3[0]==3 && $row4[0]==1) || ($row3[0]==4 && $row4[0]==1) || ($row3[0]==5 && $row4[0]==1)){  
						$r1++;
					}else if( ($row3[0]==5 && $row4[0]==2) || ($row3[0]==5 && $row4[0]==3) || ($row3[0]==5 && $row4[0]==4)){ 
						$r1++;
			        }else if( ($row3[0]==1 && $row4[0]==2) || ($row3[0]==1 && $row4[0]==3) || ($row3[0]==1 && $row4[0]==4)){ 
						$a1++;
			        }else if( $row3[0]==1 && $row4[0]==5 ) { 
						$o1++;
			        }else if( ($row3[0]==2 && $row4[0]==5) || ($row3[0]==3 && $row4[0]==5) || ($row3[0]==4 && $row4[0]==5)){ 
						$m1++;
			        }else {
						$i1++;
			        }
				    $itemfirst=$itemsec;
		        }           			
	}
    $total["$itemfirst-si"]=($a+$a1+$o+$o1)/($a+$a1+$o+$o1+$m+$m1+$i+$i1);
	$total["$itemfirst-dsi"]=($o+$o1+$m+$m1)/($a+$a1+$o+$o1+$m+$m1+$i+$i1);
	$total["$itemfirst-distance"]=sqrt($total["$itemfirst-si"]*$total["$itemfirst-si"]+$total["$itemfirst-dsi"]*$total["$itemfirst-dsi"]);
	$artotal["$itemfirst"]=sqrt($total["$itemfirst-si"]*$total["$itemfirst-si"]+$total["$itemfirst-dsi"]*$total["$itemfirst-dsi"]);
	$value=round($total["$itemfirst-distance"],3);
	
	$sql5="SELECT itemname FROM itemnum WHERE itemcode='$itemfirst'"; 
	$result5=mysqli_query($link,$sql5);
	$row5=mysqli_fetch_row($result5);
	$name=$row5[0].":".$value;
	$str = mb_convert_encoding($name, "html-entities", "utf-8");
					
	$itemsi=300*$total["$itemfirst-si"]+25;
	$itemdsi=300*$total["$itemfirst-dsi"]+25;
	$font='C:\Windows\Fonts\kaiu.ttf';
					
	imagettftext($im2,12,0,250,262+$h*2,$$itemfirst,$font,$str); //項目名稱					
    imageline($im2,15,15,$itemsi,$itemdsi,$$itemfirst);                //項目直線
	imagefilledellipse($im2,$itemsi,$itemdsi,5,5,$$itemfirst);       //項目圓形
	
	ob_clean();
	imagecolortransparent($im2,$bg);   //背景透明
    header("Content-type: image/png");
    imagepng($im2);

?>