<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: ../login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];


	$id = intval($_REQUEST['id']);
	$sql_del = "delete from guests where guest_id=$id";

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
?>
