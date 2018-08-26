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
		$id = intval($_REQUEST['id']);
		$realname = $db->real_escape_string(trim($_REQUEST['realname']));
		$duty = $db->real_escape_string(trim($_REQUEST['duty']));
		$superior_name = $db->real_escape_string(trim($_REQUEST['combo_sel_name']));
		$sup_id = intval($_REQUEST['superior_id']);
		$disb = intval($_REQUEST['out_use']);

		//检查是否互为上下级
		$sql_check = "select staff_id from staffs where staff_id=$sup_id and superior_id=$id";
		$rs_check = $db->temp_sql($sql_check);

		if($rs_check->num_rows > 0)
		{
			echo json_encode(array('msg'=>'检查错误：互为上下级。'));
		}
		else
		{
			$sql_modify = "update staffs set realname='$realname',duty='$duty',superior_id=$sup_id,superior_name='$superior_name',out_use=$disb where staff_id=$id";

			if($db->temp_sql($sql_modify))
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


	$id = intval($_REQUEST['id']);
	$realname = mysqli_real_escape_string($link, trim($_REQUEST['realname']));
	$duty = mysqli_real_escape_string($link, trim($_REQUEST['duty']));
	$superior_name = mysqli_real_escape_string($link, trim($_REQUEST['combo_sel_name']));
	$sup_id = intval($_REQUEST['superior_id']);
	$disb = intval($_REQUEST['out_use']);

	//检查是否互为上下级
	$sql_check = "select staff_id from staffs where staff_id=$sup_id and superior_id=$id";
	$rs_check = mysqli_query($link, $sql_check);

	if(mysqli_num_rows($rs_check) == 1)
	{
		echo json_encode(array('msg'=>'检查错误：互为上下级。'));
	}
	else
	{
		$sql_modify = "update staffs set realname='$realname',duty='$duty',superior_id=$sup_id,superior_name='$superior_name',out_use=$disb where staff_id=$id";

		$result = @mysqli_query($link, $sql_modify);
		if ($result)
		{
			echo json_encode(array('success'=>true));
		}
		else
		{
			echo json_encode(array('msg'=>'内部错误，修改失败。'));
		}
	}

	mysqli_close($link);
*/
?>
