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
		$inner_desc = $db->real_escape_string($_REQUEST['inner_desc']);
		$companytype = $db->real_escape_string($_REQUEST['companytype']);
		$lan_code = intval($_REQUEST['lan_code']);
		$logistics_id = intval($_REQUEST['logistics_id']);
		$logistics_note = $db->real_escape_string($_REQUEST['logistics_note']);

		$sql_modify = "update guests set inner_desc='$inner_desc',companytype='$companytype',lan_code=$lan_code,logistics_id=$logistics_id,logistics_note='$logistics_note' where guest_id=$id";

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
	$inner_desc = mysqli_real_escape_string($link, $_REQUEST['inner_desc']);
	$companytype = mysqli_real_escape_string($link, $_REQUEST['companytype']);
	$lan_code = intval($_REQUEST['lan_code']);
	$logistics_id = intval($_REQUEST['logistics_id']);
	$logistics_note = mysqli_real_escape_string($link, $_REQUEST['logistics_note']);

	$sql_modify = "update guests set inner_desc='$inner_desc',companytype='$companytype',lan_code=$lan_code,logistics_id=$logistics_id,logistics_note='$logistics_note' where guest_id=$id";

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