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

		//检查是否有下级
		$sql_check = "select count(*) from staffs where superior_id=$id";
		$rs_check = $db->temp_sql($sql_check);

		if($row_check[0] > 0)
		{
			echo json_encode(array('msg'=>"该用户有$row_check[0]个下属，请先解除上下级关系再删除用户。"));
		}
		else
		{
			$sql_del = "delete from staffs where staff_id=$id";

			if($db->temp_sql($sql_del))
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

	$id = intval($_REQUEST['id']);

	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
		or die($str_inner_err . mysqli_error());

	//检查是否有下级
	$sql_check = "select count(*) from staffs where superior_id=$id";
	$rs_check = mysqli_query($link, $sql_check);

	$row_check = mysqli_fetch_array($rs_check);
	if($row_check[0] > 0)
	{
		echo json_encode(array('msg'=>"该用户有$row_check[0]个下属，请先解除上下级关系再删除用户。"));
	}
	else
	{
		$sql_del = "delete from staffs where staff_id=$id";
		$result = @mysqli_query($link, $sql_del);
		if ($result)
		{
			echo json_encode(array('success'=>true));
		}
		else
		{
			echo json_encode(array('msg'=>'内部错误，删除失败。'));
		}
	}

	mysqli_close($link);
*/
?>