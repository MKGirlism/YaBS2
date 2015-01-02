<?php
function mysqli_result($res, $row, $field=0) {
	$res->data_seek($row);
	$datarow = $res->fetch_aray();
	return $datarow[$field];
}
?>
