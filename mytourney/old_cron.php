<?php 

$db_host = "db2279.perfora.net";
$db_username = "dbo317698046";
$db_password = "wwf9977";
$db = "db317698046";
   mysql_connect( $db_host, $db_username, $db_password )
      or die( "Error! Could not connect to database: " . mysql_error() 
);
   
   // select the database
   mysql_select_db( $db )
      or die( "Error! Could not select the database: " . mysql_error() 
);
	   
$query = "SELECT * FROM T_CONTROL WHERE `t_status` = 'In Progress' AND `t_status` <> 'Complete' AND `t_desc` LIKE('%America%') ORDER BY `t_start` DESC"; 
$result = mysql_query($query) or die("Couldn't execute query");
$count=mysql_num_rows($result);

while ($row= mysql_fetch_array($result)) {

	$t_id= $row["t_id"];
	$t_start= $row["t_start"];
	$t_desc= $row["t_desc"];
	$t_round= $row["t_round"];
	$max_round = $row["max_round"];
	$new_round= $t_round+1;
	$t_status= $row["t_status"];
	$lastresult = "r" . $t_round . "_rslt";
	$drop = $t_round-1;
	$twobackresult = "r" . $drop . "_rslt";

	if ($new_round <= $max_round) {
		$query2 = "UPDATE `T_CONTROL` SET t_round = '" . $new_round . "' WHERE t_id = '" . $t_id . "'";
		$result2 = mysql_query($query2) or die("Couldn't execute query2");
		
		if ($drop > 0) {

			$query6 = "SELECT * FROM " . $t_id . " WHERE " . $twobackresult . " = 'No-Show' OR " . $twobackresult . " IS NULL"; 
			$result6 = mysql_query($query6) or die("Couldn't execute query6");	
		
			while ($row= mysql_fetch_array($result6)) {
				$userid = $row["id"];
				$this_result = $row["r" . $t_round . "_rslt"];
				if ($this_result == "No-Show" || $this_result == ""){ 		
					$query5 = "UPDATE " . $t_id . " SET status = 'Eliminated' WHERE `id` = " . $userid;
					$result5 = mysql_query($query5) or die("Couldn't execute query5");		
					}
				else {}	
				}

			}
			
		else {}
		
		$query4 = "UPDATE " . $t_id . " SET onround = '" . $new_round . "' WHERE `status` = 'Active'"; 
		$result4 = mysql_query($query4) or die("Couldn't execute query4");
		}

	else {
		$query3 = "UPDATE `T_CONTROL` SET t_status = 'Complete' WHERE t_id = '" . $t_id . "'";
		$result3 = mysql_query($query3) or die("Couldn't execute query3");
		die();
		} 
	}



// Establish which records have control
$query = "SELECT * FROM " . $t_id . " WHERE r" . $new_round . "_vs IS NULL AND status <> 'Eliminated' ORDER BY RAND()"; 
$result = mysql_query($query) or die("Couldn't execute query control");
$count=mysql_num_rows($result); 

// if # of records is odd, then give control to even-numbered records
if ($count&1) { $i=0; }
else { $i=1; }

while ($row= mysql_fetch_array($result)) {
    if ($i&1) { 
		$id = $row["id"];
	    $query = "UPDATE $t_id SET `r" . $new_round . "_ctrl` = 'yes' WHERE `id` = '" . $id . "'";
        $results = mysql_query( $query );
		}
	else {}
    $i++;
	}
	

