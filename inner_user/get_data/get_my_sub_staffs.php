<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: ../login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];


	$item_self = ['staff_id' => $s_staff_id, 'realname' => '----我自己----'];

	$sql_sel = "select staff_id,realname from staffs where out_use=0 and superior_id=$s_staff_id";
	if(isset($_GET['ignore_id']))
	{
		$ignore = intval($_GET['ignore_id']);

		if($ignore != 0)
		{
			$sql_sel .= " and staff_id<>$ignore";
		}
	}

	require_once "../../include/dbda.class.php";
	$db = new DBDA();

	echo $db->query_combo($sql_sel, $item_self);
?>
