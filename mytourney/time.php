<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past


date_default_timezone_set('America/Los_Angeles');

echo date("g:i:s A D, F jS Y")." PST";

?>