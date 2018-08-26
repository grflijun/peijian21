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
		$type_name = $db->real_escape_string($_REQUEST['type_name']);
		$disp_idx = intval($_REQUEST['disp_idx']);
		$folder_id = intval($_REQUEST['folder_id']);

		$sql_ins = "insert into type_item(folder_id,type_name,disp_idx) values($folder_id,'$type_name',$disp_idx)";

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

	$type_name = mysqli_real_escape_string($link, $_REQUEST['type_name']);
	$disp_idx = intval($_REQUEST['disp_idx']);
	$folder_id = intval($_REQUEST['folder_id']);


	$sql_ins = "insert into type_item(folder_id,type_name,disp_idx) values($folder_id,'$type_name',$disp_idx)";

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
