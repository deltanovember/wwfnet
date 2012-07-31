<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include"auth_check_header.php";

// GET PLAYER'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
$username_from_cookie = $_COOKIE[$cookiename]; //retrieve contents of cookie 
$sql="SELECT * FROM users WHERE username='$username_from_cookie'";
			
$query = "SELECT * FROM users WHERE username='$username_from_cookie'"; 

// get results
$result = mysql_query($query) or die("Couldn't execute query 1");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$id= $row["id"];
$username= $row["username"];
$password= $row["password"];
$password_hint= $row["password_hint"];
$lastname= $row["lastname"];
$firstname= $row["firstname"];
$phone= $row["phone"];
$email= $row["email"];
$permissions = $row["permissions"];
$email_sub = substr($email, 0, 50);
$joined= $row["joined"];
$joined_sub = substr($joined, 0, 10);
$privacy= $row["privacy"];
$timezone= $row["timezone"];
$comment= $row["comment"];
$skill= $row["skill"];
$device= $row["device"];
$url= $row["url"];
}


// Get query string
$var = $_REQUEST['q'];		

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Admin Index</title>

<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:13px; width:485px;}
#mymenu {float:right;}
.td_tourney {background-color:lightblue; font-weight:bold; font-size:larger;}
.screenname {color:green;}
#conf {color:#00CC00; border:1px dashed #00CC00; margin:10px; padding:10px;background-color:#FFFFEO;}
</style>
<? 
$conf = strip_tags(substr($_REQUEST['conf'],0,1));
$user = strip_tags(substr($_REQUEST['user'],0,25));
if ($conf == "1") {$confmsg = "Profile successfully updated for " . $user;
echo "<script type='text/javascript'>alert('" . $confmsg . "');</script>";
} 
?>
</head>
<body topmargin="0">
<div id="wrap">
<div id="mymenu"><a href="admin_index.php">Admin Index</a> | <a href="logout.php">Log Out</a></div>      
<p><span style="color:green; font-size:larger; font-weight:bold;">Hello, <? echo "$firstname" ?>!</span></p>

<form action="admin_index.php" method="post" name="form" id="form">
<input name="q" type='text' id='q' />&nbsp;<input type="submit" name="Submit22" value="Search Users" />
<br />
<br />          
<table width='485px' border='1' cellpadding='2' cellspacing='0'>
<tr>
<th colspan='4' class="td_tourney">Registered Users
<?php 
if ($var != "") { echo "<br /><span style='font-weight:normal; color:blue; font-style:italic;'>Results for: '$var'</span>"; }
?>

</th>
</tr>
<th>ID</th><th>User Name</th><th>Contact Info</th>
</tr> 

<?		
$color1 = "#ffffff";  
$color2 = "#ebebeb"; 	

$query = "SELECT * FROM users WHERE (`id` LIKE \"%$var%\" OR `username` LIKE \"%$var%\" OR `email` LIKE \"%$var%\" OR `lastname`LIKE \"%$var%\" OR `firstname`LIKE \"%$var%\") ORDER BY `username` asc"; 

$numresults=mysql_query($query);
$numrows=mysql_num_rows($numresults); 

// get results
$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$id= $row["id"];
$username= $row["username"];
$password= $row["password"];
$lastname= $row["lastname"];
$firstname= $row["firstname"];
$phone= $row["phone"];
$email= $row["email"];
$premium = $row["premium"];
$email_sub = substr($email, 0, 50);

$row_color = ($row_count % 2) ? $color1 : $color2; 
//DISPLAY DATA HERE_____________

echo "
<tr valign='top'>
<td align='center' bgcolor='$row_color' >$id</td>
                <td bgcolor='$row_color' ><span class='screenname'><a href='edit_user.php?id=$id'>$username</a></span><br />"; 
if ($lastname == "") { echo "$firstname"; }
else { echo "$firstname&nbsp;$lastname"; }

if ($premium == "yes") { echo "<span class='premium'>*</span>"; }
else {};

echo "          </td>
                <td bgcolor='$row_color' ><a href='mailto:$email_sub'>$email_sub</a><br /><div style='float:right;'><a href='referee_index.php?id=$id'><img src='images/yellow_flag.gif' border='0px' /></a>&nbsp;<a href='edit_user.php?id=$id'><img src='images/profile_edit.png' border='0px' /></a>&nbsp;<a href='delete_user_confirm.php?id=$id'><img src='images/redx.gif' border='0px' /></a></div>";
				
if ($phone != "") { echo "Phone: $phone"; }
else {};

echo "</td></tr>";
    $row_count++; 
} 

?>
</table>
</form>

</div> <!-- Close Master Div -->
</body>
</html>
