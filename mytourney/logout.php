<?php 

include"login_config.php";
require_once "classes.php";

header("Pragma: no-cache");
header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$cookie_value ="Unset";

setcookie(NAME_COOKIE,$cookie_value,time() + (-3600),"/", DOMAIN_COOKIE);
setcookie(ID_COOKIE,$cookie_value,time() + (-3600),"/", DOMAIN_COOKIE);

//unset(nam]);
//unset($_COOKIE[Constants::$cookie_id]);

?>


<p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><br />
      <font size="4">You are now logged out <br />
        <br />
        <a href="login.php">Return to Login      </a></font></strong></font></p>
