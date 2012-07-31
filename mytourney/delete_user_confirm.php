<? 
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include"auth_check_header.php";
require_once "login_config.php";

$id = $_REQUEST['id'];

// GET PLAYER'S USER TABLE VALUES AND ASSIGN SESSION VARIABLES
$username_from_cookie = $_COOKIE[NAME_COOKIE]; //retrieve contents of cookie
			
$query = "SELECT * FROM users WHERE id='$id'"; 

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


// Set appropriate messages based on available profile info
if ($privacy == 'on') { 
$privacy_ind = "<span style='color:red; font-weight:bold;'>*</span>";
$privacy_msg = "<p style='color:red';>* - You have chosen to keep this contact information private.</p>"; 
} 

else {
$privacy_ind = "<span style='color:green; font-weight:bold;';>*</span>";
$privacy_msg = "<p style='color:green;';>* - You currently share contact information with your opponent.</p>"; 
}

if ($phone != "") {
$has_phone = "<strong>Phone</strong>: $phone $privacy_ind<br />";
} else {
$has_phone = "<strong>Phone</strong>: [not provided] $privacy_ind<br />";
}			

if ($url != "") {
$has_url = "<strong>URL</strong>: <a href='$url' target='_blank'>$url</a><br />";
} else {
$has_url = "<strong>URL</strong>: [not provided]<br />";
}			

if ($password_hint != "") {
$has_hint = "<strong>Password Hint</strong>: \"$password_hint\"<br />";
} else {
$has_hint = "<strong>Password Hint</strong>: [not provided]<br />";
}			

if ($comment != "") {
$has_comment = "<strong>Public Comment</strong>: \"$comment\"<br />";
} else {
$has_comment = "<strong>Public Comment</strong>: [not provided]<br />";
}			

?>	

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>My Tournament</title>

<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:13px;}
#mymenu {float:right;}
.td_tourney {background-color:lightblue; font-weight:bold; font-size:larger;}
</style>

<body topmargin="0">
<div id="wrap" style="width:500px;">
<div id="mymenu"><a href="admin_index.php">Admin Index</a> | <a href="logout.php">Log Out</a></div>      

<h3>Delete User Confirmation</h3>
<center><p style="padding:10px; background-color:lightyellow; border:1px red dashed;">Do you <em>REALLY</em> want to delete user: <span style="color:red; font-weight:bold;"><? echo $username; ?></span>? <br /><br /><a href='admin_index.php?'>NO</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='delete_user.php?id=<? echo $id; ?>'>YES</a></p></center>
<?

echo "<strong>Real Name</strong>: $firstname $lastname<br />
<strong>Email</strong>: $email_sub $privacy_ind<br />" . $has_phone . $has_url . "<strong>Member Since</strong>: $joined_sub<br />
<strong>Time Zone</strong>: $timezone | <strong>Skill Level</strong>: $skill | <strong>Device</strong>: $device<br />" . $has_hint . $has_comment . "</p>";			  

?>			  			  

			  

</div>
</body>
</html>
