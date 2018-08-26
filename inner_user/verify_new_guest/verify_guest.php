<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: ../login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];


	require_once "../../include/dbda.class.php";
	$db = new DBDA();

	if($db->CreateTempDb())
	{
		$id = intval($_REQUEST['id']);
		$serve_staff_id = intval($_REQUEST['serve_staff_id']);
		$group_id = intval($_REQUEST['group_id']);

		$sql_modify = "update guests set serve_staff_id=$serve_staff_id,group_id=$group_id,chk_status=1,chk_date=NOW() where guest_id=$id";

		if($db->temp_sql($sql_modify))
		{
			echo json_encode(array('success'=>true));
		}
		else
		{
			echo json_encode(array('msg'=>'内部错误，数据库操作失败！'));
		}

		$db->DeleteTempDb();
	}


/*
	require_once('../../include/db_def.php');

	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
		or die($str_inner_err . mysqli_error());

	$id = intval($_REQUEST['id']);
	$serve_staff_id = intval($_REQUEST['serve_staff_id']);
	$group_id = intval($_REQUEST['group_id']);

	$sql_modify = "update guests set serve_staff_id=$serve_staff_id,group_id=$group_id,chk_status=1,chk_date=NOW() where guest_id=$id";

	$result = @mysqli_query($link, $sql_modify);
	if ($result)
	{
		echo json_encode(array('success'=>true));
	}
	else
	{
		echo json_encode(array('msg'=>$sql_modify));
	}

	mysqli_close($link);
*/
?>
