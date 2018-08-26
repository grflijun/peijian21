<?php
	//首先判断用户是否正确登录，如未登录，就转到登录页面。
	session_start();

	if(!session_is_registered("user_name") || $_SESSION['user_name'] == "")
	{
		Header("Location: login.html");
	}
?>
<!DOCTYPE html>
<html lang="cmn-Hans">
<head>
<meta charset="utf-8">
<title>增加或修改商品信息</title>
<link rel="stylesheet" href="../css/inner_style.css">
</head>

<body>

<?php
	//执行完毕后要返回的页面
	$link_back = "Location: user_config.php";

	if($_POST['ware_type'] == "big")
	{
		$link_back = "Location: bigtype.php";
	}
	else if($_POST['ware_type'] == "tiny")
	{
		$link_back = "Location: tinytype.php";
	}
	else if($_POST['ware_type'] == "ware")
	{
		$link_back = "Location: wareunit.php";
	}
	else
	{
		echo("<h3>参数错误（数据表类型）</h3>\n");
		exit();
	}


	//生成要执行的SQL语句
	$sql = "";
	if($_POST['proc_desc'] == "append")
	{
		$sql = "insert into ";

		if($_POST['ware_type'] == "big")
		{
			$str_type_name = str_replace("\\", "\\\\", $_POST['type_name']);
			$str_type_name = str_replace("'", "\\'", $str_type_name);

			$sql = $sql."WareBigType(no,lan_id,name,disp_idx) values(NULL,".$_POST['cmb_lans'].",'".$str_type_name."',".$_POST['disp_idx'].")";
		}
		else if($_POST['ware_type'] == "tiny")
		{
			$str_type_name = str_replace("\\", "\\\\", $_POST['type_name']);
			$str_type_name = str_replace("'", "\\'", $str_type_name);

			$sql = $sql."WareTinyType(no,bigNo,name,disp_idx) values(NULL,".$_POST['cmb_big_type'].",'".$str_type_name."',".$_POST['disp_idx'].")";
		}
		else
		{
			$str_ware_name = str_replace("\\", "\\\\", $_POST['ware_name']);
			$str_ware_name = str_replace("'", "\\'", $str_ware_name);

			$str_order_no = str_replace("\\", "\\\\", $_POST['order_no']);
			$str_order_no = str_replace("'", "\\'", $str_order_no);

			$str_comment = str_replace("\\", "\\\\", $_POST['comment']);
			$str_comment = str_replace("'", "\\'", $str_comment);

			$sql = $sql."Wares(no,tinyNo,name,image,orderNo,comment) values(NULL,".$_POST['cmb_tiny_type'].",'".$str_ware_name."','".$_POST['cmb_img_path']."','".$str_order_no."','".$str_comment."')";
		}
	}
	else if($_POST['proc_desc'] == "modify")
	{
		$sql = "update ";

		if($_POST['ware_type'] == "big")
		{
			$str_type_name = str_replace("\\", "\\\\", $_POST['type_name']);
			$str_type_name = str_replace("'", "\\'", $str_type_name);

			$sql = $sql."WareBigType set lan_id=".$_POST['cmb_lans'].",name='".$str_type_name."',disp_idx=".$_POST['disp_idx']." where no=".$_POST['item_id'];
		}
		else if($_POST['ware_type'] == "tiny")
		{
			$str_type_name = str_replace("\\", "\\\\", $_POST['type_name']);
			$str_type_name = str_replace("'", "\\'", $str_type_name);

			$sql = $sql."WareTinyType set bigNo=".$_POST['cmb_big_type'].",name='".$str_type_name."',disp_idx=".$_POST['disp_idx']." where no=".$_POST['item_id'];
		}
		else
		{
			$str_ware_name = str_replace("\\", "\\\\", $_POST['ware_name']);
			$str_ware_name = str_replace("'", "\\'", $str_ware_name);

			$str_order_no = str_replace("\\", "\\\\", $_POST['order_no']);
			$str_order_no = str_replace("'", "\\'", $str_order_no);

			$str_comment = str_replace("\\", "\\\\", $_POST['comment']);
			$str_comment = str_replace("'", "\\'", $str_comment);

			$sql = $sql."Wares set tinyNo=".$_POST['cmb_tiny_type'].",name='".$str_ware_name."',image='".$_POST['cmb_img_path']."',orderNo='".$str_order_no."',comment='".$str_comment."' where no=".$_POST['item_id'];
		}
	}
	else
	{
		echo("<h3>参数错误（操作类型）</h3>\n");
		exit();
	}


	//执行添加或修改操作
	include("../include/linkopen.php");

	$rs = mysql_query($sql, $link);

	include("../include/linkclose.php");

	Header($link_back);
?>

</body>
</html>
