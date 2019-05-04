<?php
		
   $wpid = '74'; // blob
   $count_no_update = 0;
   $count_update = 0;
   $count_all_row = 0;
   $host        = "host = localhost";
   $port        = "port = 5432";
   $dbname      = "dbname = mol";
   $credentials = "user = mol password=mol010!#";

   $conn = pg_connect( "$host $port $dbname $credentials"  );
   if(!$conn) {
      echo "Error : Unable to open database\n";
   } else {
      echo "Opened database successfully\n";
		$post_content = '';
		$search = '</p>';
		//$a = trim($name); 
		//$query = "SELECT surname FROM employee WHERE name= '" . $a . "';"; 

		$query = "SELECT * FROM public.wp_".$wpid."_posts WHERE post_type='news' and post_status<>'trash'";
		//$query = 'SELECT * FROM public.wp_".$wpid."_posts WHERE "ID"=2965';
		$result = pg_query($conn, $query); 
		while($row = pg_fetch_array($result)){ 
			//print "----".$row['ID']."|".$row['post_content']."<br>"; 
			$str1 = "";
			$str2 = $row['post_content'];
			$ID = $row['ID'];
			$ans = "";
			$i = 0;
			if($str2!=""){
				while(true){
				
					if($i>0){
						//echo "i=".$ID."<br>str1=".$str1."str2=".$str2."<br>------------------<br>";
						$findin = strpos($str2,$str1);
						if($findin==False){
							//echo "i=".$i."<br>str1=".$str1."<br>------------------<br>";
							break;
						}else{
							$ans = $str2;
						}
					}else{
						$ans = $str2;
					}
					$pos1 = strpos($str2, $search);
					if($pos1==""){
						break;
					}
					$str1 = $str1.substr($str2,0,$pos1+4);			
					$str2 = substr($str2,$pos1+4,strlen($str2));
					
					$i++;
					
				}
				if ($ans == $row['post_content']){
					$count_no_update = $count_no_update+1;
					echo "no update ID is ".$ID."<br>";
				}else{
					$ans = str_replace("'","&#39;",$ans);
					$sql_update = "update public.wp_".$wpid."_posts set post_content = '".$ans."' where ".'"ID" = '.$ID;
					//echo $sql_update."<br>";
					$result1 = pg_query($conn, $sql_update); 
					$count_update = $count_update+1;
				}
				$count_all_row = $count_all_row+1;
				//echo $ans;
			}
			
		}
		
   }
   echo "count_all_row = ".$count_all_row."<br>";
   echo "count_update = ".$count_update."<br>";
   echo "count_no_update = ".$count_no_update."<br>";
?>