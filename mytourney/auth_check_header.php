<?php 

include"master_inc.php";
require_once "classes.php";

$username_from_cookie = $_COOKIE[NAME_COOKIE]; //retrieve contents of cookie
$userid_from_cookie = $_COOKIE[ID_COOKIE];

if($permission_level==''){

$sql="SELECT * FROM users WHERE username='$username_from_cookie'";

}else{

$threshold = $permission_level-1;

$sql="SELECT * FROM users WHERE username='$username_from_cookie' AND permissions>'$threshold'";

}

$result=mysql_query($sql);

// Mysql_num_row is counting table rows

$count=mysql_num_rows($result);

// If result matches $myusername and $mypassword, table row must be 1 row

if($count==0){

{
$refer = $_SERVER['PHP_SELF'];
header("location:$base_dir/login.php?referrer=$refer");

}

}

$query = "SELECT * FROM users WHERE `username`='$username_from_cookie'"; 

$numresults=mysql_query($query);
$numrows=mysql_num_rows($numresults); 

// get results
$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$permissions= $row["permissions"];

}

//end Chris Carr Auth Check Header

$username = $username_from_cookie;

?>