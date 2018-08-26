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
 
 	$sql_cnt = "select count(*) from logistics";
	$sql_data = "select a.logistics_id logistics_id,a.logistics_name logistics_name,a.phone phone,a.address address,a.note note,count(b.guest_id) child_cnt from logistics a left join guests b on b.logistics_id=a.logistics_id group by a.logistics_id limit $offset,$rows";

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

	$rs = mysqli_query($link, "select count(*) from logistics")
	      or die($str_inner_err . mysqli_error());

	$row = mysqli_fetch_array($rs);
	$arr_ret["total"] = $row[0];
	$rs = mysqli_query($link, "select a.logistics_id logistics_id,a.logistics_name logistics_name,a.phone phone,a.address address,a.note note,count(b.guest_id) child_cnt from logistics a left join guests b on b.logistics_id=a.logistics_id group by a.logistics_id limit $offset,$rows");

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