<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: ../login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];


	$sql_sel = "select group_id,group_name from guest_groups";

	require_once "../../include/dbda.class.php";
	$db = new DBDA();

	echo $db->query_combo($sql_sel);

/*
	$sql_sel = "select group_id,group_name from guest_groups";

	$link = mysqli_connect("localhost:3306", "renyanli", "dali123", "huijie")
		or die($str_inner_err . mysqli_error());

	$result = mysqli_query($link, $sql_sel)
	  or die($str_inner_err . mysqli_error());

$arr_ret = array();
	while($row = mysqli_fetch_array($result))
	{
		array_push($arr_ret, $row);
//		$arr_ret[] = ['group_id' => $row[0], 'group_name' => $row[1]];
	}

	mysqli_close($link);

	echo(json_encode($arr_ret));
*/
?>
