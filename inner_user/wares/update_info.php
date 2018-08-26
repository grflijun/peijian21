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
		$sheng_id = intval($_REQUEST['sheng_id']);
		$shi_id = intval($_REQUEST['shi_id']);
		$xian_id = intval($_REQUEST['xian_id']);
		$group_id = intval($_REQUEST['group_id']);
		$family_name = $db->real_escape_string($_REQUEST['family_name']);
		$given_name = $db->real_escape_string($_REQUEST['given_name']);
		$company = $db->real_escape_string($_REQUEST['company']);
		$duty = $db->real_escape_string($_REQUEST['duty']);
		$phone = $db->real_escape_string($_REQUEST['phone']);
		$phone2 = $db->real_escape_string($_REQUEST['phone2']);
		$address = $db->real_escape_string($_REQUEST['address']);

		$sql_modify = "update guests set serve_staff_id=$serve_staff_id,sheng_id=$sheng_id,shi_id=$shi_id,xian_id=$xian_id,group_id=$group_id,family_name='$family_name',given_name='$given_name',company='$company',duty='$duty',phone='$phone',phone2='$phone2',address='$address' where guest_id=$id";

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
?>
