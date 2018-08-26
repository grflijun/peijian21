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
		$logistics_name = $db->real_escape_string($_REQUEST['logistics_name']);
		$phone = $db->real_escape_string($_REQUEST['phone']);
		$address = $db->real_escape_string($_REQUEST['address']);
		$note = $db->real_escape_string($_REQUEST['note']);

		$sql_ins = "insert into logistics(logistics_name,phone,address,note) values('$logistics_name','$phone','$address','$note')";

		if($db->temp_sql($sql_ins))
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

	$logistics_name = mysqli_real_escape_string($link, $_REQUEST['logistics_name']);
	$phone = mysqli_real_escape_string($link, $_REQUEST['phone']);
	$address = mysqli_real_escape_string($link, $_REQUEST['address']);
	$note = mysqli_real_escape_string($link, $_REQUEST['note']);


	$sql_ins = "insert into logistics(logistics_name,phone,address,note) values('$logistics_name','$phone','$address','$note')";

	$result = @mysqli_query($link, $sql_ins);
	if($result)
	{
		echo json_encode(array('success'=>true));
	}
	else
	{
		echo json_encode(array('msg'=>'Some errors occured.'));
	}

	mysqli_close($link);
*/
?>
