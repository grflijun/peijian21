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
		$login_name = $db->real_escape_string($_REQUEST['login_name']);
		$login_pwd = $db->real_escape_string($_REQUEST['new_pass1']);
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

		//检查登录名是否重复
		$sql_check = "select guest_id from guests where login_name='$login_name'";
		$rs_check = $db->temp_sql($sql_check);

		if($rs_check->num_rows > 0)
		{
			echo json_encode(array('msg'=>'该用户名已存在。'));
		}
		else
		{
			$sql_ins = "insert into guests(login_name,login_pwd,reg_date,chk_status,chk_date,serve_staff_id,sheng_id,shi_id,xian_id,group_id,family_name,given_name,company,duty,phone,phone2,address) values('$login_name',SHA('$login_pwd'),NOW(),1,NOW(),$serve_staff_id,$sheng_id,$shi_id,$xian_id,$group_id,'$family_name','$given_name','$company','$duty','$phone','$phone2','$address')";

			if($db->temp_sql($sql_ins))
			{
				echo json_encode(array('success'=>true));
			}
			else
			{
				echo json_encode(array('msg'=>'内部错误，数据库操作失败！'));
			}
		}

		$db->DeleteTempDb();
	}




/*
	require_once('../../include/db_def.php');

	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
		or die($str_inner_err . mysqli_error());

	$login_name = mysqli_real_escape_string($link, $_REQUEST['login_name']);
	$login_pwd = mysqli_real_escape_string($link, $_REQUEST['new_pass1']);
	$serve_staff_id = intval($_REQUEST['serve_staff_id']);
	$sheng_id = intval($_REQUEST['sheng_id']);
	$shi_id = intval($_REQUEST['shi_id']);
	$xian_id = intval($_REQUEST['xian_id']);
	$group_id = intval($_REQUEST['group_id']);
	$family_name = mysqli_real_escape_string($link, $_REQUEST['family_name']);
	$given_name = mysqli_real_escape_string($link, $_REQUEST['given_name']);
	$company = mysqli_real_escape_string($link, $_REQUEST['company']);
	$duty = mysqli_real_escape_string($link, $_REQUEST['duty']);
	$phone = mysqli_real_escape_string($link, $_REQUEST['phone']);
	$phone2 = mysqli_real_escape_string($link, $_REQUEST['phone2']);
	$address = mysqli_real_escape_string($link, $_REQUEST['address']);

	//检查登录名是否重复
	$sql_check = "select guest_id from guests where login_name='$login_name'";
	$rs_check = mysqli_query($link, $sql_check);

	if(mysqli_num_rows($rs_check) == 1)
	{
		echo json_encode(array('msg'=>'该用户名已存在。'));
	}
	else
	{
		$sql_ins = "insert into guests(login_name,login_pwd,reg_date,chk_status,chk_date,serve_staff_id,sheng_id,shi_id,xian_id,group_id,family_name,given_name,company,duty,phone,phone2,address) values('$login_name',SHA('$login_pwd'),NOW(),1,NOW(),$serve_staff_id,$sheng_id,$shi_id,$xian_id,$group_id,'$family_name','$given_name','$company','$duty','$phone','$phone2','$address')";

		$result = @mysqli_query($link, $sql_ins);
		if($result)
		{
			echo json_encode(array('success'=>true));
		}
		else
		{
			echo json_encode(array('msg'=>'内部错误，插入数据库失败。'));
		}
	}

	mysqli_close($link);
*/
?>
