
<h3>Database Check</h3>

<?php

$ls = new WebblerListing("Database Structure");
while (list($table, $tablename) = each($GLOBALS["tables"])) {
  $ls->addElement($table);
  if ($table != $tablename) {
    $ls->addColumn($table,"real name",$tablename);
  }
  $req = Sql_Query("show columns from $tablename",0);
  $columns = array();
  if (!Sql_Affected_Rows()) {
    $ls->addColumn($table,"exist",$GLOBALS["img_cross"]);
  }
  while ($row = Sql_Fetch_Array($req)) {
    $columns[strtolower($row["Field"])] = $row["Type"];
  }
  $tls = new WebblerListing($table);
  $struct = $DBstruct[$table];
  $haserror = 0;
  $indexes = $uniques = $engine = $category = '';
  if (is_array($struct)) {
     foreach ($struct as $column => $colstruct) {
      if (!ereg("index_",$column) &&
        !ereg("^unique_",$column) &&
        $column != "primary key" &&
        $column != "storage_engine" &&
        $column != 'table_category') {
          $tls->addElement($column);
          $exist = isset($columns[strtolower($column)]);
          if ($exist) {
            $tls->addColumn($column,"exist",$GLOBALS["img_tick"]);
          } else {
            $haserror = 1;
            $tls->addColumn($column,"exist",$GLOBALS["img_cross"]);
          }
        } else {
          if (ereg("index_",$column)) {
            $indexes .= $colstruct[0].'<br/>';
          }
          if (ereg("unique_",$column)) {
            $uniques .= $colstruct[0].'<br/>';
          }
#          if ($column == "primary key")
          if ($column == "storage_engine") {
            $engine = $colstruct[0];
          }
          if ($column == 'table_category') {
            $category = $colstruct;
          }
        }
    }
  }
  if (!$haserror) {
    $tls->collapse();
    $ls->addColumn($table,"ok",$GLOBALS["img_tick"]);
  } else {
    $ls->addColumn($table,"ok",$GLOBALS["img_cross"]);
  }
  if (!empty($indexes)) {
    $ls->addColumn($table,"index",$indexes);
  }
  if (!empty($uniques)) {
    $ls->addColumn($table,"unique",$uniques);
  }
  if (!empty($category)) {
    $ls->addColumn($table,"category",$category);
  }
 
  $ls->addColumn($table,"check",$tls->display());
}
print $ls->display();

