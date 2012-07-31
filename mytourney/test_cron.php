<?php

include "functions.php";
//print NAME_COOKIE;
if (isset ($_COOKIE[NAME_COOKIE])) {
    print $_COOKIE[NAME_COOKIE];
}
else {
    //print "no";

}

?>