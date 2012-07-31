<?php

//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include"../auth_check_header.php";
require_once "../login_config.php";
require_once 'functions.php';

// close prior tourneys
$close_query = "update T_CONTROL set t_status='Complete' where t_id like 'M%'";
$result = mysql_query($close_query) or die("Couldn't execute $close_query");

$current_month_number = date('m');
$next_month_number = date('m', time() + 60*60*24*10);
$next_month_string = date('F', time() + 60*60*24*10);
$next_month_year = date('Y', time() + 60*60*24*10);


// Create mYYYY_MM table
$record = get_maximum_tournament_id();
$record++;
$old_id = "M".$next_month_year."_".$current_month_number;
$t_id = "M".$next_month_year."_".$next_month_number;
$t_start = $next_month_year."-".$next_month_number."-01";
$t_desc = $next_month_string.' '.$next_month_year ." Monthly";

$tournament_query = "INSERT into T_CONTROL (record, t_id, t_start, t_desc, t_longdesc, t_round, max_round, t_status)
                    VALUES ($record, '$t_id', '$t_start', '$t_desc', '', 1, 31, 'Register')
";
mysql_query($tournament_query) or die($tournament_query);

$tournament_query = "CREATE TABLE $t_id LIKE $old_id";
mysql_query($tournament_query) or die($tournament_query);

print $t_desc." successfully created";

?>
