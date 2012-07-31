<?php 
$arguments = getopt("c:");
if ($arguments['c'] == 'start') {
    print "success";
 mail('don@unswrc.com','Cron Job Test Script4',phpversion());
}
else {
   print "failure";
}

?>  