<?php
//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include"../auth_check_header.php";
require_once "../login_config.php";
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>

<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>My Tournament</title>

<link href="../mytourney.css" rel="stylesheet" type="text/css">

</head>

<body topmargin="0">

<table class='mt_table'>
<tr valign='top'>
<th colspan='4' class='td_tourney'>Tournament Administration</th>
</tr>
<th width='35%'><a href="create_monthly.php"  onclick="return confirm('Are you sure you wish to create a new monthly?')">Create monthly</a></th>
<th width='' style='text-align:left;'>Create new monthly tournament</th>
</table>
<br />
    <table class='mt_table'>
<tr valign='top'>
<th colspan='4' class='td_tourney'>User Administration</th>
</tr>
<th width='35%'><a href="admin_index.php">Users</a></th>
<th width='' style='text-align:left;'>View and edit users</th>
</table>

