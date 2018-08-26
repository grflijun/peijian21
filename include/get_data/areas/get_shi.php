<?php
	if(isset($_GET['sheng_id']))
	{
		$parent_id = intval($_GET['sheng_id']);

		if(0!= $parent_id)
		{
			$sql_sel = "select shi_id,shi_name from areas_shi where sheng_id=$parent_id order by shi_id";

			require_once "../../dbda.class.php";
			$db = new DBDA();

			echo $db->query_combo($sql_sel);
		}
	}

/*
	$parent_id = 0;	//上级地区ID，0表示没有选择上级地区
	if(isset($_GET['sheng_id']))
	{
		$parent_id = intval($_GET['sheng_id']);
	}

	$arr_ret[] = ['shi_id' => 0, 'shi_name' => '---请选择所属地区---'];


	if($parent_id != 0)
	{
		$sql_sel = "select shi_id,shi_name from areas_shi where sheng_id=$parent_id order by shi_id";

		$link = mysqli_connect("localhost:3306", "renyanli", "dali123", "huijie")
			or die($str_inner_err . mysqli_error());

		$result = mysqli_query($link, $sql_sel)
		  or die($str_inner_err . mysqli_error());

		while($row = mysqli_fetch_array($result))
		{
			$arr_ret[] = ['shi_id' => $row[0], 'shi_name' => $row[1]];
		}

		mysqli_close($link);
	}

	echo(json_encode($arr_ret));
*/
?>
