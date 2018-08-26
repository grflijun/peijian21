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
 
 	$sql_cnt = "select count(*) from guests where chk_status=0";
	$sql_data = "select a.guest_id guest_id,a.login_name login_name,a.reg_date reg_date,a.sheng_id sheng_id,a.shi_id shi_id,a.xian_id xian_id,a.family_name family_name,a.given_name given_name,a.company company,a.duty duty,a.phone phone,a.phone2 phone2,a.address address,CONCAT(c.sheng_name,d.shi_name,e.xian_name) area_name from guests a,areas_sheng c,areas_shi d,areas_xian e where a.chk_status=0 and c.sheng_id=a.sheng_id and d.shi_id=a.shi_id and e.xian_id=a.xian_id order by a.login_name limit $offset,$rows";

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

	$sql_cnt = "select count(*) from guests where chk_status=0";
	$rs = mysqli_query($link, $sql_cnt)
	      or die($str_inner_err . mysqli_error());

	$row = mysqli_fetch_array($rs);
	$arr_ret["total"] = $row[0];
	$rs = mysqli_query($link, "select a.guest_id guest_id,a.login_name login_name,a.reg_date reg_date,a.sheng_id sheng_id,a.shi_id shi_id,a.xian_id xian_id,a.family_name family_name,a.given_name given_name,a.company company,a.duty duty,a.phone phone,a.phone2 phone2,a.address address,CONCAT(c.sheng_name,d.shi_name,e.xian_name) area_name from guests a,areas_sheng c,areas_shi d,areas_xian e where a.chk_status=0 and c.sheng_id=a.sheng_id and d.shi_id=a.shi_id and e.xian_id=a.xian_id order by a.login_name limit $offset,$rows");

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
