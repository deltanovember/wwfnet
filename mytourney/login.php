<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Login</title>
<style type="text/css">
#wrap {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:500px;}
#conf {color:#00CC00; border:1px dashed #00CC00; margin:10px; padding:10px;background-color:#FFFFEO;}
</style>


</head>

<body topmargin="0">
<center>
<div id="wrap">
<?php
$conf = "";
if (isset ($_REQUEST['conf'])) {
    $conf = strip_tags(substr($_REQUEST['conf'],0,1));
}

$firstname = "";
if (isset ($_REQUEST['user'])) {
    $firstname = strip_tags(substr($_REQUEST['user'],0,25));
}

if ($conf == "1") {$confmsg = "Welcome to the tournament";}
if ($conf == "2") {
    $confmsg = "Your profile has been updated";
}
if ($conf == "1" || $conf == "2") {
    $confirm_message = "";
    if ($conf == "1") {
         $confirm_message = " Please check your email for the confirmation link. After confirming you may log in.";
    }
    echo $confmsg . ", " . $firstname . "!$confirm_message";
    }

 if ($conf != "1") {

    ?>
            <h3><a href="user_add.php">New to the Tournament? CLICK HERE to create a profile!</a></h3>

      <form action="checklogin.php" method="post" name="form" id="form">
          <input type="hidden" name="referrer" value = '<?php if (isset ($_REQUEST['referrer'])) print $_REQUEST['referrer'];  ?>' />
            <table width="415">
              <tr>
              <td width="196" align="right"><strong>WWF Screen Name</strong></td>
              <td width="207"><input type="text" name="username" /></td>
              </tr>
              <tr>
              <td align="right"><strong>Password *</strong></td>
              <td><input type="password" name="password" value = '<?php //echo $password; ?>' /></td>
              </tr></table>
          <script type='text/javascript'>
if (navigator.cookieEnabled == 0) {
  document.write("<p>Cookies do not appear to be enabled. You need to enable cookies for WordsWithFriends.net to load properly.</p>");
}
</script>
            <p>* This is your password for this site only. It has nothing to do with your WWF application login.</p>
<input name="submit" type="submit" value="Submit" /></form>
            <p><a href="email_password.php">Forgot Password / Change Password </a></p>
<?php
 }
 ?>

</div></center>
</body>
</html>
