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
		$logistics_name = $db->real_escape_string($_REQUEST['logistics_name']);
		$phone = $db->real_escape_string($_REQUEST['phone']);
		$address = $db->real_escape_string($_REQUEST['address']);
		$note = $db->real_escape_string($_REQUEST['note']);

		$sql_modify = "update logistics set logistics_name='$logistics_name',phone='$phone',address='$address',note='$note' where logistics_id=$id";

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
	$logistics_name = mysqli_real_escape_string($link, $_REQUEST['logistics_name']);
	$phone = mysqli_real_escape_string($link, $_REQUEST['phone']);
	$address = mysqli_real_escape_string($link, $_REQUEST['address']);
	$note = mysqli_real_escape_string($link, $_REQUEST['note']);


	$sql_modify = "update logistics set logistics_name='$logistics_name',phone='$phone',address='$address',note='$note' where logistics_id=$id";

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
