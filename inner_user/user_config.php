<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!isset($_SESSION['STAFF_ID']) || empty($_SESSION['STAFF_ID']))
	{
		Header("Location: login.html");
	}

	$s_staff_id = $_SESSION['STAFF_ID'];
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>系统设置</title>
<link rel="stylesheet" href="../css/inner_style.css">

<style>
li
{
	padding: 10px 20px;
	white-space:nowrap;
	font-size:24px;
}

li a
{
	line-height: 30px;
	text-decoration: none;
}

li a:visited
{
	color: blue;
}
</style>
</head>

<body>
<div class="bottom">
	<ul>
<?php

	$arr_func_name =
	[
		"确认新客户",
		"客户分组",
		"客户",
		"客户授权书",
		"客户订单",
		"客户发货单",
		"客户退货",
		"客户资金往来",
		"物流",
		"产品大分类",
		"产品小分类",
		"产品信息",
		"组产品价格",
		"客户产品价格",
		"供货商",
		"进货订单",
		"进货收货单",
		"仓管退货",
		"库存",
		"相册",
		"客户留言"
	];

	$arr_func_url =
	[
		"verify_new_guest/index.php",
		"guest_group/index.php",
		"guests/index.php",
		"javascript:void(0)",
		"javascript:void(0)",
		"javascript:void(0)",
		"javascript:void(0)",
		"javascript:void(0)",
		"logistics/index.php",
		"type_big/index.php",
		"type_small/index.php",
		"wareunit.php",
		"javascript:void(0)",
		"javascript:void(0)",
		"javascript:void(0)",
		"javascript:void(0)",
		"javascript:void(0)",
		"javascript:void(0)",
		"javascript:void(0)",
		"album.php",
		"msglist.php"
	];

	include("../include/linkopen.php");

	$sql = "select previliges from staffs where staff_id=$s_staff_id";
	$result = mysqli_query($link, $sql)
		  or die($str_inner_err . mysqli_error());

	if($row = mysqli_fetch_array($result))
	{
		$str_premission = $row[0];

		for($i = 0; $i < min(strlen($str_premission), count($arr_func_url)); $i++)
		{
			if($str_premission[$i] == '1')
			{
				echo "<li><a href='$arr_func_url[$i]'>$arr_func_name[$i]</a></li>\n";
			}
		}
	}

	include("../include/linkclose.php");
?>
		<li><a href="change_pwd.php">修改密码</a></li>
		<li><a href="logout.php">安全退出</a></li>
	</ul>
</div>

</body>
</html>
