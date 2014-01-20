<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=exceldata.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "RO List";
echo $table_ro;
echo "\nRO ITEM LIST";
echo $table_roItem;
?>