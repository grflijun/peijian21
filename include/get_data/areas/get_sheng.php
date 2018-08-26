<?php
	$sql_sel = "select sheng_id,sheng_name from areas_sheng order by sheng_id";

	require_once "../../dbda.class.php";
	$db = new DBDA();

	echo $db->query_combo($sql_sel);

/*
	$arr_ret[] = ['sheng_id' => 0, 'sheng_name' => '---请选择所属地区---'];

	$sql_sel = "select sheng_id,sheng_name from areas_sheng order by sheng_id";

	$link = mysqli_connect("localhost:3306", "renyanli", "dali123", "huijie")
		or die($str_inner_err . mysqli_error());

	$result = mysqli_query($link, $sql_sel)
	  or die($str_inner_err . mysqli_error());

	while($row = mysqli_fetch_array($result))
	{
		$arr_ret[] = ['sheng_id' => $row[0], 'sheng_name' => $row[1]];
	}

	mysqli_close($link);

	echo(json_encode($arr_ret));
*/
?>
