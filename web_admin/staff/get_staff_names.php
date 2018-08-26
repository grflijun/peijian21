<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['ADMIN_NAME']) || empty($_SESSION['ADMIN_NAME']))
	{
		Header("Location: admin_start.html");
	}


	$item_no_sup = ['staff_id' => 0, 'realname' => '-----无-----'];

	$sql_sel = "select staff_id,realname from staffs where out_use=0";
	if(isset($_GET['ignore_id']))
	{
		$ignore = intval($_GET['ignore_id']);

		if($ignore != 0)
		{
			$sql_sel .= " and staff_id<>$ignore";
		}
	}

	require_once "../../include/dbda.class.php";
	$db = new DBDA();

	echo $db->query_combo($sql_sel, $item_no_sup);



/*
	$ignore = 0;	//0表示查询所有记录，非0表示忽略对应ID的记录
	if(isset($_GET['ignore_id']))
	{
		$ignore = intval($_GET['ignore_id']);
	}

	$arr_ret[] = ['staff_id' => 0, 'realname' => '-----无-----'];

	$sql_sel = "select staff_id,realname from staffs where out_use=0";
	if($ignore != 0)
	{
		$sql_sel .= " and staff_id<>$ignore";
	}

	$link = mysqli_connect("localhost:3306", "renyanli", "dali123", "huijie")
		or die($str_inner_err . mysqli_error());

	$result = mysqli_query($link, $sql_sel)
	  or die($str_inner_err . mysqli_error());

	while($row = mysqli_fetch_array($result))
	{
		$arr_ret[] = ['staff_id' => $row[0], 'realname' => $row[1]];
	}

	mysqli_close($link);

	echo(json_encode($arr_ret));
*/
?>
