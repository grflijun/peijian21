<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: ../login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];


	$page = isset($_POST['page'])? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows'])? intval($_POST['rows']) : 20;
	$offset = ($page - 1) * $rows;
 
	//只能操作自己对应的客户以及下级业务员对应的客户
	$sql_cnt = "select count(*) from guests where a.chk_status=1 and serve_staff_id in($s_staff_id, (select staff_id from staffs where superior_id=$s_staff_id))";
	$sql_data = "select a.guest_id guest_id,a.login_name login_name,a.inner_desc inner_desc,a.companytype companytype,a.lack_money lack_money,a.lan_code lan_code,a.logistics_id logistics_id,a.logistics_note logistics_note,b.logistics_name logistics_name from guests a,logistics b where a.chk_status=1 and a.logistics_id=b.logistics_id and a.serve_staff_id in($s_staff_id, (select staff_id from staffs where superior_id=$s_staff_id)) order by login_name limit $offset,$rows";

	require_once "../../include/dbda.class.php";
	$db = new DBDA();

	echo $db->query_grid($sql_cnt, $sql_data);



/*
    require_once('../../include/db_def.php');

	$page = isset($_POST['page'])? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows'])? intval($_POST['rows']) : 20;
	$offset = ($page - 1) * $rows;
	$arr_ret = array();


	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
		or die($str_inner_err . mysqli_error());

//只能操作自己对应的客户以及下级业务员对应的客户
	$sql_cnt = "select count(*) from guests where a.chk_status=1 and serve_staff_id in($s_staff_id, (select staff_id from staffs where superior_id=$s_staff_id))";
	$rs = mysqli_query($link, $sql_cnt)
	      or die($str_inner_err . mysqli_error());

	$row = mysqli_fetch_array($rs);
	$arr_ret["total"] = $row[0];
	$rs = mysqli_query($link, "select a.guest_id guest_id,a.login_name login_name,a.inner_desc inner_desc,a.companytype companytype,a.lack_money lack_money,a.lan_code lan_code,a.logistics_id logistics_id,a.logistics_note logistics_note,b.logistics_name logistics_name from guests a,logistics b where a.chk_status=1 and a.logistics_id=b.logistics_id and a.serve_staff_id in($s_staff_id, (select staff_id from staffs where superior_id=$s_staff_id)) order by login_name limit $offset,$rows");

	$items = array();
	while($row = mysqli_fetch_array($rs))
	{
		array_push($items, $row);
	}
	$arr_ret["rows"] = $items;

	mysqli_close($link);

	echo json_encode($arr_ret);
*/
?>