//Define database update function
function assignPlayers($t_id,$op_id,$person0,$fname0,$new_round,$versus_sn,$hint0,$email0,$t_desc){
// echo "Here's the array for <b>" . $person0 . ":</b><br />" . "tournament = " . $t_id . " | " . $t_desc . "<br /> ID = " . "$op_id" . "<br /> fname = " . $fname0 . "<br /> Opponent = " . $versus_sn . "<br /> email = " . $email0 . "<br /> hint = " . $hint0 . "<br /><br />";

	//populate database and send email

	$query7 = "UPDATE $t_id SET `r" . $new_round . "_vs` = '" . $versus_sn . "' WHERE `id` = '" . $op_id . "'";
	$results7 = mysql_query( $query7 );
	
	$from = "Admin <admin@wordswithfriends.net>";
	$reply_to = "no-reply@wordswithfriends.net";
	$return_path = "no-reply@wordswithfriends.net";
	
	$to = $email0;
	
	$subject = "[WordsWithFriends.net] Your next opponent is '" . $versus_sn . "'";
	
	
	$mailbody = "Dear " . $fname0 . " ('" . $person0 . "'): <br><br>'" . $versus_sn . "' is your Round " . $new_round . " opponent in the " . $t_desc . "! You can contact '" . $versus_sn . "' and take other actions at your <a href='http://wordswithfriends.net/?page_id=386'>MyTourney page</a> (Password hint: '" . $hint0 . "').<br /><br /><em>NOTE: This round will close approximately 24 hours from this email.</em><br><br>Please do not reply to this message. Thanks for playing!";
	
	//____________________________Begin Multipart Mail Sender
	//add From: header 
	$headers = "From:$from\nReply-to:$reply_to\nReturn-path:$return_path\nJobID:$date\n"; 
	
	//specify MIME version 1.0 
	$headers .= "MIME-Version: 1.0\n"; 
	
	//unique boundary 
	$boundary = uniqid("HTMLDEMO8656856"); 
	
	//tell e-mail client this e-mail contains//alternate versions 
	$headers.="X-Priority: 3\n";
	$headers.="Content-Type: multipart/alternative; boundary=\"".$boundary."\"\n";
	$headers.="Content-Transfer-Encoding: 7bit\n";
	
	//message to people with clients who don't 
	//understand MIME 
	$headers .= "This is a MIME encoded message.\n\n"; 
	
	//plain text version of message 
	$headers .= "--$boundary\n" . 
	   "Content-Type: text/plain; charset=ISO-8859-1\r\n" . 
	   "Content-Transfer-Encoding: base64\n\n"; 
	$headers .= chunk_split(base64_encode("$mailbody")); 
	
	//HTML version of message 
	$headers .= "--$boundary\n" . 
	   "Content-Type: text/html; charset=ISO-8859-1\n" . 
	   "Content-Transfer-Encoding: base64\n\n"; 
	$headers .= chunk_split(base64_encode("$mailbody")); 
	
	mail("$to", "$subject", "", $headers);
	
	}	

//set php script timeout, 0 to disable
set_time_limit(0); 
	
// Assignment script
$query = "SELECT * FROM " . $t_id . " WHERE r" . $new_round . "_vs IS NULL AND status <> 'Eliminated' AND `r" . $new_round . "_ctrl` = 'yes' ORDER BY RAND()"; 
$result = mysql_query($query) or die("Couldn't execute query0");
while ($row= mysql_fetch_array($result)) {
	$odd = $row["id"];
	$player = $row["player"];

	if ($drop > 0) {
	$query1 = "SELECT * FROM " . $t_id . " WHERE r" . $new_round . "_vs IS NULL AND r" . $drop . "_vs <> '" . $player . "' AND r" . $t_round . "_vs <> '" . $player . "' AND `r" . $new_round . "_ctrl` IS NULL AND status <> 'Eliminated' LIMIT 1"; 
		}
	
	elseif ($drop == 0) {
	$query1 = "SELECT * FROM " . $t_id . " WHERE r" . $new_round . "_vs IS NULL AND r" . $t_round . "_vs <> '" . $player . "' AND `r" . $new_round . "_ctrl` IS NULL AND status <> 'Eliminated' LIMIT 1"; 
		}
		
	else {
	$query1 = "SELECT * FROM " . $t_id . " WHERE r" . $new_round . "_vs IS NULL AND `r" . $new_round . "_ctrl` IS NULL AND status <> 'Eliminated' LIMIT 1"; 
		}
	
	$result1 = mysql_query($query1) or die("Couldn't execute query_lookback");
	while ($row= mysql_fetch_array($result1)) {
		$even = $row["id"];
		$opponent = $row["player"];	
	}
	
	// Set player and opponent variables for database update
	$query1 = "SELECT * FROM users WHERE id='$odd'";
	$result1 = mysql_query($query1) or die("Couldn't execute query1");
	while ($row= mysql_fetch_array($result1)) {
		$p_fname= $row["firstname"];
		$p_email= $row["email"];
		$p_hint= $row["password_hint"];
		}
	
	$query1 = "SELECT * FROM users WHERE id='$even'";
	$result1 = mysql_query($query1) or die("Couldn't execute query3");
	while ($row= mysql_fetch_array($result1)) {
		$o_fname= $row["firstname"];
		$o_email= $row["email"];
		$o_hint= $row["password_hint"];
		  }
	
	$query10 = "SELECT * FROM $t_id WHERE `r" . $new_round . "_vs` = '" . $opponent . "'";
	$result10 = mysql_query($query10) or die("Couldn't execute query");
	$count=mysql_num_rows($result10); 
	
	if ($count > 0) {
	    $query = "UPDATE $t_id SET `r" . $new_round . "_ctrl` = 'no' WHERE `id` = '" . $odd . "'";
        $results = mysql_query( $query );	
		}
	
	else {
	assignPlayers("$t_id","$odd","$player","$p_fname","$new_round","$opponent","$p_hint","$p_email","$t_desc"); // Function call for this Player
	assignPlayers("$t_id","$even","$opponent","$o_fname","$new_round","$player","$o_hint","$o_email","$t_desc"); // Function call for the Opponent
		}
		
	}

//Reset to 30 seconds.
set_time_limit(30); 

?>