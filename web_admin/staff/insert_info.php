<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['ADMIN_NAME']) || empty($_SESSION['ADMIN_NAME']))
	{
		Header("Location: ../admin_start.html");
	}


	require_once "../../include/dbda.class.php";
	$db = new DBDA();

	if($db->CreateTempDb())
	{
		$login_name = $db->real_escape_string(trim($_REQUEST['login_name']));
		$login_pwd = $db->real_escape_string($_REQUEST['new_pass1']);
		$realname = $db->real_escape_string(trim($_REQUEST['realname']));
		$duty = $db->real_escape_string(trim($_REQUEST['duty']));
		$superior_name = $db->real_escape_string(trim($_REQUEST['combo_sel_name']));
		$sup_id = intval($_REQUEST['superior_id']);
		$disb = intval($_REQUEST['out_use']);

		//检查登录名是否重复
		$sql_check = "select staff_id from staffs where login_name='$login_name'";
		$rs_check = $db->temp_sql($sql_check);

		if($rs_check->num_rows > 0)
		{
			echo json_encode(array('msg'=>'该用户名已存在。'));
		}
		else
		{
			$sql_ins = "insert into staffs(login_name,login_pwd,realname,duty,superior_id,superior_name,out_use) values('$login_name',SHA('$login_pwd'),'$realname','$duty',$sup_id,'$superior_name',$disb)";

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

	$login_name = mysqli_real_escape_string($link, trim($_REQUEST['login_name']));
	$login_pwd = mysqli_real_escape_string($link, $_REQUEST['new_pass1']);
	$realname = mysqli_real_escape_string($link, trim($_REQUEST['realname']));
	$duty = mysqli_real_escape_string($link, trim($_REQUEST['duty']));
	$superior_name = mysqli_real_escape_string($link, trim($_REQUEST['combo_sel_name']));
	$sup_id = intval($_REQUEST['superior_id']);
	$disb = intval($_REQUEST['out_use']);

	//检查是否有重复的
	$sql_check = "select staff_id from staffs where login_name='$login_name'";
	$rs_check = mysqli_query($link, $sql_check);

	if(mysqli_num_rows($rs_check) == 1)
	{
		echo json_encode(array('msg'=>'该用户名已存在。'));
	}
	else
	{
		$sql_ins = "insert into staffs(login_name,login_pwd,realname,duty,superior_id,superior_name,out_use) values('$login_name',SHA('$login_pwd'),'$realname','$duty',$sup_id,'$superior_name',$disb)";

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
