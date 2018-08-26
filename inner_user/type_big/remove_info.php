<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: ../login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];


	$id = intval($_REQUEST['id']);
	$sql_del = "delete from type_folder where folder_id=$id";

	require_once "../../include/dbda.class.php";
	$db = new DBDA();

	$result = $db->query_sql($sql_del);
	if($result)
	{
		echo json_encode(array('success'=>true));
	}
	else
	{
		echo json_encode(array('msg'=>'内部错误，数据库操作失败！'));
	}


/*
    require_once('../../include/db_def.php');

	$id = intval($_REQUEST['id']);

	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
		or die($str_inner_err . mysqli_error());

	$sql_del = "delete from type_folder where folder_id=$id";
	$result = @mysqli_query($link, $sql_del);
	if ($result)
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
